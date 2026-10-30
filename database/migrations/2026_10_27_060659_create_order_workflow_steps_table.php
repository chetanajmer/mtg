<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderWorkflowStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cartorder_id');
            $table->integer('step_number'); // 2-7 for the steps we're tracking
            $table->json('mockup_files')->nullable();
            $table->text('customer_message')->nullable();
            $table->string('approval_status')->nullable();
            $table->date('approved_date')->nullable();
            $table->boolean('send_notification')->default(false);
            $table->timestamps();

            $table->unique(['cartorder_id', 'step_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_workflow_steps');
    }
}
