<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FilterSetting;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilterSettingController extends Controller
{
    public function index()
    {
        $filters = FilterSetting::ordered()->get();
        $categories = Category::where('is_active', 'online')->orderBy('ranking')->get(['id', 'catname']);
        $tags = DB::table('preferences')->orderBy('name')->pluck('name');

        return view('backend.settings.filter-settings', compact('filters', 'categories', 'tags'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'filters' => 'required|array',
            'filters.*.label' => 'required|string|max:60',
            'filters.*.ranking' => 'required|integer|min:0|max:9999',
            'filters.*.hidden_categories' => 'nullable|array',
            'filters.*.hidden_categories.*' => 'integer',
            'bands' => 'nullable|array|max:12',
            'bands.*.label' => 'nullable|string|max:40',
            'bands.*.from' => 'nullable|numeric|min:0|max:9999999',
            'bands.*.to' => 'nullable|numeric|min:0|max:9999999',
            'sustainability_tags' => 'nullable|array',
            'sustainability_tags.*' => 'string|max:250',
        ], [
            'filters.*.label.required' => 'Every filter needs a name.',
            'filters.*.ranking.required' => 'Every filter needs a position.',
        ]);

        $bands = $this->bands($request->input('bands', []));
        if (is_string($bands)) {
            return back()->withInput()->withErrors(['bands' => $bands]);
        }

        $tags = DB::table('preferences')->pluck('name')->all();
        $categoryIds = Category::pluck('id')->map(function ($id) { return (int) $id; })->all();

        foreach (FilterSetting::all() as $filter) {
            $input = $request->input('filters.' . $filter->filter_key);
            if (!is_array($input)) {
                continue;
            }
            $hidden = array_map('intval', $input['hidden_categories'] ?? []);
            $data = [
                'label' => trim($input['label']),
                'ranking' => (int) $input['ranking'],
                'is_active' => !empty($input['is_active']),
                'hidden_categories' => array_values(array_intersect($hidden, $categoryIds)),
            ];
            if ($filter->filter_key === 'price') {
                $data['options'] = $bands;
            } elseif ($filter->filter_key === 'sustainability') {
                $data['options'] = array_values(array_intersect($request->input('sustainability_tags', []), $tags));
            }
            $filter->update($data);
        }

        return back()->with('success', 'Filters saved.');
    }

    // Checks the price bands and returns them lowest first, or a message saying what's wrong
    private function bands($rows)
    {
        $bands = [];
        foreach ($rows as $row) {
            $label = trim((string) ($row['label'] ?? ''));
            $from = $row['from'] ?? null;
            $to = $row['to'] ?? null;
            if ($label === '' && ($from === null || $from === '') && ($to === null || $to === '')) {
                continue; // an empty row
            }
            if ($label === '' || $from === null || $from === '') {
                return 'Every price band needs a name and a From price.';
            }
            $to = $to === '' ? null : $to;
            if ($to !== null && (float) $to <= (float) $from) {
                return 'In "' . $label . '", the To price must be above the From price.';
            }
            $bands[] = ['label' => $label, 'from' => (float) $from, 'to' => $to === null ? null : (float) $to];
        }
        if (!$bands) {
            return 'Please keep at least one price band.';
        }

        usort($bands, function ($a, $b) { return $a['from'] <=> $b['from']; });
        foreach ($bands as $i => $band) {
            $next = $bands[$i + 1] ?? null;
            if ($next && ($band['to'] === null || $next['from'] < $band['to'])) {
                return 'The price bands "' . $band['label'] . '" and "' . $next['label'] . '" overlap.';
            }
        }

        return $bands;
    }
}
