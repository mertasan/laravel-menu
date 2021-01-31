# Item's ID and Nicknames

<a name="items-id"></a>
## Set Item's ID Manually

When you add a new item, a unique ID is automatically assigned to the item. However, there are time when you're loading the menu items from the database and you have to set the ID manually. To handle this, you can call the `id()` method against the item's object and pass your desired ID:

```php
$menu->add('About', ['route' => 'page.about'])
     ->id('74398247329487');
```

Alternatively, you can pass the ID as an element of the options array when adding the menu item:

```php
$menu->add('About', ['route' => 'page.about', 'id' => 74398247329487]);
```

<a name="items-nickname"></a>
## Set Item's Nicknames Manually

When you add a new item, a nickname is automatically assigned to the item for further reference. This nickname is the camel-cased form of the item's title. For instance, an item with the title: `About Us` would have the nickname: `aboutUs`.
However there are times when you have to explicitly define your menu items owing to a special character set you're using. To do this, you may simply use the `nickname()` method against the item's object and pass your desired nickname to it:

```php
$menu->add('About', ['route' => 'page.about'])
     ->nickname('about_menu_nickname');

// And use it like you normally would
$menu->item('about_menu_nickname');
```

Alternatively, you can pass the nickname as an element of the options array:

```php
$menu->add('About', ['route' => 'page.about', 'nickname' => 'about_menu_nickname']);

// And use it like you normally would
$menu->item('about_menu_nickname');    
```
