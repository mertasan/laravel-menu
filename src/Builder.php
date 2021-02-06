<?php

namespace Mertasan\Menu;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use Mertasan\Menu\Helpers\Helpers;

/**
 * @method mixed whereNickname(string $nickname);
 * @method mixed whereId(mixed $id);
 * @method mixed whereActive(bool $status);
 * @method mixed whereParent(mixed $parent = null, bool $cond = false);
 * @method mixed hasProperty(string $attribute);
 */
class Builder
{
    /**
     * The items container.
     *
     * @var Collection
     */
    protected Collection $items;

    /**
     * The Menu name.
     *
     * @var string
     */
    protected string $name;

    /**
     * The Menu configuration data.
     *
     * @var array
     */
    protected array $conf;

    /**
     * The route group attribute stack.
     *
     * @var array
     */
    protected array $groupStack = [];

    /**
     * The reserved attributes.
     *
     * @var array
     */
    protected array $reserved = ['route', 'action', 'url', 'prefix', 'parent', 'secure', 'raw'];

    protected Helpers $helpers;

    protected array $config;

    /**
     * Initializing the menu manager.
     *
     * @param string $name
     * @param array  $config
     * @param array  $conf
     */
    public function __construct(string $name, array $config, array $conf)
    {
        $this->name = $name;
        $this->config = $config;
        // creating a laravel collection for storing menu items
        $this->items = new Collection();

        $this->conf = $conf;
        $this->helpers = new Helpers;
    }

    /**
     * Adds an item to the menu.
     *
     * @param string       $title
     * @param string|array $options
     * @return Item
     */
    public function add(string $title, $options = ''): Item
    {
        $id = $options['id'] ?? $this->id();

        $item = new Item($this, $id, $title, $options);

        $this->items->push($item);

        return $item;
    }

    public function getHelpers (): Helpers
    {
        return $this->helpers;
    }

    /**
     * Generate an integer identifier for each new item.
     *
     * @return string
     */
    protected function id(): string
    {
        // Issue #170: Use more_entropy otherwise usleep(1) is called.
        // Issue #197: The ID was not a viable document element ID value due to the period.
        return str_replace('.', '', uniqid('id-', true));
    }

    /**
     * Add raw content.
     *
     * @param string $title
     * @param array $options
     *
     * @return Item
     */
    public function raw(string $title, array $options = []): Item
    {
        $options['raw'] = true;

        return $this->add($title, $options);
    }

    /**
     * Returns menu item by name.
     *
     * @param string $title
     * @return Item|null
     */
    public function get(string $title): ?Item
    {
        return $this->whereNickname($title)->first();
    }

    /**
     * Returns menu item by Id.
     *
     * @param mixed $id
     * @return Item|null
     */
    public function find($id): ?Item
    {
        return $this->whereId($id)->first();
    }

    /**
     * Return all items in the collection.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->items;
    }

    /**
     * Return the first item in the collection.
     *
     * @return Item|null
     */
    public function first(): ?Item
    {
        return $this->items->first();
    }

    /**
     * Return the last item in the collection.
     *
     * @return Item|null
     */
    public function last(): ?Item
    {
        return $this->items->last();
    }

    /**
     * Returns menu item by name.
     *
     * @param string $title
     * @return Item|null
     */
    public function item(string $title): ?Item
    {
        return $this->whereNickname($title)->first();
    }

    /**
     * @param mixed $parentID
     * @return int
     */
    public function findLevel($parentID): int
    {
        return $this->whereId($parentID)->first()->getLevel() + 1;
    }

    /**
     * Returns the first item marked as active.
     *
     * @return Item|null
     */
    public function active(): ?Item
    {
        return $this->whereActive(true)->first();
    }

    /**
     * Insert a separator after the item.
     *
     * @param array $attributes
     */
    public function divide(array $attributes = []): void
    {
        $attributes['class'] = self::formatGroupClass(array('class' => 'divider'), $attributes);

        $this->items->last()->divider = $attributes;
    }

    /**
     * Create a menu group with shared attributes.
     *
     * @param array    $attributes
     * @param callable $closure
     */
    public function group(array $attributes, callable $closure): void
    {
        $this->updateGroupStack($attributes);

        // Once we have updated the group stack, we will execute the user Closure and
        // merge in the groups attributes when the item is created. After we have
        // run the callback, we will pop the attributes off of this group stack.
        $closure($this);

        array_pop($this->groupStack);
    }

