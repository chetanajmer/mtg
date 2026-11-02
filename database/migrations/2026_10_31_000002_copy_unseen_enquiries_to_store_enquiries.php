<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CopyUnseenEnquiriesToStoreEnquiries extends Migration
{
    /**
     * Cart enquiries used to be saved only in `enquiries`, which no admin
     * page shows. Give each one an order number and Store Enquiries rows so
     * staff can see and track them. Enquiries that already have an order
     * number are skipped, so this is safe to run more than once.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('enquiries') || !Schema::hasColumn('enquiries', 'orderid')) {
            return;
        }

        DB::table('enquiries')->whereNull('orderid')->orderBy('id')->get()->each(function ($enquiry) {
            DB::transaction(function () use ($enquiry) {
                $last = DB::table('orderids')->lockForUpdate()->max('orderid');
                $orderid = $last ? ((int) $last + 1) : 10001;
                DB::table('orderids')->insert(['orderid' => $orderid]);

                $user = DB::table('storeusers')->where('email', $enquiry->email)->first();
                foreach (json_decode($enquiry->cart_items, true) ?: [] as $item) {
                    $product = !empty($item['modelno']) ? DB::table('products')->where('modelno', $item['modelno'])->first() : null;
                    DB::table('cartorders')->insert([
                        'orderid' => $orderid,
                        'userid' => $user->id ?? null,
                        'productid' => $product->id ?? null,
                        'slug' => $product->slug ?? null,
                        'modelno' => $item['modelno'] ?? null,
                        'name' => $item['name'] ?? 'Product',
                        'thumbnail' => $product->thumbnail ?? ($item['image'] ?? null),
                        'price' => $item['price'] ?? null,
                        'quantity' => $item['quantity'] ?? null,
                        'subtotal' => isset($item['price'], $item['quantity']) ? round($item['price'] * $item['quantity'], 2) : null,
                        'ordertotal' => $enquiry->total,
                        'orderstatus' => 'Pending',
                        'customer_name' => $enquiry->name,
                        'customer_email' => $enquiry->email,
                        'customer_phone' => $enquiry->phone,
                        'customer_company' => $enquiry->company,
                        'additional_info' => $enquiry->message,
                        'created_at' => $enquiry->created_at,
                        'updated_at' => now(),
                    ]);
                }

                DB::table('enquiries')->where('id', $enquiry->id)->update(['orderid' => $orderid]);
            });
        });
    }

    /**
     * Data copy; nothing to undo automatically.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
