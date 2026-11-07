<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlannerRecommendationsTable extends Migration
{
    /**
     * Budget Planner AI choices saved by the customer's inputs, so the same inputs reuse them
     * instead of asking the AI again. Only choices that passed the planner's checks are saved;
     * prices are always worked out fresh from the products.
     */
    public function up()
    {
        Schema::create('planner_recommendations', function (Blueprint $table) {
            $table->id();
            $table->char('input_key', 40)->unique(); // sha1 of the normalised inputs
            $table->string('occasion', 30);
            $table->string('occasion_label', 60);
            $table->string('team', 20);
            $table->string('budget', 20);
            $table->text('preferences')->nullable(); // JSON list of keys
            $table->unsignedTinyInteger('round')->default(0);
            $table->text('plan'); // JSON: per bundle the product ids, name and description
            $table->unsignedInteger('times_reused')->default(0);
            $table->timestamp('last_reused_at')->nullable();
            $table->timestamps();
        });

        Schema::table('planner_logs', function (Blueprint $table) {
            $table->boolean('reused')->default(false)->after('ai_written');
        });
    }

    public function down()
    {
        Schema::table('planner_logs', function (Blueprint $table) {
            $table->dropColumn('reused');
        });
        Schema::dropIfExists('planner_recommendations');
    }
}
