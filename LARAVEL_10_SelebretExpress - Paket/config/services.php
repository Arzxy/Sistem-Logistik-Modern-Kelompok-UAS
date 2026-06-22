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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

     // ── Alamat layanan microservice ───────────────────────────
    'pengguna'  => ['url' => env('SERVICE_PENGGUNA',  'http://127.0.0.1:8001')],
    'tarif'     => ['url' => env('SERVICE_TARIF',     'http://127.0.0.1:8003')],
    'armada'    => ['url' => env('SERVICE_ARMADA',    'http://127.0.0.1:8004')],
    'pelacakan' => ['url' => env('SERVICE_PELACAKAN', 'http://127.0.0.1:8005')],

];