    /**
     * Create a menu dropdown
     *
     * @param string         $title
     * @param array|callable $optionsOrClosure
     * @param callable|null  $closure
     * @return \Mertasan\Menu\Dropdown
     */
    public function dropdown(string $title, $optionsOrClosure, $closure = null): Dropdown
    {
        if (is_callable($optionsOrClosure)) {
            $options = is_array($closure) ? $closure : [];
            $closure = $optionsOrClosure;
        } else {
            $options = $optionsOrClosure;
        }
        $id = $options['id'] ?? $this->id();

        $item = new Dropdown($this, $id, $title, $options);

        $this->items->push($item);
        $this->updateGroupStack($options);
        $closure($item);
        array_pop($this->groupStack);

        return $item;
    }

    /**
     * Update the group stack with the given attributes.
     *
     * @param array $attributes
     */
    protected function updateGroupStack(array $attributes = []): void
    {
        if (count($this->groupStack) > 0) {
            $attributes = $this->mergeWithLastGroup($attributes);
        }

        $this->groupStack[] = $attributes;
    }

    /**
     * Merge the given array with the last group stack.
     *
     * @param array $new
     * @return array
     */
    protected function mergeWithLastGroup(array $new): array
    {
        return self::mergeGroup($new, last($this->groupStack));
    }

    /**
     * Merge the given group attributes.
     *
     * @param array $new
     * @param array $old
     * @return array
     */
    protected static function mergeGroup(array $new, $old): array
    {
        $new['prefix'] = self::formatGroupPrefix($new, $old);

        $new['class'] = self::formatGroupClass($new, $old);

        return array_merge(Arr::except($old, array('prefix', 'class')), $new);
    }

    /**
     * Format the prefix for the new group attributes.
     *
     * @param array $new
     * @param array $old
     * @return string|null
     */
    public static function formatGroupPrefix(array $new, array $old): ?string
    {
        if (isset($new['prefix'])) {
            return trim(Arr::get($old, 'prefix'), '/').'/'.trim($new['prefix'], '/');
        }

        return Arr::get($old, 'prefix');
    }

    /**
     * Get the prefix from the last group on the stack.
     *
     * @return string
     */
    public function getLastGroupPrefix(): ?string
    {
        if (count($this->groupStack) > 0) {
            return Arr::get(last($this->groupStack), 'prefix', '');
        }

        return null;
    }

    /**
     * Prefix the given URI with the last prefix.
     *
     * @param string $uri
     * @return string
     */
    protected function prefix(string $uri): string
    {
        return trim(trim($this->getLastGroupPrefix(), '/').'/'.trim($uri, '/'), '/') ?: '/';
    }

    /**
     * Get the valid attributes from the options.
     *
     * @param array $new
     * @param array $old
     * @return string|null
     */
    public static function formatGroupClass(array $new, array $old): ?string
    {
        if (isset($new['class']) && $new['class'] !== null) {
            $classes = trim(trim(Arr::get($old, 'class')).' '.trim(Arr::get($new, 'class')));

            return implode(' ', array_unique(explode(' ', $classes)));
        }

        return Arr::get($old, 'class');
    }

    /**
     * Get the valid attributes from the options.
     *
     * @param array $options
     *
     * @return array
     */
    public function extractAttributes($options = []): array
    {
        if (!is_array($options)) {
            $options = [];
        }

        if (count($this->groupStack) > 0) {
            $options = $this->mergeWithLastGroup($options);
        }

        return Arr::except($options, $this->reserved);
    }

    /**
     * Get the form action from the options.
     *
     * @param $options
     * @return string
     */
    public function dispatch($options): ?string
    {
        // We will also check for a "route" or "action" parameter on the array so that
        // developers can easily specify a route or controller action when creating the
        // menus.
        if (isset($options['url'])) {
            return $this->getUrl($options);
        }

        if (isset($options['route'])) {
            return $this->getRoute($options['route']);
        }

        if (isset($options['action'])) {
            return $this->getControllerAction($options['action']);
        }

        // If an action is available, we are attempting to point the link to controller
        // action route. So, we will use the URL generator to get the path to these
        // actions and return them from the method. Otherwise, we'll use current.

        return null;
    }

