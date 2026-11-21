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
    // Website header and footer (client's new design, 19 Nov)
    'whatsapp' => env('COMPANY_WHATSAPP', '+971 52 786 110 9'),
    'landline' => env('COMPANY_LANDLINE', '+971 4 398 3465'),
    'enquiry_email' => env('COMPANY_ENQUIRY_EMAIL', 'enquiry@morethangiftz.com'),
    'tiktok' => env('COMPANY_TIKTOK_URL', ''),
    'website' => env('COMPANY_WEBSITE', 'www.morethangiftz.com'),
];
