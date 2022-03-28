<?php

$controller_prefix = "\\App\\Extensions\\Expressbird\\Controllers\\";





Route::group([
    'as'         => 'expressbird.',
    'prefix'     => 'expressbird',
    // 'middleware' => 'auth:api',
], function ($router) use ($controller_prefix) {


    //=========================美团配送 START=======================

    //配送状态更改
    Route::post('meituan/order/callback', $controller_prefix.'Api\\MeituanCallbackController@updateOrderStatus');
    //配送异常
    Route::post('meituan/order/unusual', $controller_prefix.'Api\\MeituanCallbackController@updateOrderError');
    //配送范围变更回调
    Route::post('meituan/update/shop_areas', $controller_prefix.'Api\\MeituanCallbackController@updateShopAreas');
    //配送风险等级变更回调
    Route::post('meituan/update/shop_risk', $controller_prefix.'Api\\MeituanCallbackController@updateShopRisk');
    //门店创建结果回调
    Route::post('meituan/update/shop_status', $controller_prefix.'Api\\MeituanCallbackController@updateShopStatus');
    //配送员上下班打卡回调URL
    Route::post('meituan/update/rider_status', $controller_prefix.'Api\\MeituanCallbackController@updateRiderStatus');

    //=========================美团配送 END=======================



    //=========================顺丰配送 START=======================

    //=========================顺丰配送 END=======================

});