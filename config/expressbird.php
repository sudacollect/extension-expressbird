<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Express Drivers
    |--------------------------------------------------------------------------
    |
    | 定义接入的配送平台
    |
    */

    'default' => env('EXPRESSBIRD_DRIVER', 'meituan'),

    'drivers' => [
        'meituan' => [
            'driver' => App\Extensions\Expressbird\Services\MeituanService::class,
        ],
        'sfexpress' => [
            'driver' => App\Extensions\Sfexpress\Services\SfService::class,
        ],
        'dadaexpress' => [
            'driver' => App\Extensions\Dadaexpress\Services\DadaService::class,
        ],
    ],
];