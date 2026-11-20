<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageBannersTable extends Migration
{
    /**
     * Banner strips shown under the header, several per page (config/page_banners.php lists the pages).
     * A different one shows on every visit.
     */
    public function up()
    {
        Schema::create('page_banners', function (Blueprint $table) {
            $table->id();
            $table->string('page', 40)->index();
            $table->string('image', 255); // path under public/
            $table->string('alt', 150)->nullable();
            $table->string('link', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('ranking')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_banners');
    }
}
