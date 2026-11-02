<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OrderEvent;
use App\Services\OrderReports;
use Illuminate\Http\Request;

/**
 * The logged-in customer's own Reports page and CSV downloads.
 * Only orders linked to their account (orders.storeuser_id) are ever used.
 */
class CustomerReportController extends Controller
{
    private const PERIODS = ['this-year' => 'This year', 'last-year' => 'Last year', 'all' => 'All time', 'custom' => 'Custom dates'];

    public function index(Request $request, OrderReports $reports)
    {
        [$filters, $period] = $this->filters($request);
        $orders = $reports->orders($filters);

        return view('web_frontend.reports', [
            'period' => $period,
            'periods' => self::PERIODS,
            'filters' => $filters,
            'orders' => $orders,
            'summary' => $reports->summary($orders),
            'months' => $reports->byMonth($orders),
            'products' => $reports->byProduct($orders, 5),
            'query' => $request->only('period', 'from', 'to'),
        ]);
    }

    public function download(Request $request, OrderReports $reports, $type)
    {
        [$filters, $period] = $this->filters($request);
        $orders = $reports->orders($filters);
        $suffix = $period === 'custom' ? ($filters['from'] ?? 'start') . '-to-' . ($filters['to'] ?? 'today') : $period;

        if ($type === 'orders') {
            $rows = [];
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    $rows[] = [$order->orderid, $order->created_at->format('Y-m-d'), OrderReports::status($order, true), $item->name, $item->modelno,
                        (int) $item->quantity, number_format((float) $item->price, 2, '.', ''), number_format(OrderReports::lineTotal($item), 2, '.', ''),
                        number_format((float) $order->total, 2, '.', '')];
                }
            }
            return $reports->csv("order-history-$suffix.csv",
                ['Order number', 'Order date', 'Status', 'Product', 'Model no.', 'Quantity', 'Unit price (AED)', 'Line total (AED, excl. VAT)', 'Order value (AED, incl. VAT)'], $rows);
        }

        if ($type === 'spending') {
            $rows = $reports->byMonth($orders)->map(function ($m) {
                return [$m['label'], $m['orders'], number_format($m['value'], 2, '.', ''), number_format($m['confirmedValue'], 2, '.', '')];
            });
            $s = $reports->summary($orders);
            $rows->push(['Total', $s['orders'], number_format($s['value'], 2, '.', ''), number_format($s['confirmedValue'], 2, '.', '')]);
            return $reports->csv("spending-summary-$suffix.csv", ['Month', 'Orders', 'Order value (AED, incl. VAT)', 'Confirmed value (AED, incl. VAT)'], $rows);
        }

        if ($type === 'approvals') {
            $numbers = $orders->pluck('orderid', 'id');
            $events = OrderEvent::whereIn('order_id', $numbers->keys())->where('type', 'decision')->where('visibility', 'customer')
                ->orderBy('created_at')->get();
            $rows = $events->map(function ($e) use ($numbers) {
                return [$numbers[$e->order_id], $e->created_at->format('Y-m-d H:i'), config('order_steps.' . $e->step . '.customer'), $e->message];
            });
            return $reports->csv("approval-history-$suffix.csv", ['Order number', 'Date', 'Step', 'Decision'], $rows);
        }

        abort(404);
    }

    /** Period from the query string; the customer's own account is always added. */
    private function filters(Request $request)
    {
        $period = array_key_exists($request->query('period'), self::PERIODS) ? $request->query('period') : 'this-year';
        $filters = ['storeuser_id' => session()->get('userid')];
        if (empty($filters['storeuser_id'])) {
            abort(403); // without an account id the filter would be skipped and show everyone's orders
        }
        $year = now()->year;

        if ($period === 'this-year') {
            $filters += ['from' => "$year-01-01", 'to' => "$year-12-31"];
        } elseif ($period === 'last-year') {
            $filters += ['from' => ($year - 1) . '-01-01', 'to' => ($year - 1) . '-12-31'];
        } elseif ($period === 'custom') {
            foreach (['from', 'to'] as $key) {
                $value = (string) $request->query($key);
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) && strtotime($value)) {
                    $filters[$key] = $value;
                }
            }
            if (isset($filters['from'], $filters['to']) && $filters['from'] > $filters['to']) {
                [$filters['from'], $filters['to']] = [$filters['to'], $filters['from']];
            }
        }

        return [$filters, $period];
    }
}
