# Configuration

You can adjust the behavior of the menu builder in `config/laravel-menu.php` file. Currently it provide a few options out of the box:

- **auto_activate** Automatically activates menu items based on the current URI
- **activate_parents** Activates the parents of an active item
- **active_class** Default CSS class name for active items
- **restful** Activates RESTful URLS. E.g `resource/slug` will activate item with `resource` url.
- **cascade_data** If you need descendants of an item to inherit meta data from their parents, make sure this option is enabled.
- **rest_base** The base URL that all restful resources might be prefixed with.
- **active_element** You can choose the HTML element to which you want to add activation classes (anchor or the wrapping element).

You're also able to override the default settings for each menu. To override settings for menu, just add the lower-cased menu name as a key in the settings array and add the options you need to override:

```php
return [
    'menus' => [
        'default' => [
            'auto_activate'    => true,
            'activate_parents' => true,
            'active_class'     => 'active',
            'active_element'   => 'item',    // item|link
            'restful'          => true,
        ],
        'yourmenuname' => [
            'auto_activate'    => false
        ],
    ]
];
```

**Alternatively**, you can override the default settings with the `$menu->options()` method. Or you can add new custom settings to the menu.

- [Menu Settings](/docs/{{version}}/menu-settings)
