<?php

use Illuminate\Support\Str;
use Psy\CodeCleaner\LabelContextPass;

return [

    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => (int) env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => env('SESSION_TABLE', 'sessions'),
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel')).'-session'
    ),

    'path' => '/',
    'domain' => env('SESSION_DOMAIN', null),        // allow cookies for localhost
    'secure' => false,       // allow HTTP
    'http_only' => true,
    'same_site' => 'lax',     // allow cross-origin cookies

    'partitioned' => false,
];
