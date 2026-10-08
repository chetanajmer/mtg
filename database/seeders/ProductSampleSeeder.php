<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSampleSeeder extends Seeder
{
    /**
     * Seed sample products (with tier pricing, colors, branding options,
     * occasions & specifications) for client demo.
     *
     * Run: php artisan db:seed --class=ProductSampleSeeder
     *
     * @return void
     */
    public function run()
    {
        $this->seedMasters();

        $counter = DB::table('counters')->orderByDesc('id')->first();
        $lastCounter = !empty($counter) ? (int) $counter->counterstartsfrom : 0;
        $prefix = 'SG';
        $settings = DB::table('product_settings')->where('id', 1)->first();
        if (!empty($settings) && !empty($settings->startsfrom)) {
            $prefix = $settings->startsfrom;
        }

        $products = $this->products();

        foreach ($products as $data) {
            if (DB::table('products')->where('name', $data['name'])->exists()) {
                $this->command->info('Skipped (already exists): ' . $data['name']);
                continue;
            }

            $tiers = $data['tiers'];
            $imagePrefix = $data['image_prefix'];
            unset($data['tiers'], $data['image_prefix']);

            $lastCounter++;
            $data['modelno'] = $prefix . $lastCounter;
            $data['slug'] = $this->makeSlug($data['name']);

            $images = $this->findImages($imagePrefix);
            foreach ($images as $i => $img) {
                if ($i > 6) {
                    break;
                }
                $data['image' . ($i + 1)] = $img;
            }
            $data['thumbnail'] = $this->findThumbnail($images);

            if (empty($data['metatitle'])) {
                $data['metatitle'] = $data['name'];
                $data['metakey'] = $data['name'];
                $data['metadesc'] = $data['subheading'];
            }
            $data['title'] = $data['name'];
            $data['og_type'] = 'product';
            $data['twitter_card'] = 'summary';

            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            $productId = DB::table('products')->insertGetId($data);

            $sort = 0;
            foreach ($tiers as $tier) {
                DB::table('product_tier_prices')->insert([
                    'product_id' => $productId,
                    'min_qty' => $tier[0],
                    'max_qty' => $tier[1],
                    'price' => $tier[2],
                    'sort_order' => $sort++,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            DB::table('counters')->insert(['counterstartsfrom' => $lastCounter]);

            $this->command->info('Added: ' . $data['name'] . ' (ID ' . $productId . ', Model ' . $data['modelno'] . ')');
        }
    }

    /**
     * Master dropdown data used by the add/edit product form.
     * Only inserts values that don't already exist.
     */
    private function seedMasters()
    {
        $brandingOptions = [
            'Laser Engraving', 'Screen Printing', 'Full Color Print',
            'Debossing', 'Embroidery', 'UV Printing', 'Pad Printing',
        ];
        foreach ($brandingOptions as $name) {
            if (!DB::table('branding_options')->where('name', $name)->exists()) {
                DB::table('branding_options')->insert([
                    'name' => $name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $occasions = [
            'Corporate Gifts', 'Employee Onboarding', 'Client Gifting',
            'Eid Gifts', 'National Day', 'Trade Show', 'Ramadan',
        ];
        foreach ($occasions as $name) {
            if (!DB::table('occasions')->where('name', $name)->exists()) {
                DB::table('occasions')->insert([
                    'name' => $name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $preferences = [
            'Eco Friendly', 'Premium Look', 'Sustainable', 'Budget Friendly',
        ];
        foreach ($preferences as $name) {
            if (!DB::table('preferences')->where('name', $name)->exists()) {
                DB::table('preferences')->insert([
                    'name' => $name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    private function products()
    {
        return [
            [
                'name' => 'Premium Vacuum Bottle',
                'subheading' => '500ml | Keep Drinks Hot or Cold for Hours',
                'brand' => 5,
                'category' => 4,
                'subcategory' => 55,
                'sku' => 'PVB-500-BLK',
                'weight' => 0.35,
                'price' => 45,
                'sprice' => null,
                'rating' => 4.8,
                'review_count' => 124,
                'reviews_enabled' => 'yes',
                'colors' => json_encode(['#000000', '#0054a6', '#ffffff', '#d5bda3', '#007236']),
                'branding_options' => 'Laser Engraving,Screen Printing,Full Color Print',
                'min_order_quantity' => 50,
                'moq_unit' => 'Pieces',
                'tier_pricing_enabled' => 'yes',
                'tiers' => [
                    [50, 99, 45],
                    [100, 249, 38],
                    [250, 499, 32],
                    [500, 999, 28],
                    [1000, null, 24],
                ],
                'occasion_tags' => 'Corporate Gifts,Employee Onboarding',
                'preferences' => 'Sustainable,Premium Look',
                'ai_tags' => 'corporate bottle,vacuum flask,insulated bottle,executive gift',
                'quantity' => 5000,
                'stock' => '1',
                'low_stock' => 100,
                'is_active' => 'online',
                'newarrival' => 'online',
                'most_selling' => 'online',
                'featured' => 'online',
                'shortdescription' => '<p>A stylish and durable insulated bottle, perfect for corporate gifting. Keeps beverages hot for 12 hours and cold for 24 hours. Custom branding available.</p>',
                'specification' => '<p>Double-wall vacuum insulated bottle made from 304 grade stainless steel with a matte powder-coated finish.</p>',
                'specifications' => json_encode([
                    ['name' => 'Capacity', 'value' => '500ml'],
                    ['name' => 'Material', 'value' => '304 Grade Stainless Steel'],
                    ['name' => 'Insulation', 'value' => 'Double-Wall Vacuum'],
                    ['name' => 'Heat Retention', 'value' => '12 Hours Hot'],
                    ['name' => 'Cold Retention', 'value' => '24 Hours Cold'],
                    ['name' => 'Leak Proof', 'value' => 'Yes'],
                    ['name' => 'BPA Free', 'value' => 'Yes'],
                    ['name' => 'Finish', 'value' => 'Matte Powder Coat'],
                ]),
                'description' => '<p>The perfect blend of style, function, and sustainability. This premium vacuum bottle is an ideal corporate gift for employees, clients, and partners.</p><p>Custom branding is available through laser engraving, screen printing, or full color print - making every bottle a lasting reminder of your brand.</p>',
                'image_prefix' => '1-L-Copper-Vacuum-Flask',
            ],
            [
                'name' => 'A5 Notebook with 16GB USB',
                'subheading' => 'PU Leather Cover | Built-in 16GB USB Flash Drive',
                'brand' => 5,
                'category' => 14,
                'subcategory' => 13,
                'sku' => 'NB-A5-USB16',
                'weight' => 0.45,
                'price' => 38,
                'sprice' => null,
                'rating' => 4.6,
                'review_count' => 89,
                'reviews_enabled' => 'yes',
                'colors' => json_encode(['#000000', '#603913', '#440e62']),
                'branding_options' => 'Debossing,Screen Printing,Full Color Print',
                'min_order_quantity' => 50,
                'moq_unit' => 'Pieces',
                'tier_pricing_enabled' => 'yes',
                'tiers' => [
                    [50, 99, 38],
                    [100, 249, 34],
                    [250, 499, 30],
                    [500, null, 26],
                ],
                'occasion_tags' => 'Corporate Gifts,Trade Show,Employee Onboarding',
                'preferences' => 'Premium Look',
                'ai_tags' => 'notebook with usb,a5 diary,corporate stationery,office gift',
                'quantity' => 3000,
                'stock' => '1',
                'low_stock' => 100,
                'is_active' => 'online',
                'newarrival' => 'online',
                'shortdescription' => '<p>A premium A5 notebook with a built-in 16GB USB flash drive - the perfect companion for meetings, conferences, and corporate welcome kits.</p>',
                'specification' => '<p>PU leather cover, 192 ruled pages, integrated 16GB USB drive, elastic closure and ribbon bookmark.</p>',
                'specifications' => json_encode([
                    ['name' => 'Size', 'value' => 'A5 (21 x 14.8 cm)'],
                    ['name' => 'Cover', 'value' => 'PU Leather'],
                    ['name' => 'Pages', 'value' => '192 Ruled Pages'],
                    ['name' => 'USB Capacity', 'value' => '16GB'],
                    ['name' => 'Closure', 'value' => 'Elastic Band'],
                ]),
                'description' => '<p>Combine note-taking and data storage in one elegant package. The built-in 16GB USB drive sits discreetly in the cover, making this notebook a smart, practical corporate gift.</p>',
                'image_prefix' => 'A5-Size-Notebook-with-16GB-USB',
            ],
            [
                'name' => '10000mAh Wireless Powerbank',
                'subheading' => 'Fast Wireless Charging | Unique Pattern Design',
                'brand' => 5,
                'category' => 13,
                'subcategory' => 24,
                'sku' => 'PB-10K-WL',
                'weight' => 0.28,
                'price' => 65,
                'sprice' => null,
                'rating' => 4.7,
                'review_count' => 210,
                'reviews_enabled' => 'yes',
                'colors' => json_encode(['#000000', '#ffffff']),
                'branding_options' => 'UV Printing,Laser Engraving,Full Color Print',
                'min_order_quantity' => 25,
                'moq_unit' => 'Pieces',
                'tier_pricing_enabled' => 'yes',
                'tiers' => [
                    [25, 99, 65],
                    [100, 249, 58],
                    [250, 499, 52],
                    [500, null, 46],
                ],
                'occasion_tags' => 'Corporate Gifts,Trade Show,Client Gifting',
                'preferences' => 'Premium Look',
                'ai_tags' => 'powerbank,wireless charger,tech gift,10000mah',
                'quantity' => 2500,
                'stock' => '1',
                'low_stock' => 50,
                'is_active' => 'online',
                'most_selling' => 'online',
                'shortdescription' => '<p>A high-capacity 10,000mAh wireless powerbank with a unique pattern finish. Charges phones wirelessly or via cable - a tech gift your clients will use daily.</p>',
                'specification' => '<p>10,000mAh lithium polymer battery, 10W wireless output, dual USB ports, LED charge indicator.</p>',
                'specifications' => json_encode([
                    ['name' => 'Capacity', 'value' => '10,000mAh'],
                    ['name' => 'Wireless Output', 'value' => '10W'],
                    ['name' => 'Ports', 'value' => 'USB-A x2, USB-C x1'],
                    ['name' => 'Indicator', 'value' => 'LED Charge Level'],
                ]),
                'description' => '<p>Keep your brand in your clients\' hands every day. This wireless powerbank combines high capacity with a premium patterned finish and a generous branding area.</p>',
                'image_prefix' => '10-000mAh-wireless-powerbank-with-unique-pattern-design',
            ],
            [
                'name' => '6-Panel Recycled Cotton Cap',
                'subheading' => '280gsm Recycled Cotton | Adjustable Fit',
                'brand' => 15,
                'category' => 11,
                'subcategory' => 21,
                'sku' => 'CAP-6P-RC',
                'weight' => 0.09,
                'price' => 18,
                'sprice' => null,
                'rating' => 4.4,
                'review_count' => 56,
                'reviews_enabled' => 'yes',
                'colors' => json_encode(['#d5bda3', '#000000', '#440e62', '#ffffff', '#ed1c24']),
                'branding_options' => 'Embroidery,Screen Printing',
                'min_order_quantity' => 100,
                'moq_unit' => 'Pieces',
                'tier_pricing_enabled' => 'yes',
                'tiers' => [
                    [100, 249, 18],
                    [250, 499, 15],
                    [500, 999, 12],
                    [1000, null, 9],
                ],
                'occasion_tags' => 'Trade Show,National Day,Corporate Gifts',
                'preferences' => 'Eco Friendly,Sustainable,Budget Friendly',
                'ai_tags' => 'cap,recycled cotton cap,eco cap,event cap',
                'quantity' => 10000,
                'stock' => '1',
                'low_stock' => 200,
                'is_active' => 'online',
                'newarrival' => 'online',
                'shortdescription' => '<p>A classic 6-panel cap made from 280gsm recycled cotton. Comfortable, adjustable, and eco-friendly - perfect for events, promotions, and team kits.</p>',
                'specification' => '<p>280gsm recycled cotton twill, 6-panel structured design, metal buckle adjustable strap, embroidered eyelets.</p>',
                'specifications' => json_encode([
                    ['name' => 'Material', 'value' => '280gsm Recycled Cotton'],
                    ['name' => 'Panels', 'value' => '6-Panel Structured'],
                    ['name' => 'Fit', 'value' => 'Adjustable Metal Buckle'],
                    ['name' => 'Branding Area', 'value' => 'Front Panel 10 x 5 cm'],
                ]),
                'description' => '<p>Sustainable headwear that carries your brand. Made from recycled cotton with a comfortable structured fit and a large front panel ideal for embroidery or print.</p>',
                'image_prefix' => '6-Panel-280gr-Recycled-Cotton-Cap',
            ],
            [
                'name' => 'Anti-Spill Mug 350ml',
                'subheading' => 'Spill-Proof Lid | Double Wall Insulation',
                'brand' => 2,
                'category' => 4,
                'subcategory' => 53,
                'sku' => 'MUG-AS-350',
                'weight' => 0.22,
                'price' => 22,
                'sprice' => null,
                'rating' => 4.5,
                'review_count' => 76,
                'reviews_enabled' => 'yes',
                'colors' => json_encode(['#000000', '#ffffff', '#ed1c24', '#0054a6']),
                'branding_options' => 'Screen Printing,Pad Printing,Full Color Print',
                'min_order_quantity' => 50,
                'moq_unit' => 'Pieces',
                'tier_pricing_enabled' => 'yes',
                'tiers' => [
                    [50, 99, 22],
                    [100, 249, 19],
                    [250, 499, 16],
                    [500, null, 13],
                ],
                'occasion_tags' => 'Corporate Gifts,Employee Onboarding',
                'preferences' => 'Budget Friendly',
                'ai_tags' => 'mug,anti spill mug,travel mug,desk mug',
                'quantity' => 6000,
                'stock' => '1',
                'low_stock' => 150,
                'is_active' => 'online',
                'sale' => 'online',
                'shortdescription' => '<p>A clever anti-spill mug with a secure screw lid and double-wall insulation. Designed for desks, commutes, and busy workdays.</p>',
                'specification' => '<p>350ml double-wall mug with anti-spill suction base and twist-lock lid. Keeps drinks warm for up to 4 hours.</p>',
                'specifications' => json_encode([
                    ['name' => 'Capacity', 'value' => '350ml'],
                    ['name' => 'Lid', 'value' => 'Twist-Lock Spill Proof'],
                    ['name' => 'Insulation', 'value' => 'Double Wall'],
                    ['name' => 'Base', 'value' => 'Anti-Spill Suction'],
                ]),
                'description' => '<p>No more coffee spills on keyboards. The suction base keeps this mug upright even when bumped, and the double-wall build keeps drinks warm through long meetings.</p>',
                'image_prefix' => 'Anti-Spill-Mug-with-Black-lid',
            ],
            [
                'name' => 'Bopp Sport Water Bottle',
                'subheading' => '650ml | Lightweight BPA-Free Tritan',
                'brand' => 43,
                'category' => 4,
                'subcategory' => 55,
                'sku' => 'BT-SPT-650',
                'weight' => 0.12,
                'price' => 15,
                'sprice' => null,
                'rating' => 4.3,
                'review_count' => 41,
                'reviews_enabled' => 'yes',
                'colors' => json_encode(['#0054a6', '#8dc63f', '#f26522', '#ffffff00']),
                'branding_options' => 'Screen Printing',
                'min_order_quantity' => 100,
                'moq_unit' => 'Pieces',
                'tier_pricing_enabled' => 'no',
                'tiers' => [],
                'occasion_tags' => 'Trade Show,Corporate Gifts',
                'preferences' => 'Budget Friendly,Eco Friendly',
                'ai_tags' => 'sport bottle,water bottle,tritan bottle,event giveaway',
                'quantity' => 15000,
                'stock' => '1',
                'low_stock' => 300,
                'is_active' => 'online',
                'shortdescription' => '<p>A lightweight 650ml sports bottle made from BPA-free Tritan. A practical, budget-friendly giveaway for events, gyms, and outdoor campaigns.</p>',
                'specification' => '<p>650ml Tritan bottle with flip-top sipper lid and carry loop. Dishwasher safe.</p>',
                'specifications' => json_encode([
                    ['name' => 'Capacity', 'value' => '650ml'],
                    ['name' => 'Material', 'value' => 'BPA-Free Tritan'],
                    ['name' => 'Lid', 'value' => 'Flip-Top Sipper'],
                ]),
                'description' => '<p>Hydration meets branding. This lightweight sports bottle offers a large wraparound print area and a price point made for high-volume giveaways.</p>',
                'image_prefix' => 'Bopp-Sport-Water-Bottle',
            ],
        ];
    }

    /**
     * Find gallery images in public/upload/product matching a filename prefix.
     */
    private function findImages($prefix)
    {
        $files = glob(public_path('upload/product/' . $prefix . '*'));
        if (empty($files)) {
            return [];
        }
        natsort($files);
        return array_values(array_map('basename', $files));
    }

    /**
     * Pick the first gallery image that also exists in the thumbnail folder.
     */
    private function findThumbnail($images)
    {
        foreach ($images as $img) {
            if (is_file(public_path('upload/product/thumbnail/' . $img))) {
                return $img;
            }
        }
        return isset($images[0]) ? $images[0] : null;
    }

    private function makeSlug($name)
    {
        $slug = preg_replace('/[^A-Za-z0-9\-]/', '-', $name . '-' . rand(100, 1000));
        return strtolower(preg_replace('/-+/', '-', $slug));
    }
}
