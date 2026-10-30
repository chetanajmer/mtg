<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMockupApprovalToOrderProcessingWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_processing_workflows', function (Blueprint $table) {
            $table->json('mockup_files')->nullable()->after('notes');
            $table->text('customer_message')->nullable()->after('mockup_files');
            $table->date('approved_date')->nullable()->after('customer_message');
            $table->string('mockup_status')->default('pending')->after('approved_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_processing_workflows', function (Blueprint $table) {
            $table->dropColumn(['mockup_files', 'customer_message', 'approved_date', 'mockup_status']);
        });
    }
}
