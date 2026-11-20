<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHomeLogosTable extends Migration
{
    /**
     * The homepage's Our Brands and Our Clientele logos, managed in the admin panel.
     * Starts with the logos in the homepage design.
     */
    public function up()
    {
        Schema::create('home_logos', function (Blueprint $table) {
            $table->id();
            $table->string('type', 10); // brand or client
            $table->string('name', 100);
            $table->string('logo'); // path inside the public folder
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('ranking')->default(0);
            $table->timestamps();
            $table->index(['type', 'is_active', 'ranking']);
        });

        $start = [
            'brand' => ['Stormtech' => 'brand-stormtech.jpg', 'Cross' => 'brand-cross.jpg', 'Moleskine' => 'brand-moleskine.jpg', 'XD Design' => 'brand-xddesign.jpg'],
            'client' => ['Microsoft' => 'client-microsoft.jpg', 'Amazon' => 'client-amazon.jpg', 'Deloitte' => 'client-deloitte.jpg', 'Accenture' => 'client-accenture.jpg'],
        ];
        foreach ($start as $type => $logos) {
            $ranking = 1;
            foreach ($logos as $name => $file) {
                DB::table('home_logos')->insert(['type' => $type, 'name' => $name, 'logo' => 'web_frontend/assets/images/home/' . $file, 'is_active' => true, 'ranking' => $ranking++, 'created_at' => now(), 'updated_at' => now()]);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('home_logos');
    }
}
