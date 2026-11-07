<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PlannerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Planner Log: every set of bundles the Budget Planner showed, newest first,
 * with who asked, their choices and the bundles they saw.
 */
class PlannerLogController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'from' => $this->date($request->query('from')),
            'to' => $this->date($request->query('to')),
            'q' => trim((string) $request->query('q', '')),
            'occasion' => array_key_exists($request->query('occasion'), config('gift_planner.occasions')) ? $request->query('occasion') : '',
            'source' => in_array($request->query('source'), ['ai', 'reused', 'standard'], true) ? $request->query('source') : '',
        ];

        $query = PlannerLog::query()
            ->leftJoin('storeusers', 'storeusers.id', '=', 'planner_logs.storeuser_id')
            ->when($filters['from'], function ($q, $from) { $q->where('planner_logs.created_at', '>=', $from . ' 00:00:00'); })
            ->when($filters['to'], function ($q, $to) { $q->where('planner_logs.created_at', '<=', $to . ' 23:59:59'); })
            ->when($filters['occasion'], function ($q, $occasion) { $q->where('planner_logs.occasion', $occasion); })
            ->when($filters['source'], function ($q, $source) {
                if ($source === 'reused') {
                    $q->where('planner_logs.reused', true);
                } else {
                    $q->where('planner_logs.ai_written', $source === 'ai');
                }
            })
            ->when($filters['q'] !== '', function ($q) use ($filters) {
                $like = '%' . addcslashes($filters['q'], '\\%_') . '%';
                $q->where(function ($w) use ($like) {
                    $w->where('storeusers.email', 'like', $like)->orWhere('storeusers.company', 'like', $like)
                        ->orWhere(DB::raw("CONCAT_WS(' ', storeusers.fname, storeusers.lname)"), 'like', $like)
                        ->orWhere('planner_logs.occasion_label', 'like', $like);
                });
            });

        $summary = (clone $query)->selectRaw('COUNT(*) as total, COUNT(DISTINCT planner_logs.storeuser_id) as customers, SUM(planner_logs.ai_written) as ai, SUM(planner_logs.reused) as reused')->first();
        $topOccasion = (clone $query)->select('planner_logs.occasion_label', DB::raw('COUNT(*) as n'))
            ->groupBy('planner_logs.occasion_label')->orderByDesc('n')->first();

        $logs = $query->select('planner_logs.*', 'storeusers.fname', 'storeusers.lname', 'storeusers.email', 'storeusers.company')
            ->orderByDesc('planner_logs.id')->paginate(25)->withQueryString();

        return view('backend.planner.logs', compact('filters', 'logs', 'summary', 'topOccasion'));
    }

    private function date($value)
    {
        return is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) ? $value : '';
    }
}
