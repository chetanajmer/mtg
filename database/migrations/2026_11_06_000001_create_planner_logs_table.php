<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlannerLogsTable extends Migration
{
    /**
     * One row each time the Budget Planner shows bundles, for the admin log page.
     * Labels are copied so old rows still read right if the planner's options change.
     */
    public function up()
    {
        Schema::create('planner_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('storeuser_id')->nullable()->index(); // null = visitor not logged in
            $table->string('occasion', 30)->index();
            $table->string('occasion_label', 60);
            $table->string('team_label', 40);
            $table->string('budget_label', 40);
            $table->text('preferences')->nullable(); // JSON list of labels
            $table->unsignedTinyInteger('round')->default(0); // 0 = Show Recommendations, 1+ = View More Options
            $table->boolean('ai_written')->default(false);
            $table->unsignedTinyInteger('bundle_count')->default(0);
            $table->text('bundles')->nullable(); // JSON: name, label, price, items
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('planner_logs');
    }
}
