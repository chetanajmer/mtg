<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PlannerImage;
use App\Models\PlannerRecommendation;
use App\Models\Product;
use App\Services\GiftPlannerAi;
use Illuminate\Http\Request;

/**
 * What the Budget Planner has saved so it isn't paid for twice: the AI's bundle choices
 * (reused for the same choices) and the bundle pictures (reused for the same occasion and products).
 * Removing one makes the next matching request create it fresh.
 */
class PlannerLibraryController extends Controller
{
    public function recommendations(Request $request, GiftPlannerAi $ai)
    {
        $occasion = $this->occasion($request);
        $saved = PlannerRecommendation::when($occasion, function ($q, $occasion) { $q->where('occasion', $occasion); })
            ->orderByDesc('updated_at')->paginate(20)->withQueryString();

        // Product names and the shared (no customer logo) picture for each saved bundle
        $ids = $saved->flatMap(function ($row) { return collect($row->plan)->pluck('ids')->flatten(); })->unique()->all();
        $products = Product::whereIn('id', $ids)->pluck('name', 'id');
        $keys = [];
        foreach ($saved as $row) {
            foreach ($row->plan as $i => $bundle) {
                $custom = $row->occasion === 'other' ? $row->occasion_label : null;
                $keys[$row->id][$i] = $ai->pictureKey($row->occasion, $custom, $bundle['ids'] ?? []);
            }
        }
        $pictures = PlannerImage::whereIn('image_key', collect($keys)->flatten()->all())->get()->keyBy('image_key');

        return view('backend.planner.recommendations', compact('saved', 'products', 'keys', 'pictures', 'occasion'));
    }

    public function destroyRecommendation($id)
    {
        PlannerRecommendation::whereKey($id)->delete();
        return back()->with('success', 'Saved recommendation removed. The next customer with these choices gets a new AI choice.');
    }

    public function pictures(Request $request)
    {
        $occasion = $this->occasion($request);
        $kind = in_array($request->query('kind'), ['shared', 'logo'], true) ? $request->query('kind') : '';
        $query = PlannerImage::query()
            ->when($occasion, function ($q, $occasion) { $q->where('occasion', $occasion); })
            ->when($kind, function ($q, $kind) { $kind === 'logo' ? $q->whereNotNull('storeuser_id') : $q->whereNull('storeuser_id'); });
        $totals = (clone $query)->selectRaw('COUNT(*) as pictures, COALESCE(SUM(times_used), 0) as uses')->first();
        $images = $query->leftJoin('storeusers', 'storeusers.id', '=', 'planner_images.storeuser_id')
            ->select('planner_images.*', 'storeusers.company', 'storeusers.email')
            ->orderByDesc('planner_images.id')->paginate(24)->withQueryString();

        return view('backend.planner.pictures', compact('images', 'totals', 'occasion', 'kind'));
    }

    public function destroyPicture($id)
    {
        $image = PlannerImage::find($id);
        if ($image) {
            // The file name comes from the record, never from the request
            if (is_file($image->path())) {
                @unlink($image->path());
            }
            $image->delete();
        }
        return back()->with('success', 'Picture removed. The next customer shown this bundle gets a new picture.');
    }

    private function occasion(Request $request)
    {
        return array_key_exists($request->query('occasion'), config('gift_planner.occasions')) ? $request->query('occasion') : '';
    }
}
