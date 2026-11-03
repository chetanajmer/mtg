<?php

/*
|--------------------------------------------------------------------------
| Company details printed on invoices
|--------------------------------------------------------------------------
|
| Set these in .env. Empty values are simply left off the invoice. The
| address and VAT registration number (TRN) must come from the owner.
|
*/

return [
    'name' => env('COMPANY_NAME', 'More Than Giftz'),
    'address' => env('COMPANY_ADDRESS', ''),
    'trn' => env('COMPANY_TRN', ''),
    'email' => env('COMPANY_EMAIL', 'info@morethangiftz.com'),
    'phone' => env('COMPANY_PHONE', '+971 55 786 3450'),
    'website' => env('COMPANY_WEBSITE', 'www.morethangiftz.com'),
];
