<?php

return [

    'config' => [
        'icon_family' => null, // eg: fa
        'svg_path' => null, // if null, use of svg icons is disabled
        'icon_attributes' => [
            'class' => null,
        ],
        'svg_attributes' => [
            'class' => 'svg',
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
            'data_toggle_attribute' => 'data-toggle',

            // Custom icon classes overwrite config.icon_attributes.class
            'active_icon_class' => null, // If the menu item is active
            'inactive_icon_class' => null, // If the menu item is not active

            // Custom svg classes overwrite config.svg_attributes.class
            'active_svg_class' => null, // If the menu item is active
            'inactive_svg_class' => null, // If the menu item is not active
        ],
    ],

    'views' => [
        'bootstrap-items' => 'laravel-menu::bootstrap-navbar-items',
    ]
];
