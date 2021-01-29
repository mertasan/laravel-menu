<?php

namespace Mertasan\Menu;

class Link
{
    /**
     * Reference to the menu builder.
     *
     * @var Builder | null
     */
    protected ?Builder $builder;

    /**
     * Path Information.
     *
     * @var array
     */
    protected array $path = array();

    /**
     * Explicit href for the link.
     *
     * @var string|null
     */
    protected ?string $href = null;

    /**
     * Link attributes.
     *
     * @var array
     */
    public array $attributes = array();

    /**
     * Flag for active state.
     *
     * @var bool
     */
    public bool $isActive = false;

    /**
     * Creates a hyper link instance.
     *
     * @param array                     $path
     * @param Builder|null $builder
     */
    public function __construct(array $path = array(), ?Builder $builder = null)
    {
        $this->path = $path;
        $this->builder = $builder;
    }

    /**
     * Make the anchor active.
     *
     * @return Link
     */
    public function active(): Link
    {
        $this->attributes['class'] = Builder::formatGroupClass(array('class' => $this->builder ? $this->builder->conf('active_class') : $this->builder->conf('inactive_class')), $this->attributes);
        $this->isActive = true;

        return $this;
    }


    /**
     * @return array
     */
    public function getPath(): array
    {
        return $this->path;
    }

    /**
     * Get Anchor's href property.
     *
     * @return string|null
     */
    public function getHref(): ?string
    {
        return $this->href ?? null;

    }

    /**
     * Set Anchor's href property.
     *
     * @param $href
     * @return Link
     */
    public function href($href): Link
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Make the url secure.
     *
     * @return Link
     */
    public function secure(): Link
    {
        $this->path['secure'] = true;

        return $this;
    }

    /***
     * Add attributes to the link.
     *
     * @param mixed
     * @return $this|array|mixed|null
     */
    public function attr()
    {
        $args = func_get_args();

        if(isset($args[0]) && is_array($args[0])) {
            $this->attributes = array_merge($this->attributes, $args[0]);

            return $this;
        }

        if(isset($args[0], $args[1])) {
            $this->attributes[$args[0]] = $args[1];

            return $this;
        }

        if (isset($args[0])) {
            return $this->attributes[$args[0]] ?? null;
        }

        return $this->attributes;
    }

    /***
     * Check for a method of the same name if the attribute doesn't exist.
     *
     * @param $prop
     * @return Link|string
     */
    public function __get($prop)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        }

        return $this->attr($prop);
    }
}
