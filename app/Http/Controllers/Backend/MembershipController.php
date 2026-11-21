<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\MembershipSetting;
use App\Models\MembershipStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Admin > Annual Membership: the hero stats, the plans section headings and
 * the membership plans of /annual-corporate-membership.
 * Every change is a POST form (CSRF protected).
 */
class MembershipController extends Controller
{
    private const MAX_STATS = 6;
    private const MAX_FEATURES = 12;

    /** GET admin/annual-membership */
    public function index()
    {
        $settings = MembershipSetting::current();
        $stats = MembershipStat::orderBy('sort_order')->orderBy('id')->get();
        $plans = MembershipPlan::ordered()->get();

        return view('backend.membership.index', compact('settings', 'stats', 'plans'));
    }

    /** POST admin/annual-membership/content: section headings + hero stats */
    public function updateContent(Request $request)
    {
        $data = $request->validate([
            'plans_eyebrow' => 'nullable|string|max:100',
            'plans_title' => 'required|string|max:191',
            'plans_subtitle' => 'nullable|string|max:255',
            'stats' => 'nullable|array|max:' . self::MAX_STATS,
            'stats.*.value' => 'nullable|string|max:50|required_with:stats.*.label',
            'stats.*.label' => 'nullable|string|max:100|required_with:stats.*.value',
        ], [
            'stats.max' => 'You can show up to ' . self::MAX_STATS . ' stats.',
            'stats.*.value.required_with' => 'Each stat needs a value (e.g. 500+).',
            'stats.*.label.required_with' => 'Each stat needs a label (e.g. Corporate Clients).',
        ]);

        DB::transaction(function () use ($data) {
            MembershipSetting::current()->update([
                'plans_eyebrow' => $data['plans_eyebrow'] ?? null,
                'plans_title' => $data['plans_title'],
                'plans_subtitle' => $data['plans_subtitle'] ?? null,
            ]);

            // The form always sends the full list in display order
            MembershipStat::query()->delete();
            $order = 0;
            foreach ($data['stats'] ?? [] as $stat) {
                if (trim((string) ($stat['value'] ?? '')) === '' && trim((string) ($stat['label'] ?? '')) === '') {
                    continue; // empty row
                }
                MembershipStat::create([
                    'value' => trim($stat['value']),
                    'label' => trim($stat['label']),
                    'sort_order' => ++$order,
                ]);
            }
        });

        return redirect()->route('membership.index')->with('success', 'Page texts and stats saved.');
    }

    /** GET admin/annual-membership/plans/create */
    public function create()
    {
        $plan = new MembershipPlan([
            'medal' => 'silver', 'price_period' => 'year', 'button_text' => 'Select Plan', 'is_active' => true,
            'sort_order' => (int) MembershipPlan::max('sort_order') + 1,
            'features' => [['label' => 'Annual Spend', 'value' => '']],
        ]);

        return view('backend.membership.plan-form', compact('plan'));
    }

    /** POST admin/annual-membership/plans */
    public function store(Request $request)
    {
        $plan = MembershipPlan::create($this->validatedPlan($request));
        $this->keepOnePopular($plan);

        return redirect()->route('membership.index')->with('success', 'Plan "' . $plan->name . '" added.');
    }

    /** GET admin/annual-membership/plans/{id}/edit */
    public function edit($id)
    {
        $plan = MembershipPlan::findOrFail($id);

        return view('backend.membership.plan-form', compact('plan'));
    }

    /** POST admin/annual-membership/plans/{id} */
    public function update(Request $request, $id)
    {
        $plan = MembershipPlan::findOrFail($id);
        $plan->update($this->validatedPlan($request));
        $this->keepOnePopular($plan);

        return redirect()->route('membership.index')->with('success', 'Plan "' . $plan->name . '" saved.');
    }

    /** POST admin/annual-membership/plans/{id}/delete */
    public function destroy($id)
    {
        $plan = MembershipPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('membership.index')->with('success', 'Plan "' . $plan->name . '" deleted.');
    }

    private function validatedPlan(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'tagline' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:255',
            'medal' => 'required|in:' . implode(',', array_keys(MembershipPlan::MEDALS)),
            'price' => 'required|string|max:50',
            'price_period' => 'nullable|string|max:30',
            'badge_text' => 'nullable|string|max:50',
            'button_text' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0|max:999',
            'features' => 'nullable|array|max:' . self::MAX_FEATURES,
            'features.*.label' => 'nullable|string|max:100|required_with:features.*.value',
            'features.*.value' => 'nullable|string|max:100',
        ], [
            'features.max' => 'A plan can have up to ' . self::MAX_FEATURES . ' rows.',
            'features.*.label.required_with' => 'Each row with a value needs a name (e.g. Annual Spend).',
        ]);

        $data['features'] = collect($data['features'] ?? [])
            ->filter(function ($row) {
                return trim((string) ($row['label'] ?? '')) !== '';
            })
            ->map(function ($row) {
                $value = trim((string) ($row['value'] ?? ''));
                return ['label' => trim($row['label']), 'value' => $value === '' ? '–' : $value];
            })
            ->values()
            ->all();
        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_active'] = $request->boolean('is_active');
        $data['price_period'] = $data['price_period'] ?? '';
        $data['button_text'] = ($data['button_text'] ?? '') ?: 'Select Plan';
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }

    /** Only one plan is highlighted as "Most Popular" */
    private function keepOnePopular(MembershipPlan $plan)
    {
        if ($plan->is_popular) {
            MembershipPlan::where('id', '!=', $plan->id)->update(['is_popular' => false]);
        }
    }
}
