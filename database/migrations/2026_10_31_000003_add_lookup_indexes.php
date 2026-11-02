<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddLookupIndexes extends Migration
{
    /**
     * Columns almost every page searches by. Plain (non-unique) indexes, so
     * existing duplicate values are not a problem. Skips any that exist.
     */
    private $indexes = [
        'products' => ['slug', 'modelno', 'category', 'brand', 'is_active'],
        'categories' => ['slug'],
        'subcategories' => ['slug', 'catid'],
        'brands' => ['slug'],
        'cartorders' => ['orderid', 'slug', 'modelno'],
        'collection_items' => ['collection_id'],
        'collections' => ['user_id'],
        'storeusers' => ['email'],
    ];

    public function up()
    {
        foreach ($this->indexes as $table => $columns) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            foreach ($columns as $column) {
                if (!Schema::hasColumn($table, $column) || $this->hasIndex($table, $column)) {
                    continue;
                }
                $type = DB::selectOne("SELECT DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?", [$table, $column]);
                // Long text columns can only be indexed on a prefix
                $length = in_array($type->DATA_TYPE, ['text', 'mediumtext', 'longtext'], true) || $type->CHARACTER_MAXIMUM_LENGTH > 191 ? '(191)' : '';
                DB::statement("CREATE INDEX `idx_{$table}_{$column}` ON `{$table}` (`{$column}`{$length})");
            }
        }
    }

    public function down()
    {
        foreach ($this->indexes as $table => $columns) {
            foreach ($columns as $column) {
                if (Schema::hasTable($table) && collect(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", ["idx_{$table}_{$column}"]))->isNotEmpty()) {
                    DB::statement("DROP INDEX `idx_{$table}_{$column}` ON `{$table}`");
                }
            }
        }
    }

    private function hasIndex($table, $column)
    {
        return collect(DB::select("SHOW INDEX FROM `{$table}` WHERE Column_name = ? AND Seq_in_index = 1", [$column]))->isNotEmpty();
    }
}
