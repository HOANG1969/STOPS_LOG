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

    'zalo_oa' => [
        'enabled' => env('ZALO_OA_ENABLED', false),
        'channel' => env('ZALO_CHANNEL', 'oa'),
        'access_token' => env('ZALO_OA_ACCESS_TOKEN'),
        'message_api_url' => env('ZALO_OA_MESSAGE_API_URL', 'https://openapi.zalo.me/v3.0/oa/message/cs'),
        'zns_api_url' => env('ZALO_ZNS_API_URL', 'https://business.openapi.zalo.me/message/template'),
        'zns_template_id' => env('ZALO_ZNS_TEMPLATE_ID'),
        'webhook_url' => env('ZALO_OA_WEBHOOK_URL'),
        'recipient_ids' => array_values(array_filter(array_map('trim', explode(',', (string) env('ZALO_OA_RECIPIENT_IDS', ''))))),
        'safety_roles' => array_values(array_filter(array_map('trim', explode(',', (string) env('STOP_SAFETY_ROLES', 'admin,tchc_checker,tchc_manager'))))),
    ],

];
