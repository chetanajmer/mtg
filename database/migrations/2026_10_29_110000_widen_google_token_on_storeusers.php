<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class WidenGoogleTokenOnStoreusers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `storeusers` MODIFY `google_token` TEXT NULL');
        DB::statement('ALTER TABLE `storeusers` MODIFY `facebook_token` TEXT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `storeusers` MODIFY `google_token` VARCHAR(191) NULL');
        DB::statement('ALTER TABLE `storeusers` MODIFY `facebook_token` VARCHAR(191) NULL');
    }
}
