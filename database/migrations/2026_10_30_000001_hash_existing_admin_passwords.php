<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class HashExistingAdminPasswords extends Migration
{
    /**
     * Admin passwords used to be stored in plain text. Hash every one that
     * is not already a bcrypt hash so the new Hash::check() login works.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('admins')) {
            return;
        }

        // Make sure the column can hold a 60 character bcrypt hash
        DB::statement('ALTER TABLE admins MODIFY password VARCHAR(255) NULL');

        DB::table('admins')->orderBy('id')->each(function ($admin) {
            if (!empty($admin->password) && strpos($admin->password, '$2y$') !== 0) {
                DB::table('admins')
                    ->where('id', $admin->id)
                    ->update(['password' => Hash::make($admin->password)]);
            }
        });
    }

    /**
     * Hashing cannot be undone.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
