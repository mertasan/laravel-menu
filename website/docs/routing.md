# Routing

- [URLs](#urls)
- [Named Routes](#named-routes)
- [Controller Actions](#controller-actions)
    - [With Parameters](#with-parameters)
- [HTTPS](#https)
- [Manipulating Links](#manipulating-links)
    - [Link's Href Property](#links-href-property)
- [Related documents](#related-documents)

<a name="urls"></a>
## URLs

You can simply assign a URL to your menu item by passing the URL as the second argument to `add` method:

```php
$menu->add('About Us', 'about-us');
```

<a name="named-routes"></a>
## Named Routes

`laravel-menu` supports named routes as well:

This time instead of passing a simple string to `add()`, we pass an associative with key `route` and a named route as value:

```php
// Suppose we have these routes defined in our app/routes.php file

//...
Route::get('/',        ['as' => 'home.page',  function(){...}]);
Route::get('about',    ['as' => 'page.about', function(){...}]);
//...

// Now we make the menu:

Menu::make('MyNavBar', function($menu){

  $menu->add('Home',     ['route'  => 'home.page']);
  $menu->add('About',    ['route'  => 'page.about']);

});
```

<a name="controller-actions"></a>
## Controller Actions

Laravel Menu supports controller actions as well.

You will just need to set `action` key of your options array to a controller action:

Suppose we have these routes defined in our `routes/web.php` or the older `app/Http/routes.php` file:

```php
Route::get('services', 'ServiceController@index');
```

Then to refer to this route, we can pass the action into the options array.

```php
$menu->add('services', ['action' => 'ServicesController@index']);
```

<a name="with-parameters"></a>
## With Parameters

**Additionaly:** if you need to send some parameters to routes, URLs or controller actions as a query string, you can simply include them in an array along with the route, action or URL value:

```php
Menu::make('MyNavBar', function($menu){

  $menu->add('Home',     ['route'  => 'home.page']);
  $menu->add('About',    ['route'  => ['page.about', 'template' => 1]]);
  $menu->add('services', ['action' => ['ServicesController@index', 'id' => 12]]);

  $menu->add('Contact',  'contact');

});
```

<a name="manipulating-links"></a>
## Manipulating Links

All the HTML attributes will go to the wrapping tags(li, div, etc); You might encounter situations when you need to add some HTML attributes to `<a>` tags as well.

Each `Item` instance has an attribute which stores an instance of `Link` object. This object is provided for you to manipulate `<a>` tags.

Just like each item, `Link` also has an `attr()` method which functions exactly like item's:

```php
Menu::make('MyNavBar', function($menu){

  $about = $menu->add('About',    ['route'  => 'page.about', 'class' => 'navbar navbar-about dropdown']);

  $about->link->attr(['class' => 'dropdown-toggle', 'data-toggle' => 'dropdown']);

});
```

<a name="links-href-property"></a>
### Link's Href Property

If you don't want to use the routing feature of `laravel-menu` or you don't want the builder to prefix your URL with anything (your host address for example), you can explicitly set your link's href property:

```
$menu->add('About')->link->href('#');
```

<a name="related-documents"></a>
## Related documents

- [RESTful URLs](/docs/{{version}}/active-item#restful-urls)
- [URL Wildcards](/docs/{{version}}/active-item#url-wildcards)
- [URL Prefixing](/docs/{{version}}/item-groups#url-prefixing)
