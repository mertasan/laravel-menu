# Referring to Items

- [Get Item by Title](#get-item-by-title)
    - [Adding a Divider After Item](#adding-divider-after-item)
- [Get Item by ID](#get-item-by-id)
- [Get All Items](#get-all-items)
- [Get the First Item](#get-first-item)
- [Get the Last Item](#get-last-item)
- [Get the Active Item](#get-active-item)
- [Get Sub-items of the Item](#get-sub-items-of-the-item)
    - [Check if Child Items has Item](#check-if-child-items-has-item)
- [Get the Parent of the Item](#get-the-parent-of-the-item)
    - [Check if Parent Items has Item](#check-if-parent-items-has-item)
- [Named Routes](#named-routes)
- [Controller Actions](#controller-actions)
    - [With Parameters](#with-parameters)
- [HTTPS](#https)

You can access defined items throughout your code using the methods described below.

<a name="get-item-by-title"></a>
## Get Item by Title

Use $menu followed by the item's title in *camel case*:

```php
$menu->itemTitleInCamelCase

// or

$menu->get('itemTitleInCamelCase');

// or

$menu->item('itemTitleInCamelCase');
```


<a name="adding-divider-after-item"></a>
### Adding a divider after item

As an example, let's insert a divider after `About us` item after we've defined it:

```php
$menu->add('About us', 'about-us')

$menu->aboutUs->divide();

// or

$menu->get('aboutUs')->divide();

// or

$menu->item('aboutUs')->divide();
```

If you're not comfortable with the above method you can store the item's object reference in a variable for further reference:

```php
$about = $menu->add('About', 'about');
$about->add('Who We Are', 'who-we-are');
$about->add('What We Do', 'what-we-do');
```

<a name="get-item-by-id"></a>
## Get Item by ID

You can also get an item by Id if needed:

```php
$menu->add('About', ['url' => 'about', 'id' => 12]);

$about = $menu->find(12)
```

<a name="get-all-items"></a>
## Get All Items

```php
$menu->all();

// or outside of the builder context

Menu::get('MyNavBar')->all();
```

The `all()` method returns a *Laravel Collection*.

<a name="get-first-item"></a>
## Get the First Item

```php
$menu->first();

// or outside of the builder context

Menu::get('MyNavBar')->first();
```

<a name="get-last-item"></a>
## Get the Last Item

```php
$menu->last();

// or outside of the builder context

Menu::get('MyNavBar')->last();
```

<a name="get-active-item"></a>
## Get the Active Item

```php
$menu->active()

// or outside of the builder content

Menu::get('MyNavBar')->active();
```

<a name="get-sub-items-of-the-item"></a>
## Get Sub-Items of the Item

First of all you need to get the item using the methods described above then call `children()` on it.

To get children of `About` item:

```php
$aboutSubs = $menu->about->children();

// or outside of the builder context

$aboutSubs = Menu::get('MyNavBar')->about->children();

// or

$aboutSubs = Menu::get('MyNavBar')->item('about')->children();
```

`children()` returns a *Laravel Collection*.

<a name="check-if-child-items-has-item"></a>
### Check if Child Items has Item

To check if an item has any children or not, you can use `hasChildren()`

```php
if( $menu->about->hasChildren() ) {
    // Do something
}

// or outside of the builder context

Menu::get('MyNavBar')->about->hasChildren();

// Or

Menu::get('MyNavBar')->item('about')->hasChildren();
```

To get all descendants of an item you may use `all`:

```php
$aboutSubs = $menu->about->all();
```

<a name="get-the-parent-of-the-item"></a>
## Get the Parent of the Item

First get the item using one of the methods above then call `parent()` on it.

To get the parent of `About` item

```php
$aboutParent = $menu->about->parent();

// or outside of the builder context

$aboutParent = Menu::get('MyNavBar')->about->parent();

// Or

$aboutParent = Menu::get('MyNavBar')->item('about')->parent();
```

<a name="check-if-parent-items-has-item"></a>
## Check if Parent Items has Item

To check if an item has a parent or not, you can use `hasParent()`

```php
if( $menu->about->hasParent() ) {
    // Do something
}

// or outside of the builder context

Menu::get('MyNavBar')->about->hasParent();

// Or

Menu::get('MyNavBar')->item('about')->hasParent();
```

<a name="magic-where-methods"></a>
## Magic Where Methods

You can also search the items collection by magic where methods.
These methods are consisted of a `where` concatenated with a property (object property or even meta data)

For example to get an item with parent equal to 12, you can use it like so:

```php
$subs = $menu->whereParent(12);
```

Or to get item's with a specific meta data:

```php
$menu->add('Home',     '#')->data('color', 'red');
$menu->add('About',    '#')->data('color', 'blue');
$menu->add('Services', '#')->data('color', 'red');
$menu->add('Contact',  '#')->data('color', 'green');

// Fetch all the items with color set to red:
$reds = $menu->whereColor('red');
```

This method returns a *Laravel collection*.

If you need to fetch descendants of the matched items as well, Just set the second argument as true.

```php
$reds = $menu->whereColor('red', true);
```

This will give all items with color red and their decsendants.
