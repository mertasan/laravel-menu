<?php
\Menu::withConfig('examples.'.str_replace('.', '-', (CURRENT_VERSION ?? config('site.default_version'))).'.basic-usage')->make('mainMenu', function ($menu) {
    $menu->add('Home', 'home');
    $menu->dropdown('Settings', function($items){
        $items->add('General Settings');
    });
});
