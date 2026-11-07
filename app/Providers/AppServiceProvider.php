<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         Schema::defaultStringLength(191);

         // Logged-out customers log in first, then land on their orders
         View::composer(['web_frontend.partials.home-header', 'web_frontend.partials.home-footer', 'web_frontend.home'], function ($view) {
             $view->with('ordersUrl', session()->has('userid') ? url('orders') : route('corporate.login', ['redirect' => '/orders']));
         });

         View::composer('web_frontend.partials.home-header', function ($view) {
             $view->with('supplyCountries', rescue(function () {
                 return \App\Models\SupplyCountry::where('is_active', true)->ordered()->get();
             }, collect(), false));
             $view->with('navCategories', rescue(function () {
                 return \App\Models\Category::where('is_active', 'online')->orderBy('ranking')->orderBy('id')->get(['catname', 'slug']);
             }, collect(), false));
         });

         View::composer(['web_frontend.partials.header', 'web_frontend.partials.home-header'], function ($view) {
             $count = rescue(function () {
                 return session()->has('userid')
                     ? \App\Models\CartItem::where('user_id', session()->get('userid'))->count()
                     : \App\Models\CartItem::where('session_id', session()->getId())->count();
             }, 0, false);

             $view->with('cartItemCount', $count ?: 0);
         });

         // @adminCan('listproduct') ... @endadminCan: show a menu item only to
         // admins who may open that page (same rules as AdminPermission).
         \Illuminate\Support\Facades\Blade::if('adminCan', function ($path) {
             $flag = \App\Http\Middleware\AdminPermission::flagFor($path);
             return !$flag || \App\Http\Middleware\AdminPermission::allowed(session()->get('admin_id'), $flag);
         });
    }
}
