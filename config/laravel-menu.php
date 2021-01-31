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

            'dropdown_defaults' => [
                'item_attributes' => [
                    'class' => 'dropdown'
                ],
                'link_type' => 'button', // or button or $menu->dropdown(...)->type('a'); or ->type('href') or ->type('link') or ->type('button')
                'link_attributes' => [
                    'data-toggle'   => 'dropdown',
                    'aria-haspopup' => 'true',
                    'aria-expanded' => 'false'
                ],
                'child_wrapper_attributes' => [
                    'aria-labelledby' => 'dropdownMenuButton'
                ],
                'child_link_wrapper' => false // a simple example: >> if false: <ul><a>..</a></ul> else if true: <ul><li><a>..</a></ul>
            ]
        ],
    ],

    'views' => [
        'bootstrap-items' => 'laravel-menu::bootstrap-navbar-items',
    ]
];