    /**
     * Get the action for a "url" option.
     *
     * @param array|string $options
     *
     * @return string
     */
    protected function getUrl($options): string
    {
        foreach ($options as $key => $value) {
            $$key = $value;
        }

        $secure = null;
        if (isset($options['secure'])) {
            $secure = true === $options['secure'];
        }

        /**
         * @var mixed $url
         * @var string $prefix
         */
        if (is_array($url)) {
            if (self::isAbs($url[0])) {
                return $url[0];
            }

            return URL::to($prefix.'/'.$url[0], array_slice($url, 1), $secure);
        }

        if (self::isAbs($url)) {
            return $url;
        }

        return URL::to($prefix.'/'.$url, [], $secure);
    }

    /**
     * Check if the given url is an absolute url.
     *
     * @param string $url
     * @return bool
     */
    public static function isAbs(string $url): bool
    {
        return parse_url($url, PHP_URL_SCHEME) ?: false;
    }

    /**
     * Get the action for a "route" option.
     *
     * @param array|string $options
     *
     * @return string
     */
    protected function getRoute($options): string
    {
        if (is_array($options)) {
            return URL::route($options[0], array_slice($options, 1));
        }

        return URL::route($options);
    }

    /**
     * Get the action for an "action" option.
     *
     * @param array|string $options
     *
     * @return string
     */
    protected function getControllerAction($options): string
    {
        if (is_array($options)) {
            return URL::action($options[0], array_slice($options, 1));
        }

        return URL::action($options);
    }

    /**
     * Returns items with no parent.
     *
     * @return \Illuminate\Support\Collection|\Mertasan\Menu\Builder
     */
    public function roots()
    {
        return $this->whereParent();
    }

    /**
     * Filter menu items by user callbacks.
     *
     * @param callable $callback
     * @return Builder
     */
    public function filter(callable $callback): Builder
    {
        if (is_callable($callback)) {
            $this->items = $this->items->filter($callback);
        }

        return $this;
    }

    /**
     * Sorts the menu based on user's callable.
     *
     * @param mixed           $sort_by
     * @param string|callable $sort_type
     * @return Builder
     */
    public function sortBy($sort_by, $sort_type = 'asc'): Builder
    {
        if (is_callable($sort_by)) {
            $rslt = $sort_by($this->items->toArray());

            if (!is_array($rslt)) {
                $rslt = array($rslt);
            }

            $this->items = new Collection($rslt);
            return $this;
        }

        // running the sort process on the sortable items
        $this->items = $this->items->sort(function ($f, $s) use ($sort_by, $sort_type) {
            $f = $f->$sort_by;
            $s = $s->$sort_by;

            if ($f === $s) {
                return 0;
            }

            if ('asc' === $sort_type) {
                return $f > $s ? 1 : -1;
            }

            return $f < $s ? 1 : -1;
        });

        return $this;
    }

    /**
     * Creates a new Builder instance with the given name and collection.
     *
     * @param string $name
     * @param Collection $collection
     *
     * @return Builder
     */
    public function spawn(string $name, Collection $collection): Builder
    {
        $nb = new self($name, $this->conf);
        $nb->takeCollection($collection);

        return $nb;
    }

    /**
     * Takes an entire collection and stores it as the items.
     *
     * @param Collection $collection
     */
    public function takeCollection(Collection $collection): void
    {
        $this->items = $collection;
    }

    /**
     * Returns a new builder of just the top level menu items.
     *
     * @return Builder
     */
    public function topMenu(): Builder
    {
        /** @var Collection|\Mertasan\Menu\Builder $roots */
        $roots = $this->roots();
        return $this->spawn('topLevel', $roots);
    }

    /**
     * Returns a new builder with the active items children.
     *
     * @return Builder
     */
    public function subMenu(): Builder
    {
        $nb = $this->spawn('subMenu', new Collection());

        $active = $this->active();
        $subs = $active ? $active->children() : [];
        foreach ($subs as $s) {
            $nb->add($s->title, $s->url());
        }

        return $nb;
    }

