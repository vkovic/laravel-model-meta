<?php

namespace Vkovic\LaravelModelMeta\Test\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Vkovic\LaravelModelMeta\Models\Traits\HasMetaData;

class User extends Model
{
    use HasMetaData;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];
}