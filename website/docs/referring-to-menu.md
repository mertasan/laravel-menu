# Referring to Menu

<a name="referring-to-menu-instances"></a>
## Referring to Menu Instances
You might encounter situations when you need to refer to menu instances out of the builder context.


To get a specific menu by name:

```php
$menu = Menu::get('MyNavBar');
```

Or to get all menus instances:

```php
$menus = Menu::all();
```
You can also call `getCollection()` to get the same result:

```php
$menus = Menu::getCollection();
```

Both methods return a *Laravel Collection*
