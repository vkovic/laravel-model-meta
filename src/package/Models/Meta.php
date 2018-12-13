<?php

namespace Vkovic\LaravelModelMeta\Models;

use Illuminate\Database\Eloquent\Builder;
use Vkovic\LaravelMeta\Models\Meta as VkovicLaravelMeta;

class Meta extends VkovicLaravelMeta
{
    protected static $realm = 'vkovic/laravel-model-meta';

    /**
     * Filter meta by metable
     *
     * @param Builder $query
     *
     * @return mixed
     */
    public function scopeMetable(Builder $query, $metableType, $metableId)
    {
        return $query->where([
            'metable_type' => $metableType,
            'metable_id' => $metableId
        ]);
    }
}