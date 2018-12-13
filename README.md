# Laravel Model Meta

[![Build](https://api.travis-ci.org/vkovic/laravel-model-meta.svg?branch=master)](https://travis-ci.org/vkovic/laravel-model-meta)
[![Downloads](https://poser.pugx.org/vkovic/laravel-model-meta/downloads)](https://packagist.org/packages/vkovic/laravel-model-meta)
[![Stable](https://poser.pugx.org/vkovic/laravel-model-meta/v/stable)](https://packagist.org/packages/vkovic/laravel-model-meta)
[![License](https://poser.pugx.org/vkovic/laravel-model-meta/license)](https://packagist.org/packages/vkovic/laravel-model-meta)

### Laravel Model meta storage

Easily store and access model related metadata.

Avoid cluttering your models table with more fields. If you dont need them you can just unplug trait from your model
and delete related data.

---

## Compatibility

The package is compatible with Laravel versions `>= 5.5`

## Installation

Install the package via composer:

```bash
composer require vkovic/laravel-model-meta
```

The package needs to be registered in service providers:

```php
// File: config/app.php

// ...

/*
 * Package Service Providers...
 */

// ...

Vkovic\LaravelModelMeta\Providers\LaravelModelMetaServiceProvider::class,
```

Run migrations to create table which will be used to store our meta data:

```bash
php artisan migrate
```

> If you installed vkovic/laravel-meta previously, this package will use the same table, because logic is based on
> polymorphic relations and both packages are fully compatible, and you can use both simultaneously.

---
---
---

TODO ...