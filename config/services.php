<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    
    'google' => [

        'client_id' => env('Google_client_id'),

        'client_secret' => env('Google_client_secret'),

        'redirect' => env('Google_redirect'),

    ],

    'facebook' =>[

       'client_id'  => env('Facebook_client_id'),

       'client_secret'=> env('Facebook_client_secret'),

       'redirect' => env('Facebook_redirect'),

    ],

    'paytabs' => [
        'profile_id' => env('PAYTABS_PROFILE_ID'),
        'server_key' => env('PAYTABS_SERVER_KEY'),
        'base_url' => env('PAYTABS_BASE_URL', 'https://secure.paytabs.com'),
        // Where shoppers are sent after paying (the old Angular storefront used http://localhost:4200)
        'frontend_url' => env('PAYTABS_FRONTEND_URL', env('APP_URL')),
    ],

    'midocean' => [
        'api_key' => env('MIDOCEAN_API_KEY'),
    ],


];
