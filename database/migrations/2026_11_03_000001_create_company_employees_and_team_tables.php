<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyEmployeesAndTeamTables extends Migration
{
    /**
     * Customer portal "Employees" page:
     *  - company_employees: the company's gift recipients (no login)
     *  - company_team_members: colleagues who log in to the same company
     *    account; account_id is the owner's storeusers id, user_id the
     *    member's own storeusers row once they accept the invitation.
     */
    public function up()
    {
        Schema::create('company_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->index();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('designation', 100)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('city', 100)->nullable();
            $table->date('birthday')->nullable();
            $table->date('joined_on')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('company_team_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->index();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('name');
            $table->string('email');
            $table->string('role', 20);
            $table->string('status', 20)->default('invited');
            $table->string('token_hash', 64)->nullable()->index();
            $table->unsignedInteger('invited_by')->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            $table->unique(['account_id', 'email']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_team_members');
        Schema::dropIfExists('company_employees');
    }
}
