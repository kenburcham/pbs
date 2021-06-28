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

    // 'exchanges' => [
    //     'fawazahmed' =>
    //         [
    //             'api' => 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest',
    //             'currencies' => 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies.json',
    //         ],
    //     'vatcomply' =>
    //         [
    //             'api' => 'https://api.vatcomply.com/rates',
    //             'currencies' => ''
    //         ],
    //     ],

];

// APP_CURRENCYPROVIDERS=[
    
//     "https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest",
//     "https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@{apiVersion}/{date}/{endpoint}",
//     "https://api.vatcomply.com/rates?date={date}&base={currency}&

//https://api.vatcomply.com/rates?base=USD&date=2021-06-01
//https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/2020-11-24/currencies/eur.json


