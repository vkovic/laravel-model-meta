<?php

namespace Vkovic\LaravelModelMeta\Test\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Vkovic\LaravelModelMeta\Models\Traits\HasMetadata;

class User extends Model
{
    use HasMetadata;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];
}