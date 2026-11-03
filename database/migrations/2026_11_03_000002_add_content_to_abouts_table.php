<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddContentToAboutsTable extends Migration
{
    /**
     * Editable text and image of the new About Us page, saved by the admin
     * About editor. Empty = the defaults in config/about_page.php.
     */
    public function up()
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->json('content')->nullable();
        });
        // The page reads row 1; make sure it exists
        if (!DB::table('abouts')->where('id', 1)->exists()) {
            DB::table('abouts')->insert(['id' => 1]);
        }
    }

    public function down()
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropColumn('content');
        });
    }
}
