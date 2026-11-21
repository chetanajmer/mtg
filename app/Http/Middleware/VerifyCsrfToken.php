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
        // PayTabs posts these from its own pages and servers (the callback is checked by its signature)
        'paytab_returnurl',
        'callback',
    ];
}
