<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFilterSettingsTable extends Migration
{
    /**
     * The filters in the category pages' left panel, managed in the admin panel.
     * Starts with the filters as they were built.
     */
    public function up()
    {
        Schema::create('filter_settings', function (Blueprint $table) {
            $table->id();
            $table->string('filter_key', 20)->unique();
            $table->string('label', 60);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('ranking')->default(0);
            $table->text('hidden_categories')->nullable(); // category ids where this filter is hidden
            $table->text('options')->nullable(); // price: the bands; sustainability: the tags that count
            $table->timestamps();
        });

        $now = now();
        $rows = [
            ['price', 'Price Range (AED)', json_encode([
                ['label' => 'Under AED 25', 'from' => 0, 'to' => 25],
                ['label' => 'AED 25 to 50', 'from' => 25, 'to' => 50],
                ['label' => 'AED 50 to 100', 'from' => 50, 'to' => 100],
                ['label' => 'AED 100 and above', 'from' => 100, 'to' => null],
            ])],
            ['brand', 'Brand', null],
            ['material', 'Material', null],
            ['color', 'Color', null],
            ['delivery', 'Delivery Time', null],
            ['sustainability', 'Sustainability', json_encode(['Eco Friendly', 'Sustainable'])],
        ];
        foreach ($rows as $i => $row) {
            DB::table('filter_settings')->insert([
                'filter_key' => $row[0],
                'label' => $row[1],
                'is_active' => true,
                'ranking' => $i + 1,
                'options' => $row[2],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('filter_settings');
    }
}
