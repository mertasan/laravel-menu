# Subset Menus

- [Top Menu](#top-menu)
- [Sub Menu](#sub-menu)
- [Sibling Menu](#sibling-menu)
- [Crumb Menu](#crumb-menu)

With your menu constructed you can call any of our subset menu functions to get a new `Builder` to quick generate additional menus.

<a name="top-menu"></a>
## Top Menu

This generates a `Builder` of the top level items, items without a parent.

```php
{!! Menu::get('primary')->topMenu()->asUl() !!}
```

<a name="sub-menu"></a>
## Sub Menu

This generates a `Builder` of the immediate children of the active item.

```php
{!! Menu::get('primary')->subMenu()->asUl() !!}
```

<a name="sibling-menu"></a>
## Sibling Menu

This generates a `Builder` of the siblings of the active item.

```php
{!! Menu::get('primary')->siblingMenu()->asUl() !!}
```

<a name="crumb-menu"></a>
## Crumb Menu

This generates a `Builder` by recursively getting all of the parent items for the active item (including the active item).

```php
{!! Menu::get('primary')->crumbMenu()->asUl() !!}
```