    /**
     * Returns a new builder with siblings of the active item.
     *
     * @return Builder
     */
    public function siblingMenu(): Builder
    {
        $nb = $this->spawn('siblingMenu', new Collection());

        $active = $this->active();
        $parent = $active ? $active->parent() : false;
        if ($parent) {
            $siblings = $parent->children();
        } else {
            $siblings = $this->roots();
        }

        if ($siblings->count() > 1) {
            foreach ($siblings as $s) {
                $nb->add($s->title, $s->url());
            }
        }

        return $nb;
    }

    /**
     * Returns a new builder with all of the parents of the active item.
     *
     * @return Builder
     */
    public function crumbMenu(): Builder
    {
        $nb = $this->spawn('crumbMenu', new Collection());

        $item = $this->active();
        if ($item) {
            $items = [$item];
            while ($item->hasParent()) {
                $item = $item->parent();
                array_unshift($items, $item);
            }

            foreach ($items as $item) {
                $nb->add($item->title, $item->url());
            }
        }

        return $nb;
    }

    /**
     * Generate the menu items as list items using a recursive function.
     *
     * @param string   $type
     * @param mixed      $parent
     * @param array    $children_attributes
     * @param array    $item_attributes
     * @param callable $item_after_callback
     * @param array    $item_after_callback_params
     *
     * @return string
     */
    public function render($type = 'ul', $parent = null, $children_attributes = [], $item_attributes = [], $item_after_callback = null, $item_after_callback_params = []): string
    {
        $items = '';

        $item_tag = in_array($type, array('ul', 'ol')) ? 'li' : $type;

        foreach ($this->whereParent($parent) as $item) {

            if ($item->isAllowed() === false) {
                continue;
            }

            if ($item->isDropdown()) {
                // This is how array_merge in the loop is used for better performance.
                $childAttributesMerge = [$this->conf('dropdown_defaults.child_wrapper_attributes', []), $children_attributes];
                $children_attributes = array_merge([], ...$childAttributesMerge);
            }

            if ($item->link) {
                $link_attr = $item->link->attr();
                if (is_callable($item_after_callback)) {
                    call_user_func_array($item_after_callback, [
                        $item,
                        &$children_attributes,
                        &$item_attributes,
                        &$link_attr,
                        &$item_after_callback_params,
                    ]);
                }
            }
            if (!is_null($item->tag)){
                $item_tag = $item->tag;
            }

            $linkWrapper = true;
            if ($item->hasParent()) {
                $parentItem = $this->whereId($item->parent)->first();
                if (!$parentItem->isDropdown() || ($parentItem->isDropdown() && $this->conf('dropdown_defaults.child_link_wrapper') !== true)) {
                    $linkWrapper = false;
                }
            }

            if ($linkWrapper) {
                $all_attributes = array_merge($item_attributes, $item->attr()) ;
                if (!$item->isActive) {
                    $all_attributes['class'] = self::formatGroupClass(array('class' => $this->conf('inactive_class')), $all_attributes);
                }
                if (isset($item_attributes['class'])) {
                    $all_attributes['class'] .= ' ' . $item_attributes['class'];
                }
                $items .= '<'.$item_tag.self::attributes($all_attributes).'>';
            }

            if ($item->isDropdown()) {
                switch ($item->dropdownType) {
                    case 'button':
                        $items .= $item->beforeHTML.'<button type="button" '.self::attributes($link_attr ?? []).'>'.$this->getItemTitleWithIcons($item).'</button>'.$item->afterHTML;
                    break;
                    case 'a':
                        $items .= $item->beforeHTML.'<a '.self::attributes($link_attr ?? []).' href="#">'.$this->getItemTitleWithIcons($item).'</a>'.$item->afterHTML;
                    break;
                    default:
                        $items .= $item->beforeHTML.'<'.$item->dropdownTag.''.self::attributes($link_attr ?? []).'>'.$this->getItemTitleWithIcons($item).'</'.$item->dropdownTag.'>'.$item->afterHTML;
                    break;
                }

            } else {
                if ($item->link) {
                    $items .= $item->beforeHTML.'<a'.self::attributes($link_attr ?? []).(!empty($item->url()) ? ' href="'.$item->url().'"' : '').'>'.$this->getItemTitleWithIcons($item).'</a>'.$item->afterHTML;
                } else {
                    $items .= $this->getItemTitleWithIcons($item);
                }
            }

            if ($item->hasChildren()) {
                $items .= '<'.$type.self::attributes($children_attributes).'>';
                // Recursive call to children.
                $items .= $this->render($type, $item->id, $children_attributes, $item_attributes, $item_after_callback, $item_after_callback_params);
                $items .= "</{$type}>";
            }

            if ($linkWrapper) {
                $items .= "</{$item_tag}>";
            }

            if ($item->divider) {
                $items .= '<'.$item_tag.self::attributes($item->divider).'></'.$item_tag.'>';
            }
        }

        return $items;
    }

