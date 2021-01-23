<?php

namespace Mertasan\Menu;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Collection as IlluminateCollection;

class Menu
{
    /**
     * Menu collection.
     *
     * @var IlluminateCollection
     */
    protected IlluminateCollection $collection;

    /**
     * List of menu builders.
     *
     * @var Builder[]
     */
    protected array $menu = [];

    /**
     * Initializing the Menu manager
     */
    public function __construct()
    {
        // creating a collection for storing menu builders
        $this->collection = new IlluminateCollection();
    }

    /**
     * Check if a menu builder exists.
     *
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool
    {
        return Arr::exists($this->menu, $name);
    }

    /**
     * Create a new menu builder instance.
     *
     * @param string   $name
     * @param callable $callback
     * @return Builder
     */
    public function makeOnce(string $name, callable $callback): ?Builder
    {
        if ($this->exists($name)) {
            return null;
        }

        return $this->make($name, $callback);
    }

    /**
     * Create a new menu builder instance.
     *
     * @param string   $name
     * @param callable $callback
     * @param array $options (optional, it will be combined with the options to be applied)
     * @return Builder
     */
    public function make(string $name, callable $callback, array $options = []): ?Builder
    {
        if (!is_callable($callback)) {
            return null;
        }

        if (!Arr::exists($this->menu, $name)) {
            $this->menu[$name] = new Builder($name, array_merge($this->loadConf($name), $options));
        }

        // Registering the items
        $callback($this->menu[$name]);

        // Storing each menu instance in the collection
        $this->collection->put($name, $this->menu[$name]);

        // Make the instance available in all views
        View::share($name, $this->menu[$name]);

        return $this->menu[$name];
    }

    /**
     * Loads and merges menu configuration data.
     *
     * @param string $name
     * @return array
     */
    public function loadConf(string $name): array
    {
        $options = config('laravel-menu.menus');
        $name = strtolower($name);

        if (isset($options[$name]) && is_array($options[$name])) {
            return array_merge($options['default'], $options[$name]);
        }

        return $options['default'];
    }

    /**
     * Return Menu builder instance from the collection by key.
     *
     * @param string $key
     * @return Builder
     */
    public function get(string $key): Builder
    {
        return $this->collection->get($key);
    }

    /**
     * Return Menu builder collection.
     *
     * @return IlluminateCollection
     */
    public function getCollection(): IlluminateCollection
    {
        return $this->collection;
    }

    /**
     * Alias for getCollection.
     *
     * @return IlluminateCollection
     */
    public function all(): IlluminateCollection
    {
        return $this->collection;
    }
}
