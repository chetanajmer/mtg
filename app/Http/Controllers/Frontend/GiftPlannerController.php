<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PlannerLog;
use App\Models\PlannerRecommendation;
use App\Services\GiftPlanner;
use App\Services\GiftPlannerAi;
use App\Services\PlannerBrandFiles;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class GiftPlannerController extends Controller
{
    public function index(PlannerBrandFiles $brand)
    {
        return view('web_frontend.corporate-gifting-budget-planner', [
            'planner' => config('gift_planner'),
            'brand' => session()->has('userid') ? $brand->list(session()->get('userid')) : ['logo' => null, 'assets' => []],
        ]);
    }

    /** The customer's logo or a brand asset for the bundle pictures (pop-up before the results). */
    public function brandStore(Request $request, PlannerBrandFiles $brand)
    {
        if (!session()->has('userid')) {
            return response()->json(['message' => 'Please log in.'], 401);
        }
        $data = $request->validate([
            'kind' => 'required|in:logo,asset',
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:' . PlannerBrandFiles::MAX_KB,
        ], [
            'file.mimes' => 'Please choose a JPG, PNG or WEBP picture.',
            'file.max' => 'Please choose a picture under 5 MB.',
            'file.uploaded' => 'Please choose a picture under 5 MB.',
        ]);
        $brand->store(session()->get('userid'), $data['kind'], $data['file']);
        return response()->json($brand->list(session()->get('userid')));
    }

    public function brandRemove(Request $request, PlannerBrandFiles $brand)
    {
        if (!session()->has('userid')) {
            return response()->json(['message' => 'Please log in.'], 401);
        }
        $data = $request->validate(['id' => ['required', 'regex:/^(logo|asset-[0-9a-f]{12})$/']]);
        $brand->remove(session()->get('userid'), $data['id']);
        return response()->json($brand->list(session()->get('userid')));
    }

    /** Preview for the pop-up: only the customer's own files. */
    public function brandShow($id, PlannerBrandFiles $brand)
    {
        $path = session()->has('userid') ? $brand->path(session()->get('userid'), $id) : null;
        abort_unless($path, 404);
        return response()->file($path, ['Content-Type' => 'image/png', 'Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }

    public function recommend(Request $request, GiftPlanner $planner, GiftPlannerAi $ai)
    {
        $data = $request->validate([
            'occasion' => ['required', Rule::in(array_keys(config('gift_planner.occasions')))],
            'people' => 'required|integer|min:1|max:' . config('gift_planner.max_people'),
            'budget_type' => 'required|in:person,total',
            'budget' => 'required|numeric|min:1|max:' . config('gift_planner.max_budget'),
            'preferences' => 'nullable|array|max:10',
            'preferences.*' => [Rule::in(array_keys(config('gift_planner.preferences')))],
            'round' => 'nullable|integer|min:0|max:20',
            'custom_occasion' => ['nullable', 'string', 'max:40', 'regex:/^[\pL\pM\pN\s&\'’.,\/-]+$/u'],
        ], [
            'custom_occasion.regex' => 'Please use only letters and numbers for the occasion.',
        ]);
        $people = (int) $data['people'];
        // A total budget is shared by everyone; everything below works per person
        $perPerson = round($data['budget_type'] === 'total' ? $data['budget'] / $people : (float) $data['budget'], 2);
        $teamLabel = $people . ' ' . ($people === 1 ? 'person' : 'people');
        $budgetLabel = 'AED ' . number_format($perPerson, 2) . '/person' . ($data['budget_type'] === 'total' ? ' (AED ' . number_format((float) $data['budget'], 2) . ' total)' : '');
        // Same text, same result: "retirement " and "Retirement" share their saved AI wording
        $custom = $data['occasion'] === 'other' ? trim(preg_replace('/\s+/u', ' ', (string) ($data['custom_occasion'] ?? ''))) : '';

        $round = (int) ($data['round'] ?? 0);

        $preferences = $data['preferences'] ?? [];

        // Visitors who aren't logged in get the standard bundles for their choices: no AI wording or pictures
        if (!session()->has('userid')) {
            $result = $planner->recommend($data['occasion'], $people, $perPerson, $preferences, $round, $custom ?: null);
            unset($result['plan']);
            $result['guest'] = true;
            $result['perPerson'] = $perPerson;
            $this->logResult(null, $data['occasion'], $custom, $teamLabel, $budgetLabel, $preferences, $round, $result);
            return response()->json($result);
        }

        // A problem with the AI never costs the customer their bundles
        $occasionLabel = $custom ?: config('gift_planner.occasions')[$data['occasion']]['label'];
        // Same inputs as an earlier customer: reuse that AI choice instead of asking the AI again
        $inputKey = PlannerRecommendation::keyFor($data['occasion'], $custom, $people, $perPerson, $preferences, $round);
        $saved = PlannerRecommendation::where('input_key', $inputKey)
            ->where('updated_at', '>=', now()->subDays((int) config('gift_planner.reuse_days')))->first();
        $source = null;
        $chooser = function ($shortlist, $tiers, $variation, $retry) use ($ai, $occasionLabel, $teamLabel, $saved, &$source) {
            if ($saved && !$retry) {
                $source = 'saved';
                return $saved->plan;
            }
            // Only a saved choice that no longer fits is worth a new AI request; a new choice that failed is not asked again
            if ($retry && $source !== 'saved') {
                return null;
            }
            $source = 'ai';
            try {
                return $ai->choose($shortlist, $tiers, $occasionLabel, $teamLabel, $variation);
            } catch (\Throwable $e) {
                Log::warning('Budget planner AI: ' . $e->getMessage());
                return null;
            }
        };
        $result = $planner->recommend($data['occasion'], $people, $perPerson, $preferences, $round, $custom ?: null, $chooser);
        $result['reused'] = $result['aiWritten'] && $source === 'saved';
        $result['perPerson'] = $perPerson;
        $this->saveChoice($inputKey, $saved, $result, $source, $data + ['people' => $people, 'per_person' => $perPerson], $occasionLabel, $round);
        unset($result['plan']);

        try {
            $result['bundles'] = $ai->decorate($result['bundles'], $data['occasion'], $custom ?: null, app(PlannerBrandFiles::class)->fingerprint(session()->get('userid')), session()->get('userid'));
        } catch (\Throwable $e) {
            Log::warning('Budget planner AI: ' . $e->getMessage());
        }

        $this->logResult(session()->get('userid'), $data['occasion'], $custom, $teamLabel, $budgetLabel, $preferences, $round, $result);
        return response()->json($result);
    }

    /**
     * Saves a new accepted AI choice by its inputs (replacing an older one), or counts a reuse.
     */
    private function saveChoice($inputKey, $saved, array $result, $source, array $data, $occasionLabel, $round)
    {
        try {
            if ($result['reused']) {
                $saved->timestamps = false; // the age of the choice decides when it is refreshed
                $saved->increment('times_reused', 1, ['last_reused_at' => now()]);
            } elseif ($source === 'ai' && $result['plan']) {
                PlannerRecommendation::updateOrCreate(['input_key' => $inputKey], [
                    'occasion' => $data['occasion'], 'occasion_label' => mb_substr($occasionLabel, 0, 60),
                    'team' => (string) $data['people'], 'budget' => (string) $data['per_person'], 'preferences' => array_values($data['preferences'] ?? []),
                    'round' => $round, 'plan' => $result['plan'], 'times_reused' => 0, 'last_reused_at' => null,
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Budget planner saved choice: ' . $e->getMessage());
        }
    }

    /**
     * Saves what was shown for the admin Planner Log. A problem here never costs the customer their bundles.
     */
    private function logResult($userId, $occasion, $custom, $teamLabel, $budgetLabel, array $preferences, $round, array $result)
    {
        try {
            $config = config('gift_planner');
            PlannerLog::create([
                'storeuser_id' => $userId,
                'occasion' => $occasion,
                'occasion_label' => mb_substr($custom ?: $config['occasions'][$occasion]['label'], 0, 60),
                'team_label' => mb_substr($teamLabel, 0, 40),
                'budget_label' => mb_substr($budgetLabel, 0, 40),
                'preferences' => array_values(array_map(function ($key) use ($config) {
                    return $config['preferences'][$key]['label'];
                }, $preferences)),
                'round' => $round,
                'ai_written' => !empty($result['aiWritten']),
                'reused' => !empty($result['reused']),
                'bundle_count' => count($result['bundles']),
                'bundles' => array_map(function ($bundle) {
                    return [
                        'name' => $bundle['name'], 'label' => $bundle['label'], 'price' => $bundle['price'],
                        'items' => array_map(function ($item) {
                            return ['id' => $item['id'], 'name' => $item['name'], 'quantity' => $item['quantity'], 'price' => $item['price']];
                        }, $bundle['items']),
                    ];
                }, $result['bundles']),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Budget planner log: ' . $e->getMessage());
        }
    }

    /**
     * The page asks for each bundle's picture after showing the bundle, as creating one takes a while.
     */
    public function image(Request $request, GiftPlannerAi $ai)
    {
        if (!session()->has('userid')) {
            return response()->json(['message' => 'Please log in.'], 401);
        }
        $data = $request->validate(['token' => 'required|string|max:2000']);
        set_time_limit((int) config('gift_planner.ai.image_timeout') * 2 + 30);

        try {
            return response()->json(['url' => $ai->image($data['token'], session()->get('userid'))]);
        } catch (DecryptException $e) {
            return response()->json(['message' => 'Invalid request.'], 422);
        } catch (\Throwable $e) {
            Log::warning('Budget planner AI: ' . $e->getMessage());
            return response()->json(['url' => null]);
        }
    }
}
