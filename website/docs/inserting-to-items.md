# Inserting to Items

- [Inserting a Separator](#inserting-a-separator)
- [Append and Prepend](#append-and-prepend)
- [Before and After](#before-and-after)

<a name="inserting-a-separator"></a>
## Inserting a Separator

You can insert a separator after each item using `divide()` method:

```php
//...
$menu->add('Separated Item', 'item-url')->divide()

// You can also use it this way:

$menu->('Another Separated Item', 'another-item-url');

// This line will insert a divider after the last defined item
$menu->divide()

//...

/*
 * Output as <ul>:
 *
 *    <ul>
 *        ...
 *        <li><a href="item-url">Separated Item</a></li>
 *        <li class="divider"></li>
 *
 *        <li><a href="another-item-url">Another Separated Item</a></li>
 *        <li class="divider"></li>
 *        ...
 *    </ul>
 *
 */
```

`divide()` also gets an associative array of attributes:

```php
//...
$menu->add('Separated Item', 'item-url')->divide( ['class' => 'my-divider'] );
//...

/*
 * Output as <ul>:
 *
 *    <ul>
 *        ...
 *        <li><a href="item-url">Separated Item</a></li>
 *        <li class="my-divider divider"></li>
 *
 *        ...
 *    </ul>
 *
 */
```

<a name="append-and-prepend"></a>
## Append and Prepend


You can `append` or `prepend` HTML or plain-text to each item's title after it is defined:

```php
Menu::make('MyNavBar', function($menu){


  $about = $menu->add('About',    ['route'  => 'page.about', 'class' => 'navbar navbar-about dropdown']);

  $menu->about->attr(['class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'])
              ->append(' <b class="caret"></b>')
              ->prepend('<span class="glyphicon glyphicon-user"></span> ');

  // ...            

});
```

The above code will result:

```html
<ul>
  ...

  <li class="navbar navbar-about dropdown">
   <a href="about" class="dropdown-toggle" data-toggle="dropdown">
     <span class="glyphicon glyphicon-user"></span> About <b class="caret"></b>
   </a>
  </li>
</ul>

```

You can call `prepend` and `append` on collections as well.


<a name="before-and-after"></a>
## Before and After

Allows you to add an arbitrary html block instead of a drop-down list. And many other possibilities.
Unlike `append` and `prepend`, `before` and `after` adds an arbitrary html to the root of the tag li.

```php
Menu::make('MyNavBar', function($menu){


  $menu->add('User', ['title' => Auth::user()->name, 'class' => 'nav-item'])
      ->after(view('layouts.pattern.menu.user_info'))
      ->link->attr([
          'class'         => 'nav-link dropdown-toggle',
          'data-toggle'   => 'dropdown',
          'role'          => 'button',
          'aria-expanded' => 'false',
      ]);

  // ...            

});
```

Resource of view, pattern: layouts.pattern.menu.user_info

```html
<div class="dropdown-menu" role="menu">    
    <div class="user-info-header">
        <?php echo Auth::user()->name; ?><br>
    </div>
    <div class="pull-left">
        <a href="<?php echo url('tools/profile'); ?>" class="btn btn-primary btn-flat">Profile</a>
    </div>
    <div class="pull-right">
        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-primary btn-flat">
            <i class="fa fa-power-off"></i>&nbsp;Exit
        </a>
        <form id="logout-form" action="<?php echo route('logout'); ?>" method="POST" style="display: none;">
            <?php echo csrf_field(); ?>
        </form>
    </div>
</div>
```

The above code will result:

```html
<li title="Username" class="nav-item">
    <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        User
    </a>
    <div class="dropdown-menu" role="menu">    
        <div class="user-info-header">
            <?php echo Auth::user()->name; ?>
        </div>
        <div class="pull-left">
            <a href="<?php echo url('tools/profile'); ?>" class="btn btn-primary btn-flat">Profile</a>
        </div>
        <div class="pull-right">
            <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-primary btn-flat">
                <i class="fa fa-power-off"></i>&nbsp;Exit
            </a>
            <form id="logout-form" action="<?php echo route('logout'); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
        </div>
    </div>
</li>
```
