# Active Item

- [Active Item](#active-item)
    - [RESTful URLs](#restful-urls)
    - [URL Wildcards](#url-wildcards)
    - [Disable activation](#disable-activation)

<a name="active-item"></a>
## Active Item

You can mark an item as activated using `active()` on that item:

```php
$menu->add('Home', '#')->active();

/* Output
 *
 * <li class="active"><a href="#">#</a></li>
 *
 */
```

You can also add class `active` to the anchor element instead of the wrapping element (`div` or `li`):

```php
$menu->add('Home', '#')->link->active();

/* Output
 *
 * <li><a class="active" href="#">#</a></li>
 *
 */
```

Laravel Menu does this for you automatically according to the current **URI** the time you register the item.

You can also select the item (item or link) to be enabled from the package's configuration settings:

```php
'active_element' => 'item',    // item|link
```

<a name="restful-urls"></a>
## RESTful URLs

RESTful URLs are also supported as long as `restful` option is set as `true` in `config/laravel-menu.php` (`config('laravel-menu.menus.[default or your menu name].restful')`) file, E.g. menu item with url `resource` will be activated by `resource/slug` or `resource/slug/edit`.

You might encounter situations where your app is in a sub directory instead of the root directory or your resources have a common prefix; In such case you need to set `rest_base` option to a proper prefix for a better restful activation support. `rest_base` can take a simple string, array of string or a function call as value.

<a name="url-wildcards"></a>
## URL Wildcards

`laravel-menu` makes you able to define a pattern for a certain item, if the automatic activation can't help:

```php
$menu->add('Articles', 'articles')->active('this-is-another-url/*');
```

So `this-is-another-url`, `this-is-another-url/and-another` will both activate `Articles` item.

<a name="disable-activation"></a>
## Disable activation

Sometimes you may need to disable auto activation for single items.
You can pass **disableActivationByURL** in options like this:

```php
$menu->add('Anchor', ['disableActivationByURL' => true, 'url' => '#']);
```
This prevents auto activation by matching URL.
But activation for items with active children keeps working.
