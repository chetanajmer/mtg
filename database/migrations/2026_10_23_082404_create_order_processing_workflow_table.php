<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProcessingWorkflowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_processing_workflows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cartorder_id')->unique();
            $table->integer('current_step')->default(1);
            $table->boolean('step1_received')->default(false);
            $table->boolean('step2_verification')->default(false);
            $table->boolean('step3_packing')->default(false);
            $table->boolean('step4_shipped')->default(false);
            $table->boolean('step5_delivered')->default(false);
            $table->timestamp('step1_completed_at')->nullable();
            $table->timestamp('step2_completed_at')->nullable();
            $table->timestamp('step3_completed_at')->nullable();
            $table->timestamp('step4_completed_at')->nullable();
            $table->timestamp('step5_completed_at')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable();
            $table->date('dispatch_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('completed_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_processing_workflows');
    }
}
