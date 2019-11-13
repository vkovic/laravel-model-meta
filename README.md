# Laravel Model Meta

[![Build](https://api.travis-ci.org/vkovic/laravel-model-meta.svg?branch=master)](https://travis-ci.org/vkovic/laravel-model-meta)
[![Downloads](https://poser.pugx.org/vkovic/laravel-model-meta/downloads)](https://packagist.org/packages/vkovic/laravel-model-meta)
[![Stable](https://poser.pugx.org/vkovic/laravel-model-meta/v/stable)](https://packagist.org/packages/vkovic/laravel-model-meta)
[![License](https://poser.pugx.org/vkovic/laravel-model-meta/license)](https://packagist.org/packages/vkovic/laravel-model-meta)

### Laravel Model meta storage

Easily store and access model related metadata and avoid cluttering your models table with more fields.

---

## Compatibility

The package is compatible with **Laravel** versions `5.5`, `5.6`, `5.7`, `5.8` and `6`.

## Installation

Install the package via composer:

```bash
composer require vkovic/laravel-model-meta
```

Run migrations to create table which will be used to store our model metadata:

```bash
php artisan migrate
```

## Simple examples

To be able to write metadata from our model object, we'll need to add trait `HasMetadata`
to the model we want to associate metadata with.
Let's take Laravel default `User` model as an example:

```php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Vkovic\LaravelModelMeta\Models\Traits\HasMetadata;

class User extends Authenticatable
{
    use Notifiable, HasMetadata; // <= trait is added here

    // ...
}
```

Also lets assume that we already have users in our users table.
We'll need a random one for the examples below:

```php
$user = User::inRandomOrder()->first();
```

Let's create and retrieve some metadata for fetched user:

```php
// Set meta value as string
$user->setMeta('foo', 'bar');

// Get meta value
$user->getMeta('foo'); // : 'bar'

// In case there is no metadata found for given key,
// we can pass default value to return
$user->getMeta('baz', 'default'); // : 'default'
```

Beside string, metadata can also be stored as integer, float, null, boolean or array:

```php
$user->setMeta('age', 35);
$user->setMeta('temperature', 24.7);
$user->setMeta('value', null);
$user->setMeta('employed', true);
$user->setMeta('fruits', ['orange', 'apple']);

$user->getMeta('age'); // : 35
$user->getMeta('temperature'); // : 24.7
$user->getMeta('value'); // : null
$user->getMeta('employed'); // : true
$user->getMeta('fruits'); // : ['orange', 'apple']
```

We can easily check if related user meta exists without actually retrieving it from meta table:

```php
$user->setMeta('foo', 'bar');

$user->metaExists('foo'); // : true
```

Counting all related user meta records is also a breeze:

```php
$user->setMeta('a', 'one');
$user->setMeta('b', 'two');

$user->countMeta(); // : 2
```

If we need all user metadata, or just keys, no problem:

```php
$user->setMeta('a', 'one');
$user->setMeta('b', 'two');
$user->setMeta('c', 'three');

// Get all metadata
$user->allMeta(); // : ['a' => 'one', 'b' => 'two', 'c' => 'three']

// Get only keys
$user->metaKeys(); // : [0 => 'a', 1 => 'b', 2 => 'c']
```

Also, we can remove all user meta data easily:

```php
$user->setMeta('a', 'one');
$user->setMeta('b', 'two');
$user->setMeta('c', 'three');

// Remove meta by key
$user->removeMeta('a');

// Or array of keys
$user->removeMeta(['b', 'c']);
```

If, for some reason, we want to delete all meta related to this user at once, no problem:

```php
$user->purgeMeta();
```

## Retrieve models through meta scopes

`HasMetadata` trait also provides functionality to filter models with specific meta,
let's take a look at examples below:

```php
$user->setMeta('age', 35);

// Equals operator
User::whereMeta('age', '=', 35)->get();
// or shorther
User::whereMeta('age', 35)->get();

// Comparison operators
User::whereMeta('age', '>', 18)->get();
User::whereMeta('age', '!=', 20)->get();
// or with other comparison operators (<, <=, >, >=, =, <>, !=)

// All of the examples above will return Collection of users which meet's criteria,
// in this case our $user
```

Beside filtering users against meta value, we can also perform filtering based on meta key:

```php
$user->setMeta('company', 'Acme');
$anotherUser->setMeta('role', 'admin');

// Meta key
User::whereHasMetaKey('manager')->get();

// Array of keys
User::whereHasMetaKey(['company', 'role'])->get();

// All of the examples above will return Collection of users which meet's criteria,
// in this case our $user and $anotherUser
```

## Check if model has metadata

If you need to check if model has metadata functionality, you can implement an interface that comes with the package
like: 

```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Vkovic\LaravelModelMeta\Models\Interfaces\HasMetadataInterface;

class User extends Authenticatable implements HasMetadataInterface // <= interface is added here
{
    // ...
}
```

Implementing `HasMetadataInterface` gives us possibility to check if our model has metadata functionality implemented. 

```php
if ($model instanceof HasMetadataInterface) {
    // ... do something
}
```

---

## Contributing

If you plan to modify this Laravel package you should run tests that comes with it.
Easiest way to accomplish this would be with `Docker`, `docker-compose` and `phpunit`.

First, we need to initialize Docker containers:

```bash
docker-compose up -d
```

After that, we can run tests and watch the output:

```bash
docker-compose exec app vendor/bin/phpunit
```

---

## Similar packages

The package is one of three metadata packages based on the same approach:
- vkovic/laravel-model-meta (this package - Laravel model related meta storage)
- [vkovic/laravel-meta](https://github.com/vkovic/laravel-meta) (general purpose meta storage)
- [vkovic/laravel-settings](https://github.com/vkovic/laravel-settings) (app specific settings meta storage)

Packages can be used separately or together. Internally they are using same table and share common logic.