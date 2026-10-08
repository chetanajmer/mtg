<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [

        'storeuser',
        'addtocart',
        'getcatproducts',
        'getsubcatproducts',
        'addsubscriber',
        'getproductdetails',
        'getbrandproducts',
        'searchproduct',
        'search_or_update',
        'paytab_returnurl',
        'return_url',
        'callback',
        'registeruser',
        'registersocialuser',
        'loginuser',
        'loginsocialuser',
        'getuserdetails',
        'getuserorderdetails',
        'updateuserdetails',
        'stocknotify',
        'b2benquiry',
        'addtocompare',
        'getcomparelist',
        'abandancart',
        'enquiry',
        'contact',
        'headerfilter',
        'gethomedata'
        //
    ];
}
