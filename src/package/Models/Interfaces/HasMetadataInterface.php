<?php

namespace Vkovic\LaravelModelMeta\Models\Interfaces;

interface HasMetadataInterface
{
    /**
     * Set meta at given key
     * related to this model (via metable)
     * for package realm.
     * If meta exists, it'll be overwritten.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setMeta($key, $value);

    /**
     * Create meta at given key
     * related to this model (via metable)
     * for package realm.
     * If meta exists, exception will be thrown.
     *
     * @param string $key
     * @param mixed $value
     *
     * @throws \Exception
     */
    public function createMeta($key, $value);

    /**
     * Update meta at given key
     * related to this model (via metable)
     * for package realm.
     * If meta doesn't exists, exception will be thrown.
     *
     * @param string $key
     * @param mixed $value
     *
     * @throws \Exception
     */
    public function updateMeta($key, $value);

    /**
     * Get meta at given key
     * related to this model (via metable)
     * for package realm
     *
     * @param string $key
     * @param mixed $default
     *
     * @return array
     */
    public function getMeta($key, $default = null);

    /**
     * Check if meta key record exists by given key
     * related to this model (via metable)
     * for package realm
     *
     * @param string $key
     *
     * @return bool
     */
    public function metaExists($key);

    /**
     * Count all meta
     * related to this model (via metable)
     * for package realm
     *
     * @param string $realm
     *
     * @return int
     */
    public function countMeta();

    /**
     * Get all meta
     * related to this model (via metable)
     * for package realm
     *
     * @return array
     */
    public function allMeta();

    /**
     * Get all meta keys
     * related to this model (via metable)
     * for package realm
     *
     * @return array
     */
    public function metaKeys();

    /**
     * Remove meta at given key or array of keys
     * related to this model (via metable)
     * for package realm
     *
     * @param string|array $key
     */
    public function removeMeta($key);

    /**
     * Purge meta
     * related to this model (via metable)
     * for package realm
     *
     * @return int Number of records deleted
     */
    public function purgeMeta();
}