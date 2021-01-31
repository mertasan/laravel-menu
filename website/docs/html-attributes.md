# HTML Attributes

- [HTML Attributes](#html-attributes)
- [Manipulating Attributes](#manipulating-attributes)

<a name="html-attributes"></a>
## HTML Attributes
Since all menu items would be rendered as HTML entities like list items or divs, you can define as many HTML attributes as you need for each item:


```php
Menu::make('MyNavBar', function($menu){

  // As you see, you need to pass the second parameter as an associative array:
  $menu->add('Home',     ['route'  => 'home.page',  'class' => 'navbar navbar-home', 'id' => 'home']);
  $menu->add('About',    ['route'  => 'page.about', 'class' => 'navbar navbar-about dropdown']);
  $menu->add('services', ['action' => 'ServicesController@index']);
  $menu->add('Contact',  'contact');

});
```

If we choose HTML lists as our rendering format like `ul`, the result would be something similar to this:

```html
<ul>
  <li class="navbar navbar-home" id="home"><a href="http://yourdomain.com">Home</a></li>
  <li class="navbar navbar-about dropdown"><a href="http://yourdomain.com/about">About</a></li>
  <li><a href="http://yourdomain.com/services">Services</a></li>
  <li><a href="http://yourdomain.com/contact">Contact</a></li>
</ul>
```


<a name="manipulating-attributes"></a>
## Manipulating Attributes

It is also possible to set or get HTML attributes after the item has been defined using `attr()` method.

If you call `attr()` with one argument, it will return the attribute value for you.
If you call it with two arguments, It will consider the first and second parameters as a key/value pair and sets the attribute.
You can also pass an associative array of attributes if you need to add a group of HTML attributes in one step; Lastly if you call it without any arguments it will return all the attributes as an array.

```php
//...
$menu->add('About', ['url' => 'about', 'class' => 'about-item']);

echo $menu->about->attr('class');  // output:  about-item

$menu->about->attr('class', 'another-class');
echo $menu->about->attr('class');  // output:  about-item another-class

$menu->about->attr(['class' => 'yet-another', 'id' => 'about']);

echo $menu->about->attr('class');  // output:  about-item another-class yet-another
echo $menu->about->attr('id');  // output:  id

print_r($menu->about->attr());

/* Output
Array
(
    [class] => about-item another-class yet-another
    [id] => id
)
*/

//...
```

You can use `attr` on a collection, if you need to target a group of items:

```php
$menu->add('About', 'about');

$menu->about->add('Who we are', 'about/whoweare');
$menu->about->add('What we do', 'about/whatwedo');

// add a class to children of About
$menu->about->children()->attr('class', 'about-item');

```
