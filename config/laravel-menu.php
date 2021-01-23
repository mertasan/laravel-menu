<?php

return [

    'config' => [
        'icon-family' => null, // eg: fa

        'svg-settings' => [
            // 'path' => 'svg', // project/resources/svg
            'path' => null, // disabled
            'default-attributes' => [
                'class' => 'svg',
            ]
        ]
    ],

    'menus' => [
        'default' => [
            'auto_activate' => true,
            'activate_parents' => true,
            'active_class' => 'active',
            'restful' => false,
            'cascade_data' => true,
            'rest_base' => '',      // string|array
            'active_element' => 'item',  // item|link
            'data-toggle-attribute' => 'data-toggle',
        ],
    ],

    'views' => [
        'bootstrap-items' => 'laravel-menu::bootstrap-navbar-items',
    ]
];
