# Meta Data

- [Adding Meta Data](#adding-meta-data)
- [Accessing Meta Data](#accessing-meta-data)
- [Meta Data for Groups](#meta-data-for-groups)

You might encounter situations when you need to attach some meta data to each item; This data can be anything from item placement order to permissions required for accessing the item; You can do this by using `data()` method.

> {note} Meta data don't do anything to the item and won't be rendered in HTML either. It is the developer who would decide what to do with them.

<a name="adding-meta-data"></a>
## Adding Meta Data

`data()` method works exactly like `attr()` method:

If you call `data()` with one argument, it will return the data value for you.
If you call it with two arguments, It will consider the first and second parameters as a key/value pair and sets the data.
You can also pass an associative array of data if you need to add a group of key/value pairs in one step; Lastly if you call it without any arguments it will return all data as an array.

```php
Menu::make('MyNavBar', function($menu){


$menu->add('Users', ['route'  => 'admin.users'])
      ->data('permission', 'manage_users');

});
```

<a name="accessing-meta-data"></a>
## Accessing Meta Data

You can also access a data as if it's a property:

```php
//...

$menu->add('Users', '#')->data('placement', 12);

// you can refer to placement as if it's a public property of the item object
echo $menu->users->placement;    // Output : 12

//...
?>
```

<a name="meta-data-for-groups"></a>
## Meta Data for Groups

You can use `data` on a collection, if you need to target a group of items:

```php
$menu->add('Users', 'users');

$menu->users->add('New User', 'users/new');
$menu->users->add('Uses', 'users');

// add a meta data to children of Users
$menu->users->children()->data('anything', 'value');

```
