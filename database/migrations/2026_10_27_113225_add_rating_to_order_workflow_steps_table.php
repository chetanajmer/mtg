<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingToOrderWorkflowStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_workflow_steps', function (Blueprint $table) {
            $table->integer('rating')->nullable()->after('internal_remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_workflow_steps', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
    }
}