    public function getItemTitleWithIcons($item): string
    {
        return $this->getItemIcon($item).$this->getItemSvg($item).$item->title.$this->getItemSvg($item, true).$this->getItemIcon($item, true);
    }

    public function getItemIcon($item, $isAppend = false): ?string
    {
        if (!$isAppend && !$item->hasBeforeIcon) {
            return null;
        }

        if ($isAppend && !$item->hasAfterIcon) {
            return null;
        }

        $active_icon_class = $this->conf('active_icon_class');
        $inactive_icon_class = $this->conf('inactive_icon_class');
        $icon = ($isAppend ? $item->afterIcon : $item->beforeIcon);

        if ($item->isActive) {
            $addClass = $active_icon_class;
        } else {
            $addClass = $inactive_icon_class;
        }

        if (!isset($icon['class'])) {
            $icon['class'] = null;
        }

        $icon['class'] = trim($icon['class'] . ' ' . $addClass);

        return '<i '.self::attributes($icon).'></i>';
    }

    public function getItemSvg($item, $isAppend = false): ?string
    {
        if (!$isAppend && !$item->beforeSvgPath) {
            return null;
        }

        if ($isAppend && !$item->afterSvgPath) {
            return null;
        }

        $active_svg_class = $this->conf('active_svg_class');
        $inactive_svg_class = $this->conf('inactive_svg_class');
        $svg = ($isAppend ? $item->afterSvg : $item->beforeSvg);
        $svgPath = ($isAppend ? $item->afterSvgPath : $item->beforeSvgPath);

        if ($item->isActive) {
            $addClass = $active_svg_class;
        } else {
            $addClass = $inactive_svg_class;
        }

        if (!isset($svg['class'])) {
            $svg['class'] = null;
        }

        $svg['class'] = trim($svg['class'] . ' ' . $addClass);

        try {
            $svg = app(\BladeUI\Icons\Factory::class)->svg($svgPath, '', $svg)->toHtml();
        } catch (\BladeUI\Icons\Exceptions\SvgNotFound $e) {
            $svg = null;
        }

        if (!is_null($svg)) {
            return $svg;
        }

        return null;
    }

    /**
     * Returns the menu as an unordered list.
     *
     * @param array    $attributes
     * @param array    $children_attributes
     * @param array    $item_attributes
     * @param callable $item_after_callback
     * @param array    $item_after_callback_params
     *
     * @return string
     */
    public function asUl($attributes = [], $children_attributes = [], $item_attributes = [], $item_after_callback = null, $item_after_callback_params = []): string
    {
        return '<ul'.self::attributes($attributes).'>'.$this->render('ul', null, $children_attributes, $item_attributes, $item_after_callback, $item_after_callback_params).'</ul>';
    }

    /**
     * Returns the menu as an ordered list.
     *
     * @param array    $attributes
     * @param array    $children_attributes
     * @param array    $item_attributes
     * @param callable $item_after_callback
     * @param array    $item_after_callback_params
     *
     * @return string
     */
    public function asOl($attributes = [], $children_attributes = [], $item_attributes = [], $item_after_callback = null, $item_after_callback_params = []): string
    {
        return '<ol'.self::attributes($attributes).'>'.$this->render('ol', null, $children_attributes, $item_attributes, $item_after_callback, $item_after_callback_params).'</ol>';
    }

