# Getting Started

You can define the menu definitions inside a [laravel middleware](http://laravel.com/docs/master/middleware). As a result anytime a request hits your application, the menu objects will be available to all your views.
```bash
php artisan make:middleware GenerateMenus
```

Be sure to also add the middleware to the `app\Http\Kernel.php`
```php
    protected $middlewareGroups = [
        'web' => [
            //...
            \App\Http\Middleware\GenerateMenus::class,
        ],
        //...
    ];
```

Open the middleware you just created `app\Http\Middleware\GenerateMenus.php`

Then add a basic menu declaration. For example:

```php
public function handle($request, Closure $next)
{
    \Menu::make('MyNavBar', function ($menu) {
        $menu->add('Home');
        $menu->add('About', 'about');
        $menu->add('Services', 'services');
        $menu->add('Contact', 'contact');
    });

    return $next($request);
}
```

Finally, open a view and add:
```php
{!! $MyNavBar->asUl() !!}
```
Your menu will be created and displayed on the page.

**Note:** `$MyNavBar` is just a hypothetical name used in these examples; You may name your menus whatever you please.

In the above example `Menu::make()` creates a menu named `MyNavBar`, Adds the menu instance to the `Menu::collection` and ultimately makes `$myNavBar` object available across all application views.

This method accepts a callable inside which you can define your menu items. `add` method defines a new item. It receives two parameters, the first one is the item title and the second one is options.

The second parameter, `options`, can be a simple string representing a URL or an associative array of options and HTML attributes which we'll discuss shortly.

You can use `Menu::exists()` to check if the menu already exists.

```php
Menu::exists('primary'); // returns false
Menu::make('primary', function(){});
Menu::exists('primary'); // returns true
```

You can use `Menu::makeOnce()` to ensure the make callback is only called if a menu by the given name does not yet exist. This can be useful if you are creating the same menu in multiple places conditionally, and are unsure whether other conditions have caused the menu to be created already.

```php
Menu::makeOnce('primary', function(){}); // Creates primary, and executes callback.
Menu::makeOnce('primary', function(){}); // No operation.
```

**To render the menu in your view:**

`Laravel-menu` provides three rendering methods out of the box. However you can create your own rendering method using the right methods and attributes.

As noted earlier, `laravel-menu` provides three rendering formats out of the box, `asUl()`, `asOl()` and `asDiv()`. You can read about the details [here](#rendering-methods).

```php
{!! $MyNavBar->asUl() !!}
```

You can also access the menu object via the menu collection:

```php
{!! Menu::get('MyNavBar')->asUl() !!}
```

This will render your menu like so:

```html
<ul>
  <li><a href="http://yourdomain.com">Home</a></li>
  <li><a href="http://yourdomain.com/about">About</a></li>
  <li><a href="http://yourdomain.com/services">Services</a></li>
  <li><a href="http://yourdomain.com/contact">Contact</a></li>
</ul>
```
And that's all about it!

For type hinting, you can use it as follows

```php
    \Menu::make('MyNavBar', function (\Mertasan\Menu\Builder $menu) {
        ...
    });
```
