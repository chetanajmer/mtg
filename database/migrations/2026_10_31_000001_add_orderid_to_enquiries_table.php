<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderidToEnquiriesTable extends Migration
{
    /**
     * Link each cart enquiry to the Store Enquiries order created from it.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('orderid', 50)->nullable()->index()->after('id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn('orderid');
        });
    }
}
