# Icons

- [Icons](#icons)
    - [Active/Inactive Icon Class](#active-inactive-icon-class)
- [Svg Icons](#svg-icons)
    - [Active/Inactive Svg Class](#active-inactive-svg-class)

<a name="icons"></a>
## Icons

**settings.php:**

```php
return [
    ...
    'config' => [
        'icon_family' => 'fa', // class="fa [other classes]" [ or null ]
        'icon_attributes' => [
            'class' => 'icon-xl',
        ]
    ]
    ...
];
```

```php
$menu->add('Home')->icon('fa-home');
$menu->add('Users')->icon('fa-user', true); // isAppend = true
// or attributes: $menu->add('Users')->icon('fa-user', ['data-icon' => 'hello']);
$menu->add('Settings')->appendIcon('fa-cog'); // or appendIcon()
$menu->add('Products')->icon('fa-arrow-right', ['data-icon' => 'value', 'class' => 'icon-red']);
$menu->add('Categories')->icon('fa-arrow-right', ['data-icon' => 'value', 'class' => 'icon-red'], true);
$menu->add('Others')->icon('fa-archive')->appendIcon('fa-caret-right');
```

**Output:**

```html
<ul>
    ...
    <li><a href="..."><i class="fa fa-home icon-xl"></i> Home</a></li>
    <li><a href="...">Users <i class="fa fa-user icon-xl"></i></a></li>
    <li><a href="...">Settings <i class="fa fa-cog icon-xl"></i></a></li>

    <li><a href="..."><i class="fa fa-arrow-right icon-red icon-xl" data-icon="value"></i> Products</a></li>
    <li><a href="...">Categories <i class="fa fa-arrow-right icon-red icon-xl" data-icon="value"></i></a></li>

    <li><a href="..."><i class="fa fa-archive icon-xl"></i> Others <i class="fa fa-caret-right icon-xl"></i></a></li>
    ...
</ul>
```

<a name="active-inactive-icon-class"></a>
### Active/Inactive icon class

```php
return [
    ...
    'config' => [
        'icon_family' => 'fa',
    ]
    'menus' => [
        'default' => [ // or your menu name
            'active_icon_class' => 'icon-active',
            'inactive_icon_class' => 'icon-inactive',
        ],
    ],
    ...
];
```

```php
$menu->add('Home')->icon('fa-home');

$menu->add('Users')->icon('fa-user')->active();
```

**Output:**

```html
<ul>
    ...
    <li><a href="..."><i class="fa fa-home icon-inactive"></i> Home</a></li>
    <li><a href="..."><i class="fa fa-user icon-active"></i> Users</a></li>
    ...
</ul>
```

<a name="svg-icons"></a>
## Svg Icons

**settings.php:**

```php
return [
    ...
    'config' => [
        'svg_path' => 'svg', // project/resources/svg
        'svg_attributes' => [
            'class' => 'svg',
            'fill' => 'none'
        ]
    ]
    ...
];
```

**files:**

```
project
├──app
└──resources
    └── svg
        ├─home.svg
        └──sub
            └──user.svg
```

```php
$menu->add('Home')->svg('home'); // resources/svg/home.svg
$menu->add('Users')->svg('sub.user'); // resources/svg/sub/user.svg
// $menu->add('Users')->svg('first.second.file'); // resources/svg/first/second/file.svg
// custom classes
$menu->add('Settings')->svg('icon_name', 'custom svg-xl');
// merge custom attributes
$menu->add('Settings')->svg('icon_name', ['width' => '10']);
// new attributes
$menu->add('Settings')->svg('icon_name', ['width' => '10'], false);
// or new attributes with class: 
// $menu->add('Settings')->svg('icon_name', 'custom-class', ['width' => '10'], false);
// prepend and append icons
$menu->add('Home')->svg('home')->appendSvg('caret');
```

**Note:** The above examples can also be used with the `$menu->appendSvg(...)` method.

**Output:**

```html
<ul>
    ...
    <li><a href="..."><svg class="svg" fill="none">......</svg> Home</a></li>
    <li><a href="..."><svg class="svg" fill="none">......</svg> Users</a></li>

    <-- custom classes -->
    <li><a href="..."><svg class="custom svg-xl" fill="none">......</svg> Settings</a></li>

    <-- merge custom attributes -->
    <li><a href="..."><svg class="svg" fill="none" width="10">......</svg> Settings</a></li>

    <-- new attributes -->
    <li><a href="..."><svg width="10">......</svg> Settings</a></li>

    <-- prepend and append icons-->
    <li>
        <a href="...">
            <svg class="svg" fill="none">...home icon...</svg> 
            Home
            <svg class="svg" fill="none">...caret icon...</svg>
        </a>
    </li>
    ...
</ul>
```

<a name="active-inactive-svg-class"></a>
### Active/Inactive svg Class

```php
return [
    ...
    'config' => [
        'svg_attributes' => [
            'class' => 'svg',
        ]
    ]
    'menus' => [
        'default' => [ // or your menu name
            'active_svg_class' => 'svg-active',
            'inactive_svg_class' => 'svg-inactive',
        ],
    ],
    ...
];
```

```php
Menu::make('myNavbar', function(Builder $menu){

    $menu->add('Home')->svg('home');
    
    $menu->add('Users')->svg('sub.user')->active();
    
});
```

**Output:**

```html
<ul>
    ...
    <li><a href="..."><svg class="svg svg-inactive">......</svg> Home</a></li>
    <li><a href="..."><svg class="svg svg-active">......</svg> Users</a></li>
    ...
</ul>
```

### Here is an example

It will be especially useful in terms of compatibility with [tailwindcss](https://tailwindcss.com).

```php
    'config' => [
        'svg_attributes' => [
            'class' => 'w-5 h-5 fill-current',
        ]
    ]
    'menus' => [
        'default' => [
            'active_class' => 'text-red',
            'active_element' => 'item',
            'active_svg_class' => 'bg-green-400 dark:bg-green-600 text-white',
            'inactive_svg_class' => 'bg-gray-400 dark:bg-gray-600 text-white',
        ],
        'mynavbar' => [
            'active_class' => 'text-blue',
            'active_svg_class' => 'bg-green-600 dark:bg-green-400 text-yellow',
        ],
    ],
```

```php
Menu::make('myNavbar', function(Builder $menu){

    $menu->add('Home')->svg('home');
    
    $menu->add('Users')->svg('sub.user')->active();

});

```

**Output:**

```html
<ul>
    ...
    <li>
        <a href="...">
            <svg class="w-5 h-5 fill-current bg-gray-400 dark:bg-gray-600 text-white">......</svg> 
            Home
        </a>
    </li>
    <li class="text-blue">
        <a href="...">
            <svg class="w-5 h-5 fill-current bg-green-600 dark:bg-green-400 text-yellow">......</svg> 
            Users
        </a>
    </li>
    ...
</ul>
```
