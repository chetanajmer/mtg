<?php

namespace App\Services;

use App\Models\Product;

/**
 * Builds the Budget Planner's bundles from real products. Prices always come
 * from Product::priceForQuantity(), so a bundle costs what the cart will charge.
 */
class GiftPlanner
{
    /**
     * @param callable|null $chooser ($shortlist, $tierLimits, $round, $retry) may return the AI's choice of bundles.
     *               Anything it returns is checked here; if it fails, the chooser is asked once more with $retry true,
     *               and a second failure keeps the standard bundles.
     * @return array bundles, plus broadened (nothing tagged for the occasion), unmatchedPreferences
     *               (labels no product matched), aiWritten, and plan (the accepted choice, or null)
     */
    public function recommend($occasionKey, $teamKey, $budgetKey, array $preferenceKeys = [], $round = 0, $customOccasion = null, $chooser = null)
    {
        $occasion = config('gift_planner.occasions')[$occasionKey];
        if ($occasionKey === 'other' && $customOccasion) {
            $occasion['tags'] = [$customOccasion];
        }
        $team = config('gift_planner.team_sizes')[$teamKey];
        $budget = config('gift_planner.budgets')[$budgetKey];
        $preferences = array_intersect_key(config('gift_planner.preferences'), array_flip($preferenceKeys));

        $candidates = $this->candidates($occasion, $team, $budget['max'], $preferences);

        $unmatched = [];
        foreach ($preferences as $key => $preference) {
            $found = false;
            foreach ($candidates as $candidate) {
                $found = $found || in_array($key, $candidate['matched'], true);
            }
            if (!$found) {
                $unmatched[] = $preference['label'];
            }
        }

        $hasExact = false;
        foreach ($candidates as $candidate) {
            $hasExact = $hasExact || $candidate['exact'];
        }

        $images = config('gift_planner.bundle_images.' . $occasionKey, []);
        $bundles = $this->bundles($candidates, $budget['max'], $occasion['name'], $images, $round);
        $chosen = null;
        $plan = null;
        if ($chooser && $bundles) {
            $shortlist = $this->shortlist($candidates);
            $limits = $this->tierLimits($budget['max']);
            $plan = $chooser($shortlist, $limits, $round, false);
            $chosen = $plan ? $this->bundlesFromPlan($plan, $candidates, $budget['max'], $images, count($bundles)) : null;
            // A choice that no longer fits (a saved one, after product or price changes): the chooser may ask once more
            if (!$chosen && $plan) {
                $plan = $chooser($shortlist, $limits, $round, true);
                $chosen = $plan ? $this->bundlesFromPlan($plan, $candidates, $budget['max'], $images, count($bundles)) : null;
            }
            $bundles = $chosen ?: $bundles;
        }

        return [
            'bundles' => $bundles,
            'broadened' => !empty($occasion['tags']) && !empty($candidates) && !$hasExact,
            'unmatchedPreferences' => $unmatched,
            'aiWritten' => (bool) $chosen,
            'plan' => $chosen ? $plan : null, // the accepted choice, for saving
        ];
    }

    /**
     * How many of a product to order for this team: never below its MOQ.
     */
    public function quantityFor(Product $product, $team)
    {
        return max((int) $team['min'], $product->minOrderQuantity());
    }

    private function tags($value)
    {
        $tags = array_map('trim', explode(',', str_replace(['[', ']', '"'], '', (string) $value)));
        return array_values(array_filter(array_map('mb_strtolower', $tags), 'strlen'));
    }

