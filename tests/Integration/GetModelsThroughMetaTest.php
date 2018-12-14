<?php

namespace Vkovic\LaravelModelMeta\Test\Integration;

use Vkovic\LaravelModelMeta\Test\Support\Models\User;
use Vkovic\LaravelModelMeta\Test\TestCase;

class GetModelsThroughMetaTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_get_models_by_meta_key()
    {
        $noOfUsers = rand(1, 10);
        $noOfAdmins = rand(1, 10);

        $users = factory(User::class, $noOfUsers)->create();
        $admins = factory(User::class, $noOfAdmins)->create();

        foreach ($users as $user) {
            $user->setMeta('foo', 'unused');
        }

        foreach ($admins as $admin) {
            $admin->setMeta('bar', 'unused');
        }

        $this->assertEquals($noOfUsers, User::whereHasMetaKey('foo')->count());
        $this->assertEquals($noOfAdmins, User::whereHasMetaKey('bar')->count());
    }
}
