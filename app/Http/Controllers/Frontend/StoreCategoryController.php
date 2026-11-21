<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\FilterSetting;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreCategoryController extends Controller
{
    private const PER_PAGE = 24;

    private const SORTS = [
        'popular' => 'Popular',
        'newest' => 'Newest',
        'price-low' => 'Price: low to high',
        'price-high' => 'Price: high to low',
    ];

    public function category(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', 'online')->first();
        if (!$category) {
            abort(404);
        }

        $currentSubcategory = null;
        if (is_string($request->query('sub'))) {
            $currentSubcategory = Subcategory::where('catid', $category->id)->where('is_active', 'online')->where('slug', $request->query('sub'))->first();
        }

        $query = Product::where('is_active', 'online')->where('category', $category->id);
        if ($currentSubcategory) {
            $query->where('subcategory', $currentSubcategory->id);
        }
        // Popular: best sellers first, then the admin's ranking, then the latest
        $all = $query->orderByRaw("most_selling='online' desc")->orderByRaw('ranking IS NULL, ranking')->orderByDesc('id')->get();

        // The filters the admin has switched on for this category, in their order
        $groups = FilterSetting::ordered()->get()->filter(function ($setting) use ($category) {
            return $setting->showsOn($category->id);
        })->keyBy('filter_key');

        $budgets = [];
        if ($groups->has('price')) {
            foreach ($groups['price']->options ?: [] as $band) {
                $budgets[FilterSetting::bandKey($band)] = $band;
            }
        }

        $tags = $groups->has('sustainability') ? ($groups['sustainability']->options ?: []) : [];
        $filters = $this->filterOptions($all, $tags);
        $chosen = [];
        foreach (array_keys($filters) as $key) {
            $chosen[$key] = $groups->has($key) ? $this->chosenValues($request, $key, $filters[$key]) : [];
        }
        $budget = is_string($request->query('budget')) && isset($budgets[$request->query('budget')]) ? $request->query('budget') : '';
        $sort = is_string($request->query('sort')) && isset(self::SORTS[$request->query('sort')]) ? $request->query('sort') : 'popular';
        $view = $request->query('view') === 'list' ? 'list' : 'grid';

        $band = $budget !== '' ? $budgets[$budget] : null;
        $listed = $all->filter(function ($product) use ($chosen, $band, $tags) {
            return $this->matches($product, $chosen, $band, $tags);
        });
        if ($sort === 'newest') {
            $listed = $listed->sortByDesc('id');
        } elseif ($sort === 'price-low' || $sort === 'price-high') {
            $listed = $listed->sortBy(function ($product) {
                return $this->price($product);
            }, SORT_REGULAR, $sort === 'price-high');
        }

        $page = LengthAwarePaginator::resolveCurrentPage();
        $products = new LengthAwarePaginator(
            $listed->forPage($page, self::PER_PAGE)->values(),
            $listed->count(),
            self::PER_PAGE,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $pages = config('category_pages');
        $design = $pages[$category->slug] ?? $pages['default'];
        $sorts = self::SORTS;

        return view('web_frontend.category', [
            'category' => $category,
            'currentSubcategory' => $currentSubcategory,
            'products' => $products,
            'design' => $design,
            'banner' => $this->bannerUrl($category, $design),
            'groups' => $groups,
            'filters' => $filters,
            'chosen' => $chosen,
            'budget' => $budget,
            'budgets' => $budgets,
            'sort' => $sort,
            'sorts' => $sorts,
            'view' => $view,
            'pageLinks' => $this->pageLinks($products),
        ]);
    }

    // The old list view address now opens the same page
    public function listview($slug)
    {
        return redirect('category/' . $slug, 301);
    }

    // Subcategories open their category page with that subcategory selected
    public function subcategory($slug)
    {
        $subcategory = Subcategory::where('slug', $slug)->where('is_active', 'online')->first();
        $category = $subcategory ? Category::where('id', $subcategory->catid)->where('is_active', 'online')->first() : null;
        if (!$category) {
            abort(404);
        }

        return redirect('category/' . $category->slug . '?sub=' . urlencode($subcategory->slug), 301);
    }

    public function subcategorylist($slug)
    {
        return $this->subcategory($slug);
    }

    // A banner uploaded in the admin panel comes first, then the owner's banner for this category
    private function bannerUrl($category, $design)
    {
        if ($category->bannerimage && is_file(public_path('upload/category/' . $category->bannerimage))) {
            return asset('upload/category/' . $category->bannerimage);
        }
        if (!empty($design['banner']) && is_file(public_path('web_frontend/assets/images/categories/' . $design['banner']))) {
            return asset('web_frontend/assets/images/categories/' . $design['banner']);
        }

        return null;
    }

    // What each filter can offer, taken from the products in this category. Each is value => label.
    private function filterOptions($products, $tags)
    {
        $brandIds = $products->pluck('brand')->filter()->unique()->values();
        $brands = Brand::whereIn('id', $brandIds)->where('is_active', 'online')->orderBy('brandname')->pluck('brandname', 'id')->all();

        $materials = [];
        $colors = [];
        $delivery = [];
        $sustainability = [];
        foreach ($products as $product) {
            foreach ($this->materials($product) as $material) {
                $materials[$material] = $material;
            }
            foreach ($this->colors($product) as $color) {
                $colors[$color] = $color;
            }
            $time = trim((string) $product->estimated_delivery_time);
            if ($time !== '') {
                $delivery[$time] = $time;
            }
            foreach ($this->sustainability($product, $tags) as $label) {
                $sustainability[$label] = $label;
            }
        }
        // Colours show the names from the admin's colour list, or the code if it has none
        $colorNames = Color::pluck('name', 'code')->mapWithKeys(function ($name, $code) {
            return [strtolower($code) => $name];
        });
        foreach ($colors as $code => $label) {
            $colors[$code] = $colorNames[strtolower($code)] ?? $code;
        }
        ksort($materials);
        asort($colors);
        ksort($delivery);
        ksort($sustainability);

        return ['brand' => $brands, 'material' => $materials, 'color' => $colors, 'delivery' => $delivery, 'sustainability' => $sustainability];
    }

    // Values picked in the address, keeping only ones this category really offers
    private function chosenValues(Request $request, $key, $options)
    {
        $values = $request->query($key);
        if (!is_array($values)) {
            return [];
        }

        return array_values(array_filter($values, function ($value) use ($options) {
            return is_string($value) && isset($options[$value]);
        }));
    }

    private function matches($product, $chosen, $band, $tags)
    {
        if ($chosen['brand'] && !in_array((string) $product->brand, $chosen['brand'], true)) {
            return false;
        }
        if ($chosen['material'] && !array_intersect($this->materials($product), $chosen['material'])) {
            return false;
        }
        if ($chosen['color'] && !array_intersect($this->colors($product), $chosen['color'])) {
            return false;
        }
        if ($chosen['delivery'] && !in_array(trim((string) $product->estimated_delivery_time), $chosen['delivery'], true)) {
            return false;
        }
        if ($chosen['sustainability'] && !array_intersect($this->sustainability($product, $tags), $chosen['sustainability'])) {
            return false;
        }
        if ($band) {
            $price = $this->price($product);
            if ($price <= 0 || $price < $band['from'] || ($band['to'] !== null && $price >= $band['to'])) {
                return false;
            }
        }

        return true;
    }

    // The price the customer sees at the minimum order
    private function price($product)
    {
        return (float) $product->priceForQuantity($product->minOrderQuantity());
    }

    private function materials($product)
    {
        $found = [];
        $specifications = json_decode((string) $product->specifications, true);
        foreach (is_array($specifications) ? $specifications : [] as $row) {
            if (is_array($row) && isset($row['name'], $row['value']) && in_array(strtolower((string) $row['name']), ['material', 'cover'], true) && trim((string) $row['value']) !== '') {
                $found[] = trim((string) $row['value']);
            }
        }

        return $found;
    }

    private function colors($product)
    {
        $colors = json_decode((string) $product->colors, true);

        return array_values(array_filter(is_array($colors) ? $colors : [], function ($color) {
            return is_string($color) && preg_match('/^#[0-9a-f]{3,8}$/i', $color);
        }));
    }

    private function sustainability($product, $tags)
    {
        $found = [];
        foreach (explode(',', (string) $product->preferences) as $label) {
            $label = trim($label);
            if (in_array($label, $tags, true)) {
                $found[] = $label;
            }
        }

        return $found;
    }

    // Page numbers as in the design: 1 2 3 4 5 ... 12
    private function pageLinks($products)
    {
        $last = $products->lastPage();
        $current = $products->currentPage();
        if ($last <= 7) {
            return range(1, max(1, $last));
        }
        $start = max(1, min($current - 2, $last - 5));
        $links = range($start, $start + 4);
        if ($start > 1) {
            array_unshift($links, 1, '...');
        }
        $links[] = '...';
        $links[] = $last;

        return $links;
    }
}
