<?php

return array(

    'icon_family' => null, // eg: fa

    'svg_settings' => [
        // 'path' => 'svg', // project/resources/svg
        'path' => null, // disabled
        'default_attributes' => [
            'class' => 'svg',
        ]
    ],

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
);
