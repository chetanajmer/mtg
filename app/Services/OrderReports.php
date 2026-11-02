<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Figures for the customer and admin Reports pages. One entry per order
 * (orders table), never per product line. "Confirmed" = step 1 done,
 * "delivered" = step 8 done, "completed" = step 9 done.
 */
class OrderReports
{
    /**
     * Orders matching the filters, newest first.
     * Filters: from / to (Y-m-d, both inclusive, on the order date), storeuser_id,
     * customer (email), product (product id), step (1-10 | waiting | completed).
     */
    public function orders(array $filters): Collection
    {
        return Order::with(['items', 'stepStates'])
            ->when($filters['from'] ?? null, function ($q, $from) { $q->whereDate('created_at', '>=', $from); })
            ->when($filters['to'] ?? null, function ($q, $to) { $q->whereDate('created_at', '<=', $to); })
            ->when($filters['storeuser_id'] ?? null, function ($q, $id) { $q->where('storeuser_id', $id); })
            ->when($filters['customer'] ?? null, function ($q, $email) { $q->where('customer_email', $email); })
            ->when($filters['product'] ?? null, function ($q, $product) {
                $q->whereHas('items', function ($items) use ($product) { $items->where('productid', $product); });
            })
            ->orderByDesc('created_at')->orderByDesc('id')
            ->get()
            ->filter(function ($order) use ($filters) {
                $step = $filters['step'] ?? null;
                if (!$step) {
                    return true;
                }
                if ($step === 'completed') {
                    return self::isDone($order, 9);
                }
                if ($step === 'waiting') {
                    return $order->step_state === 'waiting_customer';
                }
                return (int) $order->current_step === (int) $step && !self::isDone($order, 9);
            })
            ->values();
    }

    public static function isDone($order, $step)
    {
        return optional($order->state($step))->state === 'done';
    }

    /** Unit price × quantity of one product line (cartorders.price is stored as text). */
    public static function lineTotal($item)
    {
        return round((float) $item->price * (int) $item->quantity, 2);
    }

    public function summary(Collection $orders): array
    {
        $confirmed = $orders->filter(function ($o) { return self::isDone($o, 1); });
        $value = (float) $orders->sum('total');
        return [
            'orders' => $orders->count(),
            'value' => $value,
            'confirmedOrders' => $confirmed->count(),
            'confirmedValue' => (float) $confirmed->sum('total'),
            'average' => $orders->count() ? $value / $orders->count() : 0,
            'pieces' => (int) $orders->sum(function ($o) { return $o->items->sum('quantity'); }),
            'deliveredPieces' => (int) $orders->filter(function ($o) { return self::isDone($o, 8); })
                ->sum(function ($o) { return $o->items->sum('quantity'); }),
            'completed' => $orders->filter(function ($o) { return self::isDone($o, 9); })->count(),
            'waiting' => $orders->where('step_state', 'waiting_customer')->count(),
        ];
    }

    /** [Y-m => [label, orders, value, confirmedValue]] oldest month first. */
    public function byMonth(Collection $orders): Collection
    {
        return $orders->groupBy(function ($o) { return $o->created_at->format('Y-m'); })
            ->map(function ($group, $month) {
                return [
                    'month' => $month,
                    'label' => \Carbon\Carbon::createFromFormat('Y-m-d', $month . '-01')->format('M Y'),
                    'orders' => $group->count(),
                    'value' => (float) $group->sum('total'),
                    'confirmedValue' => (float) $group->filter(function ($o) { return self::isDone($o, 1); })->sum('total'),
                ];
            })
            ->sortKeys()->values();
    }

    /** Products by pieces ordered: name, model no, pieces, orders, value (excl. VAT). */
    public function byProduct(Collection $orders, $limit = null): Collection
    {
        $rows = $orders->flatMap(function ($o) {
            return $o->items->map(function ($item) use ($o) { return [$o->id, $item]; });
        })->groupBy(function ($pair) { return $pair[1]->productid ?: 'name:' . $pair[1]->name; })
        ->map(function ($pairs) {
            $first = $pairs->first()[1];
            return [
                'product' => $first->productid,
                'name' => $first->name ?: 'Product',
                'modelno' => $first->modelno,
                'pieces' => (int) $pairs->sum(function ($p) { return $p[1]->quantity; }),
                'orders' => $pairs->pluck(0)->unique()->count(),
                'value' => (float) $pairs->sum(function ($p) { return self::lineTotal($p[1]); }),
            ];
        })->sortByDesc('pieces')->values();
        return $limit ? $rows->take($limit) : $rows;
    }

    /** Customers by order value: name, company, email, orders, value, confirmed value. */
    public function byCustomer(Collection $orders, $limit = null): Collection
    {
        $rows = $orders->groupBy(function ($o) { return strtolower((string) $o->customer_email) ?: 'order:' . $o->id; })
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'email' => $first->customer_email,
                    'name' => $first->customer_name,
                    'company' => $first->customer_company,
                    'orders' => $group->count(),
                    'value' => (float) $group->sum('total'),
                    'confirmedValue' => (float) $group->filter(function ($o) { return self::isDone($o, 1); })->sum('total'),
                ];
            })->sortByDesc('value')->values();
        return $limit ? $rows->take($limit) : $rows;
    }

    /** Steps an open order can be at: 1-9 (at step 10, the rating, the project is already completed). */
    public static function openSteps(): array
    {
        return array_filter(config('order_steps'), function ($n) { return $n <= 9; }, ARRAY_FILTER_USE_KEY);
    }

    /** Open orders per current step (1-9) plus completed, in step order. */
    public function byStep(Collection $orders): array
    {
        $counts = [];
        foreach (self::openSteps() as $n => $step) {
            $counts[$n] = ['step' => $n, 'title' => $step['title'], 'orders' => 0, 'waiting' => 0];
        }
        $completed = 0;
        foreach ($orders as $o) {
            if (self::isDone($o, 9)) {
                $completed++;
                continue;
            }
            $n = (int) $o->current_step;
            if (isset($counts[$n])) {
                $counts[$n]['orders']++;
                if ($o->step_state === 'waiting_customer') {
                    $counts[$n]['waiting']++;
                }
            }
        }
        return ['steps' => array_values($counts), 'completed' => $completed];
    }

    /** Where an order stands, in the customer's words or the staff step title. */
    public static function status($order, $customer = false)
    {
        if (self::isDone($order, 9)) {
            return 'Completed';
        }
        $step = config('order_steps.' . (int) $order->current_step);
        $labels = $customer ? OrderWorkflow::CUSTOMER_LABELS : \App\Models\OrderStepState::LABELS;
        return 'Step ' . $order->current_step . ' · ' . ($step[$customer ? 'customer' : 'title'] ?? '') . ' · ' . ($labels[$order->step_state] ?? $order->step_state);
    }

    /** CSV download that opens correctly in Excel (UTF-8 BOM) and never runs cell formulas. */
    public function csv($filename, array $header, $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($header, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $header);
            foreach ($rows as $row) {
                fputcsv($out, array_map(function ($cell) {
                    $cell = (string) $cell;
                    return preg_match('/^[=+\-@\t\r]/', $cell) && !is_numeric($cell) ? "'" . $cell : $cell;
                }, array_values($row)));
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
