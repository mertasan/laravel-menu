# Advanced Usage

- [A Basic Example](#a-basic-example)
- [Control Structure for Blade](#control-structure-for-blade)
    - [@lm_attrs](#lm_attrs)
- [Attributes and Callback function of item](#attributes-and-callback-function-of-item)

You can create your own rendering formats.

<a name="a-basic-example"></a>
## A Basic Example

If you'd like to render your menu(s) according to your own design, you should create two views.

* `View-1`  This view contains all the HTML codes like `nav` or `ul` or `div` tags wrapping your menu items.
* `View-2`  This view is actually a partial view responsible for rendering menu items (it is going to be included in `View-1`.)


The reason we use two view files here is that `View-2` calls itself recursively to render the items to the deepest level required in multi-level menus.

Let's make this easier with an example:

```php
Menu::make('MyNavBar', function($menu){

  $menu->add('Home');

  $menu->add('About',    ['route'  => 'page.about']);

  $menu->about->add('Who are we?', 'who-we-are');
  $menu->about->add('What we do?', 'what-we-do');

  $menu->add('Services', 'services');
  $menu->add('Contact',  'contact');

});
```

In this example we name View-1 `custom-menu.blade.php` and View-2 `custom-menu-items.blade.php`.

**custom-menu.blade.php**

```php
<nav class="navbar">
  <ul class="horizontal-navbar">
    @include('custom-menu-items', ['items' => $MyNavBar->roots()])
  </ul>
</nav><!--/nav-->
```

**custom-menu-items.blade.php**

```php
@foreach($items as $item)
  <li @if($item->hasChildren()) class="dropdown" @endif>
      <a href="{!! $item->url() !!}">{!! $item->title !!} </a>
      @if($item->hasChildren())
        <ul class="dropdown-menu">
              @include('custom-menu-items', ['items' => $item->children()])
        </ul>
      @endif
  </li>
@endforeach
```

Let's describe what we did above, In `custom-menus.blade.php` we put whatever HTML boilerplate code we had according to our design, then we included `custom-menu-items.blade.php` and passed the menu items at *root level* to `custom-menu-items.blade.php`:

```php
...
@include('custom-menu-items', ['items' => $menu->roots()])
...
```

In `custom-menu-items.blade.php` we ran a `foreach` loop and called the file recursively in case the current item had any children.

To put the rendered menu in your application template, you can simply include `custom-menu` view in your master layout.

<a name="control-structure-for-blade"></a>
## Control Structure For Blade

Laravel menu extends Blade to handle special layouts.

<a name="lm_attrs"></a>
### @lm_attrs

You might encounter situations when some of your HTML properties are explicitly written inside your view instead of dynamically being defined when adding the item; However you will need to merge these static attributes with your Item's attributes.

```php
@foreach($items as $item)
  <li @if($item->hasChildren()) class="dropdown" @endif data-test="test">
      <a href="{!! $item->url() !!}">{!! $item->title !!} </a>
      @if($item->hasChildren())
        <ul class="dropdown-menu">
              @include('custom-menu-items', ['items' => $item->children()])
        </ul>
      @endif
  </li>
@endforeach
```

In the above snippet the `li` tag has class `dropdown` and `data-test` property explicitly defined in the view; Laravel Menu provides a control structure which takes care of this.

Suppose the item has also several attributes dynamically defined when being added:

```php
$menu->add('Dropdown', ['class' => 'item item-1', 'id' => 'my-item']);
```

The view:

```php
@foreach($items as $item)
  <li @lm_attrs($item) @if($item->hasChildren()) class="dropdown" @endif data-test="test" @lm_endattrs>
      <a href="{!! $item->url !!}">{!! $item->title !!} </a>
      @if($item->hasChildren())
        <ul class="dropdown-menu">
              @include('custom-menu-items', ['items' => $item->children()])
        </ul>
      @endif
  </li>
@endforeach
```

This control structure automatically merges the static HTML properties with the dynamically defined properties.

Here's the result:

```
...
<li class="item item-1 dropdown" id="my-item" data-test="test">...</li>
...
```

<a name="attributes-and-callback-function-of-item"></a>
## Attributes and Callback function of item

When printing a list, you can:
Set the attributes for the list element;
Set the callback function, to add a prefix to each link or by condition ("?id={$id}") and much more.

* **Example of converting a menu into a drop-down list for mobile**

Controller:
```php
$items=[
    'copy'=>[
        'icon'=>'fa-copy',
        'title'=>'Copy',
        'text'=>'Copy',
        'link_attribute'=>[
            'class'=>'nav-link',
            'href'=> url(Request::capture()->path()."/copy"),
        ]
    ],
];

$controlItem = Menu::make('controlItem', function($menu) use ($items){
    foreach ($items as $key => $item) if(!isset($item['visible']) || $item['visible']){
        $menu->add($item['text'],['title'=>$item['title']])
            ->append('</span>')
            ->prepend('<i class="fa '.$item['icon'].'"></i> <span>')
            ->link->attr($item['link_attribute']);
    }
});

return view('layouts.table.view',[
    'controlItem' => $controlItem
]);
```
View: layouts.table.view
```php
<ul class="control-items-min">
    <li title="Menu">
        <a data-toggle="dropdown" aria-expanded="true"><i class="fa fa-bars"></i> <span></span></a>
    <!-- The first array is the attributes for the list: for example, `ul`;
         The second is the attributes for the child lists, for example, `ul>li>ul`;
         The third array is attributes that are added to the attributes of the `li` element. -->
        <?php echo $controlItem->asUl(['class'=>'dropdown-menu', 'role'=>'menu'],[],['class'=>'dropdown-item']); ?>
    </li>
</ul>
<?php echo $controlItem->asUl(['class'=>'control-items'],[],['class'=>'nav-item']); ?>
```

* **Example of printing the recording management menu**

Controller:
```php
$items=[
    'copy'=>[
        'icon'=>'fa-copy',
        'title'=>'Copy',
        'text'=>'Copy',
        'link_attribute'=>[
            'class'=>'nav-link',
            'href'=> url(Request::capture()->path()."/copy"),
        ]
    ],
];

$controlItem = Menu::make('controlItem', function($menu) use ($items){
    foreach ($items as $key => $item) if(!isset($item['visible']) || $item['visible']){
        $menu->add($item['text'],['title'=>$item['title']])
            ->append('</span>')
            ->prepend('<i class="fa '.$item['icon'].'"></i> <span>')
            ->link->attr($item['link_attribute']);
    }
});

return view('layouts.table.view',[
    'controlItem' => $controlItem
]);
```
View: layouts.table.view (use in a cycle with different IDs)
```php
echo (isset($controlItem)) ? $controlItem->asUl(
    ['class'=>'dropdown-menu control-item'],
    [],
    ['class'=>'nav-item'],
    function($item, &$children_attributes, &$item_attributes, &$link_attr, &$id){
        $link_attr['href'] .= "/".(int)$id;
    },
    $id):'';
```
