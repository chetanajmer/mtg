<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\MembershipSetting;
use App\Models\MembershipStat;

/**
 * /annual-corporate-membership. Stats and plans come from Admin > Annual Membership.
 */
class MembershipPageController extends Controller
{
    public function show()
    {
        $settings = MembershipSetting::current();
        $stats = MembershipStat::orderBy('sort_order')->orderBy('id')->get();
        $plans = MembershipPlan::where('is_active', true)->ordered()->get();

        return view('web_frontend.annual-corporate-membership', compact('settings', 'stats', 'plans'));
    }
}
