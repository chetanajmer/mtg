<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\OrderReports;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Staff Reports: one page with filters, totals, breakdowns, the matching
 * orders (one row per order) and CSV export. Replaces the old Date, User
 * and Product reports, whose URLs now open this page.
 */
class ReportController extends Controller
{
    public function index(Request $request, OrderReports $reports)
    {
        $filters = $this->filters($request);
        $orders = $reports->orders($filters);

        $page = max(1, (int) $request->query('page', 1));
        $list = new LengthAwarePaginator($orders->forPage($page, 25)->values(), $orders->count(), 25, $page,
            ['path' => route('reports.index'), 'query' => $request->except('page')]);

        return view('backend.report.index', [
            'filters' => $filters,
            'list' => $list,
            'summary' => $reports->summary($orders),
            'months' => $reports->byMonth($orders),
            'customers' => $reports->byCustomer($orders, 10),
            'products' => $reports->byProduct($orders, 10),
            'steps' => $reports->byStep($orders),
            'customerOptions' => DB::table('orders')->whereNotNull('customer_email')->where('customer_email', '!=', '')
                ->select('customer_email', DB::raw('MAX(customer_name) as name'), DB::raw('MAX(customer_company) as company'))
                ->groupBy('customer_email')->orderBy('name')->get(),
            'productOptions' => DB::table('cartorders')->whereNotNull('order_id')->whereNotNull('productid')
                ->select('productid', DB::raw('MAX(name) as name'), DB::raw('MAX(modelno) as modelno'))
                ->groupBy('productid')->orderBy('name')->get(),
            'exportQuery' => $request->except('page'),
        ]);
    }

    public function export(Request $request, OrderReports $reports)
    {
        $filters = $this->filters($request);
        $orders = $reports->orders($filters);
        $name = 'mtg-report-' . ($filters['from'] ?? 'start') . '-to-' . ($filters['to'] ?? 'today');

        if ($request->query('rows') === 'lines') {
            $rows = [];
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    $rows[] = [$order->orderid, $order->created_at->format('Y-m-d'), $order->customer_name, $order->customer_company, $order->customer_email,
                        $item->name, $item->modelno, (int) $item->quantity, number_format((float) $item->price, 2, '.', ''),
                        number_format(OrderReports::lineTotal($item), 2, '.', ''), OrderReports::status($order)];
                }
            }
            return $reports->csv("$name-product-lines.csv", ['Order number', 'Order date', 'Customer', 'Company', 'Email', 'Product', 'Model no.',
                'Quantity', 'Unit price (AED)', 'Line total (AED, excl. VAT)', 'Status'], $rows);
        }

        $rows = $orders->map(function ($order) {
            return [$order->orderid, $order->created_at->format('Y-m-d'), $order->customer_name, $order->customer_company, $order->customer_email,
                $order->items->count(), (int) $order->items->sum('quantity'), number_format((float) $order->total, 2, '.', ''),
                OrderReports::isDone($order, 1) ? 'Yes' : 'No', OrderReports::status($order)];
        });
        return $reports->csv("$name-orders.csv", ['Order number', 'Order date', 'Customer', 'Company', 'Email', 'Products', 'Pieces',
            'Order value (AED, incl. VAT)', 'Confirmed', 'Status'], $rows);
    }

    // The old report URLs (menu links, bookmarks) open the new page at the matching section
    public function date_report()
    {
        return redirect()->to(route('reports.index') . '#by-month');
    }

    public function user_report()
    {
        return redirect()->to(route('reports.index') . '#by-customer');
    }

    public function product_report()
    {
        return redirect()->to(route('reports.index') . '#by-product');
    }

    /** Filters from the query string. Default period: this calendar year. */
    private function filters(Request $request)
    {
        $filters = [];
        foreach (['from', 'to'] as $key) {
            $value = (string) $request->query($key);
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) && strtotime($value)) {
                $filters[$key] = $value;
            }
        }
        if (!$request->has('from') && !$request->has('to')) {
            $filters = ['from' => now()->year . '-01-01', 'to' => now()->year . '-12-31'];
        }
        if (isset($filters['from'], $filters['to']) && $filters['from'] > $filters['to']) {
            [$filters['from'], $filters['to']] = [$filters['to'], $filters['from']];
        }
        if ($request->filled('customer')) {
            $filters['customer'] = (string) $request->query('customer');
        }
        if ($request->filled('product') && ctype_digit((string) $request->query('product'))) {
            $filters['product'] = (int) $request->query('product');
        }
        $step = (string) $request->query('step');
        if (in_array($step, ['waiting', 'completed'], true) || (ctype_digit($step) && $step >= 1 && $step <= 10)) {
            $filters['step'] = $step;
        }
        return $filters;
    }
}
