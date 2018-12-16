<?php

namespace Vkovic\LaravelModelMeta\Test\Integration;

use Vkovic\LaravelModelMeta\Test\Support\Models\User;
use Vkovic\LaravelModelMeta\Test\TestCase;

class GetModelsThroughMetaTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        // Add some related random db entries to avoid possible mistakes in further testing
        foreach (factory(User::class, rand(10, 100))->create() as $user) {
            $user->setMeta(str_random(), str_random());
        }
    }

    /**
     * Data provider for: key, value and type
     *
     * @return array
     */
    public function keyValueProvider()
    {
        return [
            // key | value | type
            [str_random(), str_random()],
            [str_random(), str_random(), 'string'],
            [str_random(), null],
            [str_random(), null, 'null'],
            [str_random(), 1, 'int'],
            [str_random(), 1.1, 'float'],
            [str_random(), true, 'boolean'],
            [str_random(), false, 'boolean'],
            [str_random(), []],
            [str_random(), [], 'array'],
            [str_random(), range(1, 10)],
            [str_random(), range(1, 10), 'array'],
        ];
    }

    /**
     * @test
     * @dataProvider keyValueProvider
     */
    public function it_can_get_models_by_key_and_value($key, $value, $type = null)
    {
        $user = factory(User::class)->create();

        if ($type == null) {
            $user->setMeta($key, $value);
        } else {
            $user->setMeta($key, $value, $type);
        }

        $this->assertEquals($user->id, User::whereMeta($key, $value)->first()->id);
    }

    /**
     * @test
     */
    public function it_can_get_models_by_key_and_value_using_comparison()
    {
        $totalUsers = rand(0, 100);
        $adults = $working = 0;

        $users = factory(User::class, $totalUsers)->create();

        foreach ($users as $user) {
            $age = rand(0, 100);
            $employed = (bool) rand(0, 1);

            if ($age >= 18) {
                $adults++;
            }

            if ($employed) {
                $working++;
            }

            $user->setMeta('employed', $employed);
            $user->setMeta('age', $age);
        }

        $withoutJobs = $totalUsers - $working;

        $this->assertEquals($withoutJobs, User::whereMeta('employed', '<>', true)->count());
        $this->assertEquals($withoutJobs, User::whereMeta('employed', '!=', true)->count());
        $this->assertEquals($adults, User::whereMeta('age', '>=', '18')->count());
        $this->assertEquals($adults, User::whereMeta('age', '>=', 18)->count());
    }

    /**
     * @test
     */
    public function it_can_get_models_by_meta_key_or_keys()
    {
        $totalUsers = rand(0, 100);
        $admins = $managers = 0;

        $users = factory(User::class, $totalUsers)->create();

        foreach ($users as $user) {
            if (rand(0, 1)) {
                $user->setMeta('admin', true);
                $admins++;
            } else {
                $user->setMeta('manager', true);
                $managers++;
            }
        }

        $this->assertEquals($totalUsers, User::whereHasMetaKey(['admin', 'manager'])->count());
        $this->assertEquals($admins, User::whereHasMetaKey('admin')->count());
        $this->assertEquals($managers, User::whereHasMetaKey('manager')->count());
    }

    /**
     * @test
     */
    public function it_throws_exception_on_wrong_operator()
    {
        $this->expectExceptionMessage('Invalid operator');

        User::whereMeta('foo', '><', 'bar');
    }
}
