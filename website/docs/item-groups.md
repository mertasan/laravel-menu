# Item Groups

- [Item Groups](#item-groups)
- [Nested Groups](#nested-groups)
- [URL Prefixing](#url-prefixing)
- [Related Documents](#related-documents)

<a name="item-groups"></a>
## Item Groups

Sometimes you may need to share attributes between a group of items. Instead of specifying the attributes and options for each item, you may use a menu group feature:

**PS:** This feature works exactly like Laravel group routes.


```php
Menu::make('MyNavBar', function($menu){

  $menu->add('Home',     ['route'  => 'home.page', 'class' => 'navbar navbar-home', 'id' => 'home']);

  $menu->group(['style' => 'padding: 0', 'data-role' => 'navigation'], function($m){

        $m->add('About',    ['route'  => 'page.about', 'class' => 'navbar navbar-about dropdown']);
        $m->add('services', ['action' => 'ServicesController@index']);
  }

  $menu->add('Contact',  'contact');

});
```

Attributes `style` and `data-role` would be applied to both `About` and `Services` items:

```html
<ul>
    <li class="navbar navbar-home" id="home"><a href="http://yourdomain.com">Home</a></li>
    <li style="padding: 0" data-role="navigation" class="navbar navbar-about dropdown"><a href="http://yourdomain.com/about"About</a></li>
    <li style="padding: 0" data-role="navigation"><a href="http://yourdomain.com/services">Services</a></li>
</ul>
```

<a name="nested-groups"></a>
## Nested Groups

Laravel Menu supports nested grouping feature as well. A menu group merges its own attribute with its parent group then shares them between its wrapped items:

```php
Menu::make('MyNavBar', function($menu){


    $menu->group(['prefix' => 'pages', 'data-info' => 'test'], function($m){

        $m->add('About', 'about');

        $m->group(['prefix' => 'about', 'data-role' => 'navigation'], function($a){

            $a->add('Who we are', 'who-we-are?');
            $a->add('What we do?', 'what-we-do');
            $a->add('Our Goals', 'our-goals');
        });
    });

});
```

If we render it as a ul:

```html
<ul>
    ...
    <li data-info="test">
        <a href="http://yourdomain.com/pages/about">About</a>
        <ul>
            <li data-info="test" data-role="navigation"><a href="http://yourdomain.com/pages/about/who-we-are"></a></li>
            <li data-info="test" data-role="navigation"><a href="http://yourdomain.com/pages/about/what-we-do"></a></li>
            <li data-info="test" data-role="navigation"><a href="http://yourdomain.com/pages/about/our-goals"></a></li>
        </ul>
    </li>
</ul>
```

<a name="url-prefixing"></a>
## URL Prefixing

Just like Laravel route prefixing feature, a group of menu items may be prefixed by using the `prefix` option in the  array being passed to the group.

**Attention:** Prefixing only works on the menu items addressed with `url` but not `route` or `action`.

```php
Menu::make('MyNavBar', function($menu){

  $menu->add('Home',     ['route'  => 'home.page', 'class' => 'navbar navbar-home', 'id' => 'home']);

  $menu->add('About', ['url'  => 'about', 'class' => 'navbar navbar-about dropdown']);  // URL: /about

  $menu->group(['prefix' => 'about'], function($about){

    $about->add('Who we are?', 'who-we-are');   // URL: about/who-we-are
    $about->add('What we do?', 'what-we-do');   // URL: about/what-we-do

  });

  $menu->add('Contact',  'contact');

});
```

This will generate:

```html
<ul>
    <li class="navbar navbar-home" id="home"><a href="/">Home</a></li>

    <li data-role="navigation" class="navbar navbar-about dropdown"><a href="http://yourdomain.com/about/summary"About</a>
        <ul>
           <li><a href="http://yourdomain.com/about/who-we-are">Who we are?</a></li>
           <li><a href="http://yourdomain.com/about/who-we-are">What we do?</a></li>
        </ul>
    </li>

    <li><a href="services">Services</a></li>
    <li><a href="contact">Contact</a></li>
</ul>
```

<a name="related-documents"></a>
## Related Documents
- [Meta Data for Groups](/docs/{{version}}/meta-data#meta-data-for-groups)
