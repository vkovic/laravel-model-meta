<?php

namespace Vkovic\LaravelModelMeta\Models;

class Meta extends \Vkovic\LaravelMeta\Models\Meta
{
    /**
     * Metable relation
     *
     * @return MorphTo
     */
    public function metable()
    {
        return $this->morphTo();
    }
}