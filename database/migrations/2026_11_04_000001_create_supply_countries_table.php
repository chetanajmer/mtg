<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSupplyCountriesTable extends Migration
{
    /**
     * The "Supplies to" countries in the site header, managed in the admin panel.
     * Starts with the countries in the homepage design.
     */
    public function up()
    {
        Schema::create('supply_countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('ranking')->default(0);
            $table->timestamps();
        });

        $start = ['ae' => 'United Arab Emirates', 'sa' => 'Saudi Arabia', 'om' => 'Oman', 'kw' => 'Kuwait', 'qa' => 'Qatar', 'bh' => 'Bahrain', 'us' => 'United States', 'in' => 'India'];
        $ranking = 1;
        foreach ($start as $code => $name) {
            DB::table('supply_countries')->insert(['code' => $code, 'name' => $name, 'is_active' => true, 'ranking' => $ranking++, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('supply_countries');
    }
}
