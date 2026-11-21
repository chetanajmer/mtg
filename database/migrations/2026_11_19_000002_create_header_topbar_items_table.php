<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHeaderTopbarItemsTable extends Migration
{
    /**
     * Left side of the black top bar in the header: the language and currency menus and the offer texts
     * (admin: Setup & Config > Header Top Bar). Starts with what the client's design shows.
     */
    public function up()
    {
        Schema::create('header_topbar_items', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->index(); // language, currency or offer
            $table->string('text', 60);
            $table->string('link', 255)->nullable(); // offers only
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('ranking')->default(0);
            $table->timestamps();
        });

        $now = now();
        $rows = [['language', 'English', 1], ['currency', 'AED', 2], ['offer', '50% PRICE DROP', 3], ['offer', 'Extra 20% OFF', 4], ['offer', 'MTGPROMO20', 5]];
        foreach ($rows as [$type, $text, $ranking]) {
            DB::table('header_topbar_items')->insert(['type' => $type, 'text' => $text, 'is_active' => true, 'ranking' => $ranking, 'created_at' => $now, 'updated_at' => $now]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('header_topbar_items');
    }
}
