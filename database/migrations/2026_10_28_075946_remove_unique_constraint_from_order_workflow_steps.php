<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueConstraintFromOrderWorkflowSteps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_workflow_steps', function (Blueprint $table) {
            $table->dropUnique(['cartorder_id', 'step_number']);
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
            $table->unique(['cartorder_id', 'step_number']);
        });
    }
}
