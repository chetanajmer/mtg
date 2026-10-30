<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStep8DeliveryFieldsToOrderWorkflowSteps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_workflow_steps', function (Blueprint $table) {
            $table->string('delivery_type')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('delivered_to')->nullable();
            $table->text('delivery_address')->nullable();
            $table->json('delivery_proof')->nullable();
            $table->string('tracking_type')->nullable();
            $table->string('awb_number')->nullable();
            $table->string('courier_partner')->nullable();
            $table->date('dispatch_date')->nullable();
            $table->json('dispatch_proof')->nullable();
            $table->text('internal_remarks')->nullable();
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
            $table->dropColumn(['delivery_type', 'delivery_date', 'delivered_to', 'delivery_address', 'delivery_proof', 'tracking_type', 'awb_number', 'courier_partner', 'dispatch_date', 'dispatch_proof', 'internal_remarks']);
        });
    }
}
