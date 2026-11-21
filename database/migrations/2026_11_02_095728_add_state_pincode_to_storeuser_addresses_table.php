<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStatePincodeToStoreuserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('storeuser_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('storeuser_addresses', 'state')) {
                $table->string('state', 100)->nullable()->after('city');
            }
            if (!Schema::hasColumn('storeuser_addresses', 'pincode')) {
                $table->string('pincode', 20)->nullable()->after('state');
            }
        });

        // location is now derived from city/state; allow it to be empty
        DB::statement('ALTER TABLE `storeuser_addresses` MODIFY `location` VARCHAR(191) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('storeuser_addresses', function (Blueprint $table) {
            $table->dropColumn(['state', 'pincode']);
        });
    }
}
