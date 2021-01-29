<?php

namespace Mertasan\Menu;

class Dropdown extends Item {

    public bool $isDropdown = true;
    public ?string $dropdownType = null;

    /**
     * Dropdown constructor.
     *
     * @param \Mertasan\Menu\Builder $builder
     * @param                        $id
     * @param string                 $title
     * @param                        $options
     */
    public function __construct (Builder $builder, $id, string $title, $options)
    {
        parent::__construct($builder, $id, $title, $options);
        $this->builder = $builder;
        $this->dropdownType = $this->dropdownType ?: $this->builder->conf('dropdown_defaults.link_type', 'button');
        $this->attributes = $this->builder->extractAttributes(array_merge($this->builder->conf('dropdown_defaults.item_attributes', []), $options));
        if (!is_null($this->link)) {
            $this->link->attributes = array_merge($this->builder->conf('dropdown_defaults.link_attributes', []), $this->link->attributes);
        }
    }

    /**
     * Set dropdown link [HTML Element] type
     *
     * @param string $linkType
     * @return Dropdown
     */
    public function type(string $linkType): Dropdown
    {
        $dropdownType = str_replace(['href', 'link'], 'a', $linkType);

        $this->dropdownType = $dropdownType;

        return $this;
    }

}
