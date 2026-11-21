<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeOrderidsUnique extends Migration
{
    /**
     * Website order numbers come from the orderids counter. It was MyISAM (locks did nothing) with no unique index,
     * so two customers sending a quote at the same moment could get the same number. Now InnoDB with a unique
     * orderid (Order::nextOrderNumber). Any duplicate counter rows are removed first (the table is only a counter).
     */
    public function up()
    {
        DB::statement('DELETE o1 FROM orderids o1 JOIN orderids o2 ON o1.orderid = o2.orderid AND o1.id > o2.id');
        DB::statement('ALTER TABLE orderids ENGINE = InnoDB');
        Schema::table('orderids', function ($table) {
            $table->unique('orderid');
        });
    }

    public function down()
    {
        Schema::table('orderids', function ($table) {
            $table->dropUnique(['orderid']);
        });
        DB::statement('ALTER TABLE orderids ENGINE = MyISAM');
    }
}
