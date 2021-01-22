<?php

namespace Mertasan\Menu;

use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * Add attributes to a collection of items.
     *
     * @param  mixed
     *
     * @return Collection
     */
    public function attr(): Collection
    {
        $args = func_get_args();

        $this->each(function ($item) use ($args) {
            if (count($args) >= 2) {
                $item->attr($args[0], $args[1]);
            } else {
                $item->attr($args[0]);
            }
        });

        return $this;
    }

    /**
     * Add meta data to a collection of items.
     *
     * @param  mixed
     *
     * @return Collection
     */
    public function data(): Collection
    {
        $args = func_get_args();

        $this->each(function ($item) use ($args) {
            if (count($args) >= 2) {
                $item->data($args[0], $args[1]);
            } else {
                $item->data($args[0]);
            }
        });

        return $this;
    }

    /**
     * Appends text or HTML to a collection of items.
     *
     * @param mixed $html
     *
     * @return Collection
     */
    public function append($html): Collection
    {
        $this->each(function ($item) use ($html) {
            $item->title .= $html;
        });

        return $this;
    }

    /**
     * Prepends text or HTML to a collection of items.
     *
     * @param mixed $html
     * @param mixed $key
     *
     * @return Collection
     */
    public function prepend($html, $key = null): Collection
    {
        $this->each(function ($item) use ($html) {
            $item->title = $html.$item->title;
        });

        return $this;
    }
}
