<?php

return [

    'config' => [
        'icon_family' => null,
        'svg_path' => null,
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
            'inactive_class' => null,
            'active_class' => 'active',
            'restful' => false,
            'cascade_data' => true,
            'rest_base' => '',
            'active_element' => 'item',
            'data_toggle_attribute' => 'data-toggle',

            'active_icon_class' => null,
            'inactive_icon_class' => null,

            'active_svg_class' => null,
            'inactive_svg_class' => null,

            'dropdown_defaults' => [
                'item_attributes' => [
                    'class' => 'dropdown dropdown-item'
                ],
                'link_type' => 'button',
                'link_attributes' => [
                    'data-toggle'   => 'dropdown',
                    'aria-haspopup' => 'true',
                    'aria-expanded' => 'false'
                ],
                'child_wrapper_attributes' => [
                    'aria-labelledby' => 'dropdownMenuButton'
                ],
                'child_link_wrapper' => false
            ]
        ],
    ]
];
