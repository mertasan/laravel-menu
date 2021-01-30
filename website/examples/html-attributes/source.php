<?php
\Menu::withConfig('examples.'.str_replace('.', '-', (CURRENT_VERSION ?? config('site.default_version'))).'.html-attributes')->make('mainMenu', function($menu){

    // As you see, you need to pass the second parameter as an associative array:
    $menu->add('Home',     ['route'  => 'home.page',  'class' => 'navbar navbar-home', 'id' => 'home']);
    $menu->add('About',    ['route'  => 'page.about', 'class' => 'navbar navbar-about dropdown']);
    $menu->add('Contact',  'contact');

});