    /**
     * Returns the menu as div containers.
     *
     * @param array    $attributes
     * @param array    $children_attributes
     * @param array    $item_attributes
     * @param callable $item_after_callback
     * @param array    $item_after_callback_params
     *
     * @return string
     */
    public function asDiv($attributes = [], $children_attributes = [], $item_attributes = [], $item_after_callback = null, $item_after_callback_params = []): string
    {
        return '<div'.self::attributes($attributes).'>'.$this->render('div', null, $children_attributes, $item_attributes, $item_after_callback, $item_after_callback_params).'</div>';
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param array $attributes
     *
     * @return string|null
     */
    public static function attributes(array $attributes): ?string
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            $element = self::attributeElement($key, $value);
            if (!is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' '.implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     *
     * @param string $key
     * @param string|null $value
     * @return string
     */
    protected static function attributeElement(string $key, ?string $value): ?string
    {
        if (is_numeric($key)) {
            $key = $value;
        }
        if (!is_null($value)) {
            return $key.'="'.e($value).'"';
        }

        return null;
    }

    /**
     * Return configuration value by key.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function conf(string $key, $default = null)
    {
        return data_get($this->conf, $key, $default);
    }

    /**
     * @param string|null $key
     * @param null        $default
     * @return mixed
     */
    public function getConfig (?string $key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->config;
        }

        return data_get($this->config, $key, $default);
    }

    /**
     * Add custom options
     * One-time special additions can be made to the options to be applied to the menu.
     *
     * @param array       $options
     * @param string|null $optionsFrom (optional, if you want to use the options of another
     *                                 menu instead of "default" options, enter another menu name.)
     * @return void
     */
    public function options(array $options, ?string $optionsFrom = 'default'): void
    {
        if ($optionsFrom === null) {
            $this->conf = $options;
        } else {
            $defaultOptions = $this->getConfig('menus');
            $name = strtolower($optionsFrom);
            $currentName = strtolower($this->name);
            $menuOptions = false;

            if ($name !== 'default' && isset($defaultOptions[$name]) && is_array($defaultOptions[$name])) {
                $menuOptions = $defaultOptions[$name];
            } else if (isset($defaultOptions[$currentName]) && is_array($defaultOptions[$currentName])) {
                $menuOptions = $defaultOptions[$currentName];
            }

            $this->conf = $this->helpers::arrayExtend($defaultOptions["default"], ($menuOptions ?: []), $options);
        }
    }

    /**
     * Merge item's attributes with a static string of attributes.
     *
     * @param null  $new
     * @param array $old
     *
     * @return string|null
     */
    public static function mergeStatic($new = null, array $old = []): ?string
    {
        // Parses the string into an associative array
        parse_str(preg_replace('/\s*([\w-]+)\s*=\s*"([^"]+)"/', '$1=$2&', $new), $attrs);

        // Merge classes
        $attrs['class'] = self::formatGroupClass($attrs, $old);

        // Merging new and old array and parse it as a string
        return self::attributes(array_merge(Arr::except($old, array('class')), $attrs));
    }

    /**
     * Filter items recursively.
     *
     * @param string $attribute
     * @param mixed  $value
     * @return Collection
     */
    public function filterRecursive(string $attribute, $value): Collection
    {
        $collection = new Collection();

        // Iterate over all the items in the main collection
        $this->items->each(function ($item) use ($attribute, $value, &$collection) {
            if (!$this->hasProperty($attribute)) {
                return false;
            }

            if ($item->$attribute === $value) {
                $collection->push($item);

                // Check if item has any children
                if ($item->hasChildren()) {
                    $collection = $collection->merge($this->filterRecursive($attribute, $item->id));
                }
            }
            return $collection;
        });

        return $collection;
    }

    /**
     * Search the menu based on an attribute.
     *
     * @param string $method
     * @param array  $args
     * @return bool|Builder|Collection
     */
    public function __call(string $method, array $args)
    {
        preg_match('/^[W|w]here([\w]+)$/', $method, $matches);

        if ($matches) {
            $attribute = strtolower($matches[1]);
        } else {
            return false;
        }

        $value = $args ? $args[0] : null;
        $recursive = $args[1] ?? false;

        if ($recursive) {
            return $this->filterRecursive($attribute, $value);
        }

        return $this->items->filter(function ($item) use ($attribute, $value) {
            if (!$item->hasProperty($attribute)) {
                return false;
            }

            if ($item->$attribute === $value) {
                return true;
            }

            return false;
        })->values();
    }

    /**
     * Returns menu item by name.
     *
     * @param $prop
     * @return Item
     */
    public function __get($prop)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        }

        return $this->whereNickname($prop)->first();
    }
}
