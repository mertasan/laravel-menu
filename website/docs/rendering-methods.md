# Rendering Methods

- [Menu as Unordered List](#menu-as-unordered-list)
- [Menu as Ordered List](#menu-as-ordered-list)
- [Menu as Div](#menu-as-div)
- [Menu as Bootstrap 3 Navbar](#menu-as-bootstrap-3-navbar)
- [Adding class attributes to child items](#adding-class-attributes-to-child-items)


<a name="menu-as-unordered-list"></a>
## Menu as Unordered List

Several rendering formats are available out of the box:

```html
  {!! $MenuName->asUl() !!}
```

`asUl()` will render your menu in an unordered list. it also takes an optional parameter to define attributes for the `<ul>` tag itself:

```php
{!! $MenuName->asUl( ['class' => 'awesome-ul'] ) !!}
```

Result:

```html
<ul class="awesome-ul">
  <li><a href="http://yourdomain.com">Home</a></li>
  <li><a href="http://yourdomain.com/about">About</a></li>
  <li><a href="http://yourdomain.com/services">Services</a></li>
  <li><a href="http://yourdomain.com/contact">Contact</a></li>
</ul>
```

<a name="menu-as-ordered-list"></a>
## Menu as Ordered List


```php
{!! $MenuName->asOl() !!}
```

`asOl()` method will render your menu in an ordered list. it also takes an optional parameter to define attributes for the `<ol>` tag itself:

```php
{!! $MenuName->asOl( ['class' => 'awesome-ol'] ) !!}
```

Result:

```html
<ol class="awesome-ol">
  <li><a href="http://yourdomain.com">Home</a></li>
  <li><a href="http://yourdomain.com/about">About</a></li>
  <li><a href="http://yourdomain.com/services">Services</a></li>
  <li><a href="http://yourdomain.com/contact">Contact</a></li>
</ol>
```

<a name="menu-as-div"></a>
## Menu as Div

```php
{!! $MenuName->asDiv() !!}
```

`asDiv()` method will render your menu as nested HTML divs. it also takes an optional parameter to define attributes for the parent `<div>` tag itself:

```php
{!! $MenuName->asDiv( ['class' => 'awesome-div'] ) !!}
```

Result:

```html
<div class="awesome-div">
  <div><a href="http://yourdomain.com">Home</a></div>
  <div><a href="http://yourdomain.com/about">About</a></div>
  <div><a href="http://yourdomain.com/services">Services</a></div>
  <div><a href="http://yourdomain.com/contact">Contact</a></div>
</div>
```

<a name="menu-as-bootstrap-3-navbar"></a>
## Menu as Bootstrap 3 Navbar

Laravel Menu provides a parital view out of the box which generates menu items in a bootstrap friendly style which you can **include** in your Bootstrap based navigation bars:

You can access the partial view by `config('laravel-menu.views.bootstrap-items')`.

All you need to do is to include the partial view and pass the root level items to it:

```html
...

@include(config('laravel-menu.views.bootstrap-items'), ['items' => $mainNav->roots()])

...

```

This is how your Bootstrap code is going to look like:

```html
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Brand</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

       @include(config('laravel-menu.views.bootstrap-items'), ['items' => $mainNav->roots()])

      </ul>
      <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">

        @include(config('laravel-menu.views.bootstrap-items'), ['items' => $loginNav->roots()])

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
```

In case you are using bootstrap 5 (currently in beta) you need to set the `data_toggle_attribute` option from `data-toggle` to `data-bs-toggle` in your `config/laravel-menu.php`.

```php
return [
    ...
    'menus' => [
        'default' => [
            'data_toggle_attribute' => 'data-toggle',
        ],
    ],
    ...
];
```

<a name="adding-class-attributes-to-child-items"></a>
## Adding Class Attributes to Child Items

Like adding a class to the menu `ul` and `ol`, classes can be added the submenu too. The three parameters to `asUl` are arrays as follows:

- The first array is the attributes for the list: for example, `ul`
- The second is the attributes for the child lists, for example, `ul>li>ul`
- The third array is attributes that are added to the attributes of the `li` element

With this you can add a class to the child menu (submenu) like this:

```php
{!! $menu->asUl( ['class' => 'first-level-ul'], ['class' => 'second-level-ul'] ) !!}
```
