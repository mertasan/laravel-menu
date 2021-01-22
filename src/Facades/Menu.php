<?php

namespace Mertasan\Menu\Facades;

class Menu extends \Illuminate\Support\Facades\Facade
{

    protected static function getFacadeAccessor()
    {
        return 'menu';
    }
}
