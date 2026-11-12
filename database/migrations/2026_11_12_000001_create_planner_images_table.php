<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlannerImagesTable extends Migration
{
    /**
     * Budget Planner bundle pictures created by the AI. One picture per occasion and set of products
     * (plus the customer's logo when they added one), reused for every later request that matches,
     * so a picture is only ever paid for once. The file is in public/web_frontend/assets/images/planner/generated.
     */
    public function up()
    {
        Schema::create('planner_images', function (Blueprint $table) {
            $table->id();
            $table->char('image_key', 40)->unique(); // sha1 of occasion, product ids and logo
            $table->string('occasion', 30)->index();
            $table->string('occasion_label', 60);
            $table->string('product_ids', 255); // sorted, e.g. "12-40-77"
            $table->text('product_names')->nullable(); // JSON, as named when the picture was made
            $table->unsignedInteger('storeuser_id')->nullable()->index(); // set when the picture carries this customer's logo
            $table->string('file', 80);
            $table->string('model', 100)->nullable();
            $table->unsignedInteger('times_used')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('planner_images');
    }
}
