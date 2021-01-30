# Filtering and Sorting the Items

- [Filtering the Items](#filtering-the-items)
- [Sorting the Items](#sorting-the-items)
    - [Sorting the Items by ID](#sorting-the-items-by-id)
    - [Sorting the Items by Passing a Closure](#sorting-the-items-by-passing-a-closure)

<a name="filtering-the-items"></a>
## Filtering the Items

We can filter menu items by a using `filter()` method.
`Filter()` receives a closure which is defined by you.It then iterates over the items and run your closure on each of them.

You must return false for items you want to exclude and true for those you want to keep.


Let's proceed with a real world scenario:

I suppose your `User` model can check whether the user has an specific permission or not:

```php
Menu::make('MyNavBar', function($menu){


  $menu->add('Users', ['route'  => 'admin.users'])
       ->data('permission', 'manage_users');

})->filter(function($item){
  if(User::get()->can( $item->data('permission'))) {
      return true;
  }
  return false;
});
```
As you might have noticed we attached the required permission for each item using `data()`.

As result, `Users` item will be visible to those who has the `manage_users` permission.

<a name="sorting-the-items"></a>
## Sorting the Items

`laravel-menu` can sort the items based on either a user defined function or a key which can be item properties like id,parent,etc or meta data stored with each item.


To sort the items based on a property and or meta data:

```php
Menu::make('main', function($m){

    $m->add('About', '#')     ->data('order', 2);
    $m->add('Home', '#')      ->data('order', 1);
    $m->add('Services', '#')  ->data('order', 3);
    $m->add('Contact', '#')   ->data('order', 5);
    $m->add('Portfolio', '#') ->data('order', 4);

})->sortBy('order');
```

`sortBy()` also receives a second parameter which specifies the ordering direction: Ascending order(`asc`) and Descending Order(`dsc`).

Default value is `asc`.

<a name="sorting-the-items-by-id"></a>
### Sorting the Items by ID

To sort the items based on `Id` in descending order:

```php
Menu::make('main', function($m){

    $m->add('About');
    $m->add('Home');
    $m->add('Services');
    $m->add('Contact');
    $m->add('Portfolio');

})->sortBy('id', 'desc');
```


<a name="sorting-the-items-by-passing-a-closure"></a>
### Sorting the Items by Passing a Closure

```php
Menu::make('main', function($m){

    $m->add('About')     ->data('order', 2);
    $m->add('Home')      ->data('order', 1);
    $m->add('Services')  ->data('order', 3);
    $m->add('Contact')   ->data('order', 5);
    $m->add('Portfolio') ->data('order', 4);

})->sortBy(function($items) {
    // Your sorting algorithm here...
});
```

The closure takes the items collection as argument.
