<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class HashExistingStoreuserPasswords extends Migration
{
    /**
     * Older store users were saved with plain text passwords. Hash every
     * password that is not already a bcrypt hash.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('storeusers')) {
            return;
        }

        // Make sure the column can hold a 60 character bcrypt hash
        DB::statement('ALTER TABLE storeusers MODIFY password VARCHAR(255) NULL');

        DB::table('storeusers')->orderBy('id')->each(function ($user) {
            if (!empty($user->password) && strpos($user->password, '$2y$') !== 0) {
                DB::table('storeusers')
                    ->where('id', $user->id)
                    ->update(['password' => Hash::make($user->password)]);
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