    private function candidates($occasion, $team, $maxBudget, $preferences)
    {
        $wanted = array_map('mb_strtolower', $occasion['tags']);
        $general = strtolower(config('gift_planner.general_tag'));
        $list = [];

        $products = Product::where('is_active', 'online')->whereNotNull('occasion_tags')->where('occasion_tags', '!=', '')->orderBy('id')->get();
        foreach ($products as $product) {
            $tags = $this->tags($product->occasion_tags);
            $exact = $this->matchesOccasion($wanted, $tags);
            if ($wanted && !$exact && !in_array($general, $tags, true)) {
                continue;
            }
            // Too large a minimum order for this team
            if ($product->minOrderQuantity() > $team['max']) {
                continue;
            }
            $quantity = $this->quantityFor($product, $team);
            $price = $product->priceForQuantity($quantity);
            if ($price <= 0 || $price > $maxBudget) {
                continue;
            }

            $matched = $this->matchedPreferences($product, $preferences);
            $key = strtolower(trim($product->name));
            $score = ($exact ? 2 : 0) + 3 * count($matched);
            if (isset($list[$key]) && $list[$key]['score'] >= $score) {
                continue; // the same product listed twice
            }
            $list[$key] = compact('product', 'quantity', 'price', 'matched', 'exact', 'score');
        }

        usort($list, function ($a, $b) {
            return $b['score'] <=> $a['score'] ?: $a['price'] <=> $b['price'];
        });
        return array_slice($list, 0, config('gift_planner.shortlist_size'));
    }

