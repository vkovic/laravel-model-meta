<?php

namespace Vkovic\LaravelModelMeta\Test\Unit;

use Vkovic\LaravelModelMeta\Models\Meta;
use Vkovic\LaravelModelMeta\Test\Support\Models\User;
use Vkovic\LaravelModelMeta\Test\TestCase;

class HasMetaDataTraitTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_test()
    {
        $user = new User;
        $user->name = 'Toronto - Belgrade';
        $user->email = 'Toronto - Belgrade';
        $user->password = 'Toronto - Belgrade';
        $user->save();

        $this->assertTrue(false);
    }
}
