<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrderWorkflowTables extends Migration
{
    /**
     * New order tracking structure:
     *  - orders: one row per order number (cartorders become its line items)
     *  - order_step_states: one row per order and step, one state each
     *  - order_events: append-only timeline (who did what, when)
     * Existing cartorders and order_workflow_steps data is converted; the old
     * tables are kept.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('orderid')->unique();
            $table->unsignedInteger('storeuser_id')->nullable()->index();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_company')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->unsignedTinyInteger('current_step')->default(1);
            $table->string('step_state', 30)->default('in_progress');
            $table->timestamp('step_started_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_step_states', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->index();
            $table->unsignedTinyInteger('step');
            $table->string('state', 30)->default('not_started');
            $table->json('data')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['order_id', 'step']);
        });

        Schema::create('order_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->index();
            $table->unsignedTinyInteger('step')->nullable();
            $table->string('type', 40);
            $table->string('visibility', 10)->default('internal');
            $table->string('actor_type', 10)->default('system');
            $table->unsignedInteger('actor_id')->nullable();
            $table->string('actor_name')->nullable();
            $table->text('message')->nullable();
            $table->json('data')->nullable();
            $table->json('files')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        if (!Schema::hasColumn('cartorders', 'order_id')) {
            Schema::table('cartorders', function (Blueprint $table) {
                $table->unsignedInteger('order_id')->nullable()->index()->after('id');
            });
        }

        $this->convertExistingOrders();
    }

    public function down()
    {
        Schema::table('cartorders', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
        Schema::dropIfExists('order_events');
        Schema::dropIfExists('order_step_states');
        Schema::dropIfExists('orders');
    }

    private function convertExistingOrders()
    {
        $now = now();

        // Old orders without an order number get one, so every order has one
        foreach (DB::table('cartorders')->whereNull('orderid')->orderBy('id')->pluck('id') as $rowId) {
            $next = ((int) DB::table('orderids')->max('orderid') ?: 10000) + 1;
            DB::table('orderids')->insert(['orderid' => $next]);
            DB::table('cartorders')->where('id', $rowId)->update(['orderid' => $next]);
        }

        // Old statuses -> furthest step done / state
        $legacy = [
            'confirmed' => [1, null], 'in transit' => [7, 'in_progress'], 'delivered' => [8, null],
            'enquiry received' => [1, null], 'mockup approval' => [2, null], 'advance payment' => [3, null],
            'sample approval' => [4, null], 'mass production' => [5, null], 'quality check & packing' => [6, null],
            'balance payment' => [7, null], 'completed' => [10, null],
        ];

        foreach (DB::table('cartorders')->orderBy('id')->get()->groupBy('orderid') as $orderid => $rows) {
            $first = $rows->first();
            $orderId = DB::table('orders')->insertGetId([
                'orderid' => $orderid,
                'storeuser_id' => $first->userid,
                'customer_name' => $first->customer_name,
                'customer_email' => $first->customer_email,
                'customer_phone' => $first->customer_phone,
                'customer_company' => $first->customer_company,
                'total' => (float) $first->ordertotal,
                'created_at' => $first->created_at ?: $now,
                'updated_at' => $now,
            ]);
            DB::table('cartorders')->where('orderid', $orderid)->update(['order_id' => $orderId]);

            DB::table('order_events')->insert([
                'order_id' => $orderId, 'step' => 1, 'type' => 'created', 'visibility' => 'customer',
                'actor_type' => 'system', 'actor_name' => 'System',
                'message' => 'Enquiry received with ' . $rows->count() . ' product(s).',
                'created_at' => $first->created_at ?: $now,
            ]);

            // Furthest completed step from the old status text and old step records
            $doneUpTo = 0;
            $inProgress = null;
            $status = strtolower(trim((string) $first->orderstatus));
            if (isset($legacy[$status])) {
                [$doneUpTo, $inProgress] = $legacy[$status];
                if ($inProgress) {
                    $inProgress = $doneUpTo + 1;
                }
            }
            $oldSteps = DB::table('order_workflow_steps')->whereIn('cartorder_id', $rows->pluck('id'))->orderBy('id')->get();
            foreach ($oldSteps as $old) {
                if (in_array(strtolower((string) $old->approval_status), ['completed', 'approved', 'delivered'], true)) {
                    $doneUpTo = max($doneUpTo, (int) $old->step_number);
                }
                $files = array_merge(
                    (array) json_decode($old->mockup_files ?: '[]', true),
                    (array) json_decode($old->delivery_proof ?: '[]', true),
                    (array) json_decode($old->dispatch_proof ?: '[]', true)
                );
                $details = array_filter([
                    'status' => $old->approval_status, 'courier' => $old->courier_partner, 'tracking_number' => $old->awb_number,
                    'delivered_to' => $old->delivered_to, 'rating' => $old->rating,
                ]);
                DB::table('order_events')->insert([
                    'order_id' => $orderId, 'step' => (int) $old->step_number, 'type' => 'imported', 'visibility' => 'internal',
                    'actor_type' => 'system', 'actor_name' => 'Imported',
                    'message' => trim('Imported from the old tracking page. ' . (string) $old->customer_message),
                    'data' => $details ? json_encode($details) : null, 'files' => $files ? json_encode(array_values($files)) : null,
                    'created_at' => $old->created_at ?: $now,
                ]);
            }
            if ($status !== '' && $status !== 'pending' && $doneUpTo > 0) {
                DB::table('order_events')->insert([
                    'order_id' => $orderId, 'step' => null, 'type' => 'imported', 'visibility' => 'internal',
                    'actor_type' => 'system', 'actor_name' => 'Imported',
                    'message' => 'Old order status was "' . $first->orderstatus . '".', 'created_at' => $now,
                ]);
            }

            $current = min($doneUpTo + 1, 10);
            for ($step = 1; $step <= 10; $step++) {
                $state = $step <= $doneUpTo ? 'done' : ($step === $current ? 'in_progress' : 'not_started');
                if ($doneUpTo >= 10) {
                    $state = 'done';
                }
                DB::table('order_step_states')->insert([
                    'order_id' => $orderId, 'step' => $step, 'state' => $state,
                    'started_at' => $state !== 'not_started' ? $now : null,
                    'completed_at' => $state === 'done' ? $now : null,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
            DB::table('orders')->where('id', $orderId)->update([
                'current_step' => $doneUpTo >= 10 ? 10 : $current,
                'step_state' => $doneUpTo >= 10 ? 'done' : 'in_progress',
                'step_started_at' => $now,
            ]);
        }
    }
}
