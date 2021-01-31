# Sub-items


<a name="sub-items"></a>
## Sub Items

Items can have sub-items too:

```php
Menu::make('MyNavBar', function($menu){

  //...

  $menu->add('About',    ['route'  => 'page.about']);

  // these items will go under Item 'About'

  // refer to about as a property of $menu object then call `add()` on it
  $menu->about->add('Who We are', 'who-we-are');

  // or

  $menu->get('about')->add('What We Do', 'what-we-do');

  // or

  $menu->item('about')->add('Our Goals', 'our-goals');

  //...

});
```

You can also chain the item definitions and go as deep as you wish:

```php
  $menu->add('About', ['route'  => 'page.about'])
       ->add('Level2', 'link address')
       ->add('level3', 'Link address')
       ->add('level4', 'Link address');
```  

It is possible to add sub items directly using `parent` attribute:

```php
$menu->add('About',    ['route'  => 'page.about']);
$menu->add('Level2', ['url' => 'Link address', 'parent' => $menu->about->id]);
```  
