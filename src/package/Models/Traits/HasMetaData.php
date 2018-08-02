<?php

namespace Movor\LaravelModelMeta\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Movor\LaravelMeta\Meta;

trait HasMetaData
{
    /**
     * Initialize the trait
     *
     * @return void
     */
    public static function bootHasMetaData()
    {
        // Delete related meta on model deletion
        static::deleted(function (Model $model) {
            $model->purgeMeta();
        });
    }

    /**
     * Morph many relation
     *
     * @return MorphMany
     */
    public function meta()
    {
        return $this->morphMany(Meta::class, 'metable');
    }

    /**
     * Set meta at given key
     *
     * @param $key
     * @param $value
     *
     */
    public function setMeta($key, $value)
    {
        $this->meta()->updateOrCreate([
            'realm' => $this->getMetaIdentifier()[0],
            'key' => $key,
        ], ['key' => $key, 'value' => $value]);
    }

    /**
     * Get meta at given key
     *
     * @param $key
     *
     * @return mixed
     */
    public function getMeta($key, $default = null)
    {
        $meta = $this->meta()->where('key', $key)->first();

        return optional($meta)->value ?: $default;
    }

    /**
     * Get all meta
     *
     * @return array
     */
    public function getAllMeta()
    {
        return $this->meta()->pluck('value', 'key')->toArray();
    }

    /**
     * Remove meta
     *
     * @param $key
     */
    public function removeMeta($key)
    {
        $this->meta()->where('key', $key)->delete();
    }

    /**
     * Purge meta
     */
    public function purgeMeta()
    {
        $this->meta()->delete();
    }

    /**
     * Check if meta key exists
     *
     * @param $key
     *
     * @return bool
     */
    public function hasMetaKey($key)
    {
        return $this->meta()->where('key', $key)->exists();
    }

    /**
     * Filter all models which has given meta key value pair
     *
     * @param Builder $query
     * @param         $key
     * @param         $operator
     * @param null    $value
     *
     * @return Builder|static
     */
    public function scopeWhereMeta(Builder $query, $key, $operator, $value = null)
    {
        // If there is no value, it means operator is value
        if (!isset($value)) {
            $value = $operator;
            $operator = '=';
        }

        return $query->whereHas('meta', function (Builder $q) use ($key, $operator, $value) {
            list($realm, $metableType) = $this->getMetaIdentifier();

            $q->where([
                'realm' => $realm,
                'metable_type' => $metableType,
                'key' => $key
            ])->where('value', $operator, $value);
        });
    }

    /**
     * Filter all models which meta contains given key
     *
     * @param Builder      $query
     * @param array|string $key
     *
     * @return Builder|static
     */
    public function scopeWhereHasMetaKey(Builder $query, $key)
    {
        return $query->whereHas('meta', function (Builder $q) use ($key) {
            list($realm, $metableType) = $this->getMetaIdentifier();

            $q->where([
                'realm' => $realm,
                'metable_type' => $metableType,
            ])->whereIn('key', (array) $key);
        });
    }

    /**
     * Get unique identifier for laravel model in meta table
     *
     * @return array
     */
    protected function getMetaIdentifier()
    {
        return [
            'laravel-model',
            get_class($this),
            $this->getKey()
        ];
    }
}