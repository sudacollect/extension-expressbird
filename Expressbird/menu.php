<?php
/*
|--------------------------------------------------------------------------
| 菜单扩展
|--------------------------------------------------------------------------
|
| 目前支持扩展菜单项
| 1. 支持对当前已存在菜单的扩展
| 2. 支持扩展新的菜单项
|
*/

return [
    
    'meituan_menu'=>[
        'single'    => true,
        'title'     => '美团',
        'slug'      => 'meituan_menu',
        'url'       => 'extension/expressbird/meituan/orders',
        'icon_class'=> 'fa fa-map',
        'icon_bg_color'=>'#08f',
        'font_color'=>'#fff',
        'target'     => '_self',
        'order'     => 0,
        
        'children' => [
            [
                'title'     => '美团订单',
                'slug'      => 'mt_orders',
                'url'       => 'extension/expressbird/meituan/orders',
                'icon_class'=> 'zly-list-ul',
                'target'     => '_self',
                'order'     => 0,
            ],
            [
                'title'     => '对接配置',
                'slug'      => 'setting',
                'url'       => 'extension/expressbird/meituan/setting',
                'icon_class'=> 'zly-list-ul',
                'target'     => '_self',
                'order'     => 0,
            ],
            [
                'title'     => '接口',
                'slug'      => 'url',
                'url'       => 'extension/expressbird/meituan/setting-url',
                'icon_class'=> 'zly-list-ul',
                'target'     => '_self',
                'order'     => 0,
            ],
            [
                'title'     => '门店',
                'slug'      => 'shop',
                'url'       => 'extension/expressbird/meituan/shop-list',
                'icon_class'=> 'zly-list-ul',
                'target'     => '_self',
                'order'     => 0,
            ],
            [
                'title'     => '模拟测试',
                'slug'      => 'test',
                'url'       => 'extension/expressbird/meituan/test-order-arrange',
                'icon_class'=> 'zly-list-ul',
                'target'     => '_self',
                'order'     => 0,
            ],
            [
                'title'     => '帮助',
                'slug'      => 'help',
                'url'       => 'extension/expressbird/meituan/help',
                'icon_class'=> 'zly-list-ul',
                'target'     => '_self',
                'order'     => 0,
            ],

        ],
    ],

    // 'sf_menu'=>[
    //     'single'    => true,
    //     'title'     => '顺丰',
    //     'slug'      => 'sf_menu',
    //     'url'       => 'extension/expressbird/index',
    //     'icon_class'=> 'fa fa-map',
    //     'icon_bg_color'=>'#08f',
    //     'font_color'=>'#fff',
    //     'target'     => '_self',
    //     'order'     => 0,
        
    //     'children' => [
    //         [
    //             'title'     => '顺丰订单',
    //             'slug'      => 'mt_orders',
    //             'url'       => 'extension/expressbird/sfexpress/orders',
    //             'icon_class'=> 'zly-list-ul',
    //             'target'     => '_self',
    //             'order'     => 0,
    //         ],
    //         'app_menu'=>[
    //             'single'    => true,
    //             'title'     => '对接配置',
    //             'slug'      => 'app_menu',
    //             'url'       => 'extension/expressbird/sfexpress/setting',
    //             'icon_class'=> 'zly-gear-s-o',
    //             'target'     => '_self',
    //             'order'     => 1,
                
    //             'children' => [
                    
                    
    //             ],
    //         ],
        
    //         'url_menu'=>[
    //             'single'    => true,
    //             'title'     => '接口',
    //             'slug'      => 'url_menu',
    //             'url'       => 'extension/expressbird/sfexpress/setting-url',
    //             'icon_class'=> 'zly-gear-s-o',
    //             'target'     => '_self',
    //             'order'     => 2,
                
    //             'children' => [
                    
    //             ],
    //         ],
        
    //         'data_menu'=>[
    //             'single'    => true,
    //             'title'     => '关联',
    //             'slug'      => 'data_menu',
    //             'url'       => 'extension/expressbird/setting-key',
    //             'icon_class'=> 'zly-gear-s-o',
    //             'target'     => '_self',
    //             'order'     => 3,
                
    //             'children' => [
                    
                    
    //             ],
    //         ],

    //         'help_menu'=>[
    //             'single'    => true,
    //             'title'     => '关联',
    //             'slug'      => 'data_menu',
    //             'url'       => 'extension/expressbird/sfexpress/help',
    //             'icon_class'=> 'zly-gear-s-o',
    //             'target'     => '_self',
    //             'order'     => 3,
                
    //             'children' => [
                    
                    
    //             ],
    //         ],
    //     ],
    // ],

    



    'help_menu'=>[
        'single'    => true,
        'title'     => '帮助',
        'slug'      => 'help_menu',
        'url'       => 'extension/expressbird/memo',
        'icon_class'=> 'fa fa-map',
        'icon_bg_color'=>'#08f',
        'font_color'=>'#fff',
        'target'     => '_self',
        'order'     => 4,
        
        'children' => [
            
        ],
    ],
    
];