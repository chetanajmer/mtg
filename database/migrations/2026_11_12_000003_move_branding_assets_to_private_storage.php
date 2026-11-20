<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MoveBrandingAssetsToPrivateStorage extends Migration
{
    /**
     * Customers' branding assets were in the public folder (storage/app/public/branding_assets), where anyone with
     * the link could open them. They move to private storage (storage/app/branding_assets) and are shown only to
     * their owner (BrandingAssetController::file / download). Files only; the stored paths stay the same.
     */
    public function up()
    {
        foreach (DB::table('branding_assets')->pluck('file_path') as $path) {
            $from = storage_path('app/public/' . $path);
            $to = storage_path('app/' . $path);
            if ($path && is_file($from) && !is_file($to)) {
                if (!is_dir(dirname($to))) {
                    mkdir(dirname($to), 0755, true);
                }
                rename($from, $to);
            }
        }
    }

    public function down()
    {
        foreach (DB::table('branding_assets')->pluck('file_path') as $path) {
            $from = storage_path('app/' . $path);
            $to = storage_path('app/public/' . $path);
            if ($path && is_file($from) && !is_file($to)) {
                if (!is_dir(dirname($to))) {
                    mkdir(dirname($to), 0755, true);
                }
                rename($from, $to);
            }
        }
    }
}
