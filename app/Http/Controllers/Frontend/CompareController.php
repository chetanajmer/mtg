<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use DB;

class CompareController extends Controller
{
    /**
     * Get all active products for comparison selection
     */
    public function searchProducts(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        // Accept excluded_ids as an array (?excluded_ids[]=1) or as text (?excluded_ids=1,2)
        $excludedIds = $request->get('excluded_ids', []);
        if (!is_array($excludedIds)) {
            $excludedIds = explode(',', (string) $excludedIds);
        }
        $excludedIds = array_values(array_filter(array_map('intval', $excludedIds)));

        $query = Product::where('is_active', 'online')
            ->whereNotNull('name')
            ->where('name', '!=', '');

        if ($search !== '') {
            $query->where('name', 'like', '%' . addcslashes($search, '%_\\') . '%');
        }

        if (!empty($excludedIds)) {
            $query->whereNotIn('id', $excludedIds);
        }

        $products = $query->orderBy('name')
            ->select('id', 'name', 'thumbnail', 'image1', 'bulk_image', 'description', 'shortdescription', 'specification', 'slug', 'newarrival', 'sale', 'most_selling')
            ->limit(50)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'thumbnail' => $product->imageUrl() ?: asset('web_frontend/assets/images/product-main.jpg'),
                    'url' => route('web.product', $product->slug),
                    'description' => $product->shortdescription ?? $product->description ?? '',
                    'specification' => $product->specification ?? '',
                    'slug' => $product->slug,
                    'newarrival' => $product->newarrival,
                    'sale' => $product->sale,
                    'most_selling' => $product->most_selling,
                ];
            });

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    /**
     * Get product details including tier pricing for comparison
     */
    public function getProductDetails(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $productIds = $request->get('product_ids');
        $selectedQuantity = $request->get('selected_quantity', 500);

        $products = Product::whereIn('id', $productIds)
            ->where('is_active', 'online')
            ->select('id', 'name', 'thumbnail', 'image1', 'bulk_image', 'description', 'shortdescription', 'specification', 'slug', 'newarrival', 'sale', 'most_selling', 'base_price', 'price', 'sprice', 'tier_pricing_enabled', 'min_order_quantity')
            ->get()
            ->keyBy('id');

        $productDetails = [];

        foreach ($productIds as $productId) {
            $product = $products->get($productId);
            if (!$product) {
                continue;
            }

            // Get tier pricing
            $tierPrices = [];
            if ($product->tier_pricing_enabled === 'yes') {
                $tierPrices = DB::table('product_tier_prices')
                    ->where('product_id', $productId)
                    ->where('price', '>', 0)
                    ->orderBy('min_qty')
                    ->orderBy('sort_order')
                    ->get()
                    ->map(function ($tier) {
                        return [
                            'min_qty' => (int) $tier->min_qty,
                            'max_qty' => $tier->max_qty ? (int) $tier->max_qty : null,
                            'price' => (float) $tier->price,
                            'display_range' => $this->formatQuantityRange($tier->min_qty, $tier->max_qty),
                        ];
                    })->toArray();
            }

            // If no tier pricing, use base price as single tier
            if (empty($tierPrices)) {
                $basePrice = $product->displayPrice();
                $tierPrices[] = [
                    'min_qty' => 1,
                    'max_qty' => null,
                    'price' => $basePrice,
                    'display_range' => '1+',
                ];
            }

            // Calculate pricing for selected quantity
            $unitPrice = $product->priceForQuantity($selectedQuantity);
            $totalPrice = $unitPrice * $selectedQuantity;

            // Calculate savings (using first tier as reference)
            $referencePrice = $tierPrices[0]['price'];
            $savingsPerUnit = max(0, $referencePrice - $unitPrice);
            $savingsPercent = $referencePrice > 0 ? round(($savingsPerUnit / $referencePrice) * 100, 1) : 0;

            // Determine badge
            $badge = null;
            if ($product->most_selling === 'online') {
                $badge = ['text' => 'Most Popular', 'class' => 'b-o', 'icon' => 'crown'];
            } elseif ($product->newarrival === 'online') {
                $badge = ['text' => 'New Arrival', 'class' => 'b-b', 'icon' => 'star'];
            } elseif ($product->sale === 'online') {
                $badge = ['text' => 'On Sale', 'class' => 'b-g', 'icon' => 'tag'];
            }

            $productDetails[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'thumbnail' => $product->imageUrl() ?: asset('web_frontend/assets/images/product-main.jpg'),
                'url' => route('web.product', $product->slug),
                'description' => $product->shortdescription ?? $product->description ?? '',
                'specification' => $product->specification ?? '',
                'slug' => $product->slug,
                'tier_pricing' => $tierPrices,
                'base_price' => $referencePrice,
                'display_price' => $product->displayPrice(), // price when no tier matches (same as the cart)
                'moq' => $product->minOrderQuantity(),
                'selected_quantity' => $selectedQuantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'savings_per_unit' => $savingsPerUnit,
                'savings_percent' => $savingsPercent,
                'badge' => $badge,
            ];
        }

        return response()->json([
            'success' => true,
            'products' => $productDetails,
        ]);
    }

    /**
     * Add selected products to cart and redirect to cart page
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $cartController = new CartController();
        $added = [];
        $alreadyInCart = [];
        $skipped = [];

        foreach ($request->get('products') as $productData) {
            $product = Product::find($productData['id']);
            if (!$product || $product->is_active !== 'online') {
                $skipped[] = optional($product)->name ?: 'A product that is no longer available';
                continue;
            }
            // Already in the cart: leave that line (and its quantity) as it is and tell the customer
            if ($cartController->hasProduct($product->id)) {
                $alreadyInCart[] = $product->name;
                continue;
            }
            $item = $cartController->addProductToCart($product, $productData['quantity']);
            $added[] = ['id' => $product->id, 'name' => $product->name, 'quantity' => (int) $item->quantity];
        }

        if (!$added && !$alreadyInCart) {
            return response()->json([
                'success' => false,
                'message' => 'None of the selected products are available any more.',
                'skipped' => $skipped,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $added
                ? count($added) . ' product' . (count($added) === 1 ? '' : 's') . ' added to your quote cart.'
                : 'These products are already in your quote cart.',
            'added' => $added,
            'already_in_cart' => $alreadyInCart,
            'skipped' => $skipped,
            'count' => $cartController->lineCount(), // same number as the header cart badge
            'redirect_url' => route('cart.index'),
        ]);
    }

    /**
     * Format quantity range for display
     */
    private function formatQuantityRange($min, $max)
    {
        $min = (int) $min;
        if ($max === null) {
            return $min . '+';
        }
        return $min . ' – ' . $max;
    }

    /**
     * Save selected product IDs to session
     */
    public function saveSelection(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        session()->put('compare_products', $request->get('product_ids'));

        return response()->json([
            'success' => true,
        ]);
    }
}