    /**
     * Same tag, or one inside the other, so "national day gifts" finds "National Day".
     */
    private function matchesOccasion($wanted, $tags)
    {
        foreach ($wanted as $occasion) {
            foreach ($tags as $tag) {
                if ($occasion === $tag) {
                    return true;
                }
                if (mb_strlen($occasion) >= 4 && mb_strlen($tag) >= 4 && (mb_strpos($tag, $occasion) !== false || mb_strpos($occasion, $tag) !== false)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function matchedPreferences(Product $product, $preferences)
    {
        $tags = $this->tags($product->preferences);
        $text = strtolower($product->name . ' ' . $product->ai_tags);
        $matched = [];
        foreach ($preferences as $key => $preference) {
            $hit = (bool) array_intersect(array_map('strtolower', $preference['tags'] ?? []), $tags);
            foreach ($preference['keywords'] ?? [] as $keyword) {
                $hit = $hit || strpos($text, $keyword) !== false;
            }
            if (!empty($preference['branding'])) {
                $hit = $hit || (bool) $this->tags($product->branding_options);
            }
            if ($hit) {
                $matched[] = $key;
            }
        }
        return $matched;
    }

    private function bundles($candidates, $maxBudget, $occasionName, $images, $round)
    {
        $tiers = config('gift_planner.tiers');
        $sets = $this->combinations($candidates, max(array_column($tiers, 'items')));

        // Each round ("View More Options") leaves out the bundles of the rounds before it
        $used = [];
        $picked = [];
        for ($r = 0; $r <= $round; $r++) {
            $picked = [];
            foreach ($tiers as $tier) {
                $best = null;
                $bestValue = -INF;
                $target = $maxBudget * $tier['share'];
                foreach ($sets as $id => $set) {
                    if ($set['total'] > $target || count($set['items']) > $tier['items'] || isset($picked[$id]) || isset($used[$id])) {
                        continue;
                    }
                    $value = 10 * $set['total'] / $target + $set['score'] - $set['repeats'];
                    if ($value > $bestValue) {
                        $best = $id;
                        $bestValue = $value;
                    }
                }
                if ($best !== null) {
                    $picked[$best] = $sets[$best];
                }
            }
            $used += $picked;
        }

        // Cheapest first; the labels follow the prices, not the order they were picked
        usort($picked, function ($a, $b) {
            return $a['total'] <=> $b['total'];
        });
        $labels = count($picked) === 1 ? [$tiers[1]] : $tiers;

        $bundles = [];
        foreach ($picked as $i => $set) {
            $tier = $labels[$i];
            $bundles[] = $this->formatBundle($set['items'], $set['total'], $tier, $occasionName . ' ' . $tier['suffix'], $tier['description'], $images);
        }
        return $bundles;
    }

    private function formatBundle($items, $total, $tier, $name, $description, $images)
    {
        return [
            'badge' => $tier['badge'],
            'label' => $tier['label'],
            'name' => $name,
            'description' => $description,
            'featured' => $tier['badge'] === 'popular',
            'image' => isset($images[$tier['badge']]) ? asset('web_frontend/assets/images/planner/' . $images[$tier['badge']]) : null,
            'price' => round($total, 2),
            'items' => array_map(function ($candidate) {
                $product = $candidate['product'];
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => round($candidate['price'], 2),
                    'quantity' => $candidate['quantity'],
                    'moq' => $product->minOrderQuantity(),
                    'image' => $product->imageUrl(),
                    'url' => route('web.product', $product->slug),
                ];
            }, $items),
        ];
    }

    /**
     * What the AI may choose from: nothing about customers, only products in this shortlist.
     */
    private function shortlist($candidates)
    {
        return array_map(function ($candidate) {
            return [
                'id' => $candidate['product']->id,
                'name' => $candidate['product']->name,
                'price_per_person' => round($candidate['price'], 2),
                'suits_occasion' => $candidate['exact'],
                'matches_preferences' => $candidate['matched'],
            ];
        }, $candidates);
    }

    private function tierLimits($maxBudget)
    {
        return array_map(function ($tier) use ($maxBudget) {
            return ['tier' => $tier['label'], 'max_total_per_person' => round($maxBudget * $tier['share'], 2), 'max_items' => $tier['items']];
        }, config('gift_planner.tiers'));
    }

    /**
     * Turns the AI's choice into bundles, or returns null if any part breaks the rules:
     * only shortlisted products, no repeats, and each bundle within its tier's budget and size.
     * Labels follow the prices, as they do for the standard bundles.
     */
    private function bundlesFromPlan(array $plan, $candidates, $maxBudget, $images, $expected)
    {
        if (count($plan) !== $expected) {
            return null;
        }
        $byId = [];
        foreach ($candidates as $candidate) {
            $byId[$candidate['product']->id] = $candidate;
        }

        $sets = [];
        $seen = [];
        foreach ($plan as $row) {
            $ids = array_values(array_unique(array_map('intval', (array) ($row['ids'] ?? []))));
            if (!$ids || array_diff($ids, array_keys($byId))) {
                return null;
            }
            sort($ids);
            $key = implode('-', $ids);
            if (isset($seen[$key])) {
                return null;
            }
            $seen[$key] = true;
            $items = array_map(function ($id) use ($byId) { return $byId[$id]; }, $ids);
            $sets[] = ['items' => $items, 'total' => array_sum(array_column($items, 'price')), 'name' => $row['name'], 'description' => $row['description']];
        }

        usort($sets, function ($a, $b) {
            return $a['total'] <=> $b['total'];
        });
        $tiers = config('gift_planner.tiers');
        $labels = count($sets) === 1 ? [$tiers[1]] : $tiers;

        $bundles = [];
        foreach ($sets as $i => $set) {
            $tier = $labels[$i] ?? null;
            if (!$tier || count($set['items']) > $tier['items'] || $set['total'] > $maxBudget * $tier['share']) {
                return null;
            }
            $bundles[] = $this->formatBundle($set['items'], $set['total'], $tier, $set['name'], $set['description'], $images);
        }
        return $bundles;
    }

    /**
     * Every set of up to $size candidates, keyed by its product ids.
     */
    private function combinations($candidates, $size)
    {
        $sets = [];
        $walk = function ($start, $current) use (&$walk, &$sets, $candidates, $size) {
            for ($i = $start; $i < count($candidates); $i++) {
                $next = array_merge($current, [$candidates[$i]]);
                $categories = array_map(function ($c) { return $c['product']->category; }, $next);
                $ids = array_map(function ($c) { return $c['product']->id; }, $next);
                sort($ids);
                $sets[implode('-', $ids)] = [
                    'items' => $next,
                    'total' => array_sum(array_column($next, 'price')),
                    'score' => array_sum(array_column($next, 'score')) / count($next),
                    'repeats' => count($next) - count(array_unique($categories)),
                ];
                if (count($next) < $size) {
                    $walk($i + 1, $next);
                }
            }
        };
        $walk(0, []);
        return $sets;
    }
}
