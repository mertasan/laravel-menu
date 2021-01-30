# Menu Settings

- [Override Default Settings](#override-default-settings)
- [New Settings Specific to the Menu](#add-new-settings-specific-to-menu)
- [Adding Custom & Shared Settings](#adding-custom-and-shared-settings)
- [Override All Settings](#override-all-settings)
- [Settings Defined in the make Method](#settings-defined-in-the-make-method)

To manipulate menu config data later you can override the default settings with the following methods. Or you can add new custom settings to the menu.


**Sample `config/laravel-menu.php` file content**

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
        'mysidebar' => [
            'active_class'     => 'active-class-mysidebar',
        ],
        'mynavbar' => [
            'active_class'     => 'active-class-mynavbar',
        ],
    ]
];
```

<a name="override-default-settings"></a>
## Override Default Settings

```php
Menu::make('MySidebar', function ($menu) {
    $menu->options([
        'active_class' => 'new-active-class',
    ]);
    $menu->add('Home');
    $menu->add('About', 'about');
    
});
/**
 * Results:
[
    'auto_activate'    => true,
    'activate_parents' => true,
    'active_class'     => 'new-active-class'
    'active_element'   => 'item',
    'restful'          => true,
]
*/
```


<a name="add-new-settings-specific-to-menu"></a>
## Add New Settings Specific to the Menu

```php
Menu::make('MySidebar', function ($menu) {
    $menu->options([
        'inactive_class' => 'custom-inactive-class-mysidebar',
    ]);
    $menu->add('Home');
    $menu->add('About', 'about');
    
});
/**
 * Results:
[
    'auto_activate'    => true,
    'activate_parents' => true,
    'active_class'     => 'active-class-mysidebar'
    'active_element'   => 'item',
    'restful'          => true,
    'inactive_class'   => 'custom-inactive-class-mysidebar',
]
*/
```

<a name="adding-custom-and-shared-settings"></a>
## Adding Custom & Shared Settings

Add custom settings and get the rest of the settings from `MySidebar`.

```php
Menu::make('MyNavbar', function ($menu) {
    $menu->options([
        'inactive_class' => 'custom-inactive-class-mynavbar',
    ], 'MySidebar'); // or mysidebar
    $menu->add('Home');
    $menu->add('About', 'about');
    
});
/**
 * Results:
[
    'auto_activate'    => true,
    'activate_parents' => true,
    'active_class'     => 'active-class-mysidebar'
    'active_element'   => 'item',
    'restful'          => true,
    'inactive_class'   => 'custom-inactive-class-mynavbar',
]
*/
```


<a name="override-all-settings"></a>
## Override All Settings

Override all settings (including default settings) and add new ones.

```php
Menu::make('MyNavbar', function ($menu) {
    $menu->options([
        'active_class' => 'active',
    ], null); 
    $menu->add('Home');
    $menu->add('About', 'about');
    
});
/**
 * Results:
[
    'active_class'     => 'active'
]
*/
```

<a name="settings-defined-in-the-make-method"></a>
## Settings Defined in the `make()` Method

```php
Menu::make('MyNavbar', function ($menu) {
    $menu->add('Home');
    $menu->add('About', 'about');
    
}, [
    'inactive_class' => 'custom-inactive-class-mynavbar',
]);
/**
 * Results:
[
    'auto_activate'    => true,
    'activate_parents' => true,
    'active_class'     => 'active-class-mynavbar'
    'active_element'   => 'item',
    'restful'          => true,
    'inactive_class'   => 'custom-inactive-class-mynavbar',
]
*/
```
