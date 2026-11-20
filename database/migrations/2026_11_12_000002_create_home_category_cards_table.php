<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHomeCategoryCardsTable extends Migration
{
    /**
     * The cards under Search by Category on the homepage (also the footer's Categories list),
     * managed from the admin panel. Starts with the six cards the homepage showed before.
     */
    public function up()
    {
        Schema::create('home_category_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->string('title', 100)->nullable(); // empty: the category's own name
            $table->string('image', 255); // path under public/
            $table->text('subcategory_ids')->nullable(); // JSON, up to 4; empty: the category's first 4 subcategories
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('ranking')->default(0);
            $table->timestamps();
        });

        $start = [
            ['Drinkware & Kitchenware', 'card-drinkware-kitchenware.webp', 'drinkware-and-kitchenware', ['bottles-220', 'mugs-618', 'tumblers-flasks-885', 'coasters-109']],
            ['Apparel & Accessories', 'card-apparel-accessories.webp', 'apparel-and-accessories', ['t-shirts-856', 'winterwear-636', 'caps-293', 'accessories-160']],
            ['Technology & Accessories', 'card-technology-accessories.webp', 'technology-and-accessories', ['headphones-and-ear-phones-577', 'mobile-accessories-245', 'power-banks-621', 'speakers-752']],
            ['Office Stationery & Writing', 'card-office-stationery-writing.webp', 'office-stationery-and-writing', ['notebooks-702', 'metal-aluminum-pens-890', 'desktop-items-826', 'pen-sets-227']],
            ['Gift Sets', 'card-gift-sets.webp', 'customized-items', ['customized-gift-sets-581']],
            ['Bags & Travels', 'card-bags-travels.webp', 'bags-and-travels', ['backpack-bags-430', 'travel-trolley-bags-109', 'briefcase-business-bags-637', 'pouches-sleeve-665']],
        ];
        foreach ($start as $i => [$title, $image, $categorySlug, $subSlugs]) {
            $categoryId = DB::table('categories')->where('slug', $categorySlug)->value('id');
            if (!$categoryId) {
                continue; // a database without this category simply starts without the card
            }
            $subIds = [];
            foreach ($subSlugs as $slug) {
                if ($id = DB::table('subcategories')->where('slug', $slug)->value('id')) {
                    $subIds[] = (int) $id;
                }
            }
            DB::table('home_category_cards')->insert([
                'category_id' => $categoryId,
                'title' => $title,
                'image' => 'web_frontend/assets/images/home/' . $image,
                'subcategory_ids' => json_encode($subIds),
                'is_active' => true,
                'ranking' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('home_category_cards');
    }
}
