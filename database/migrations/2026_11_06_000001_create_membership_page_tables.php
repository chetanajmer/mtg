<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Content of the Annual Corporate Membership page that admins edit under
 * Admin > Annual Membership: the hero stats, the plans section headings and
 * the membership plans. Filled with what the page showed before, so the page
 * looks the same until an admin changes something.
 */
class CreateMembershipPageTables extends Migration
{
    public function up()
    {
        Schema::create('membership_settings', function (Blueprint $table) {
            $table->id();
            $table->string('plans_eyebrow', 100)->nullable();
            $table->string('plans_title', 191)->nullable();
            $table->string('plans_subtitle', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('membership_stats', function (Blueprint $table) {
            $table->id();
            $table->string('value', 50);
            $table->string('label', 100);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('tagline', 150)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('medal', 20)->default('silver'); // silver | gold | platinum
            $table->string('price', 50);                    // shown as typed, e.g. ₹5,00,000
            $table->string('price_period', 30)->default('year');
            $table->boolean('is_popular')->default(false);
            $table->string('badge_text', 50)->nullable();
            $table->text('features')->nullable();           // JSON: [{"label":"…","value":"…"}]
            $table->string('button_text', 50)->default('Select Plan');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();

        DB::table('membership_settings')->insert([
            'plans_eyebrow' => 'MEMBERSHIP PLANS',
            'plans_title' => 'Choose the Right Plan for Your Business',
            'plans_subtitle' => 'More gifting. More savings. More possibilities.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $stats = [['500+', 'Corporate Clients'], ['50,000+', 'Gifts Delivered'], ['4.9/5', 'Client Satisfaction']];
        foreach ($stats as $i => $stat) {
            DB::table('membership_stats')->insert([
                'value' => $stat[0], 'label' => $stat[1], 'sort_order' => $i + 1, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        $features = function ($spend, $savings, $custom, $dispatch, $manager, $rollover) {
            return json_encode([
                ['label' => 'Annual Spend', 'value' => $spend],
                ['label' => 'Estimated Savings', 'value' => $savings],
                ['label' => 'Free Customization', 'value' => $custom],
                ['label' => 'Priority Dispatch', 'value' => $dispatch],
                ['label' => 'Dedicated Account Manager', 'value' => $manager],
                ['label' => 'Unused Budget Rollover', 'value' => $rollover],
            ], JSON_UNESCAPED_UNICODE);
        };

        $plans = [
            ['Silver', 'For Growing Teams', 'Essential benefits to get you started.', 'silver', '₹2,00,000', false, null,
                $features('₹2,00,000 / year', '₹20,000 (10%)', 'Up to 5 products', 'Standard', '–', '–')],
            ['Gold', 'For Established Businesses', 'Maximum value with premium benefits.', 'gold', '₹5,00,000', true, 'MOST POPULAR',
                $features('₹5,00,000 / year', '₹75,000 (15%)', 'Up to 10 products', 'Priority (3–5 days)', '✓', 'Up to 25%')],
            ['Platinum', 'For Large Enterprises', 'The complete corporate gifting solution.', 'platinum', '₹10,00,000', false, null,
                $features('₹10,00,000 / year', '₹2,00,000 (20%)', 'Unlimited', 'Express (1–3 days)', '✓', 'Up to 50%')],
        ];
        foreach ($plans as $i => $p) {
            DB::table('membership_plans')->insert([
                'name' => $p[0], 'tagline' => $p[1], 'description' => $p[2], 'medal' => $p[3], 'price' => $p[4],
                'price_period' => 'year', 'is_popular' => $p[5], 'badge_text' => $p[6], 'features' => $p[7],
                'button_text' => 'Select Plan', 'is_active' => true, 'sort_order' => $i + 1,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('membership_plans');
        Schema::dropIfExists('membership_stats');
        Schema::dropIfExists('membership_settings');
    }
}
