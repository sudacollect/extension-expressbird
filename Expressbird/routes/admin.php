<?php

$ext_name = 'expressbird';
$ext_path = ucfirst('expressbird');

$controller_prefix = "\\App\\Extensions\\".$ext_path."\\Controllers\\Admin\\";



Route::group([
    'as'         => 'expressbird.',
    'middleware' => 'expressbird',
    'prefix'     => $ext_name,
], function ($router) use ($ext_path,$controller_prefix) {

    //访问首页
    Route::get('/', $controller_prefix.'HomeController@index');
    Route::get('/index', $controller_prefix.'HomeController@index');


    //====================美团订单================================

    Route::group([
        'as'         => 'meituan.',
        'prefix'     => 'meituan',
    ], function ($router) use ($ext_path,$controller_prefix) {

        Route::get('orders', $controller_prefix.'MeituanOrderController@index');
        Route::get('orderlogs/{order_id}', $controller_prefix.'MeituanOrderController@showLogs');
        Route::post('orderlog/resend/{order_id}', $controller_prefix.'MeituanOrderController@resendOrder');

        // 取消订单
        Route::post('order/cancel/{id}', $controller_prefix.'MeituanOrderController@cancelOrder');

        // 门店
        Route::get('shop-list', $controller_prefix.'MeituanShopController@index');
        Route::get('shop-detail/{id}', $controller_prefix.'MeituanShopController@showDetail');
        Route::get('shop-query/{shop_id?}', $controller_prefix.'MeituanShopController@shopQuery');
        Route::post('shop-query-filter', $controller_prefix.'MeituanShopController@shopQueryFilter');


        // 模拟测试
        Route::get('test-order-{action}', $controller_prefix.'MeituanTestController@testOrderView');

        Route::post('test-order-post', $controller_prefix.'MeituanTestController@testOrder');//模拟接单

        //帮助
        Route::get('help', $controller_prefix.'SettingController@showMeituanHelp');

    });

    

    //====================美团订单================================


    //设置应用基础参数(名称、密钥等)
    Route::get('/{express_code}/setting', $controller_prefix.'SettingController@showSetting');
    Route::post('/{express_code}/setting/save', $controller_prefix.'SettingController@settingSave');


    //设置各种链接参数
    Route::get('/{express_code}/setting-url', $controller_prefix.'SettingController@settingUrl');
    Route::post('/{express_code}/setting-url/save', $controller_prefix.'SettingController@settingUrlSave');
    //帮助
    Route::get('/{express_code}/help', $controller_prefix.'SettingController@showHelp');

    //使用说明
    Route::get('/memo', $controller_prefix.'SettingController@memo');
    

});

