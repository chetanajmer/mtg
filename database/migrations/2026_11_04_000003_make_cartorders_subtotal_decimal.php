<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeCartordersSubtotalDecimal extends Migration
{
    /**
     * Line subtotals were whole numbers, which dropped the fils from every order line.
     * Existing rows keep their values; a subtotal that lost its fils is not restored.
     */
    public function up()
    {
        DB::statement('ALTER TABLE cartorders MODIFY subtotal DECIMAL(12,2) NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE cartorders MODIFY subtotal INT NULL');
    }
}
