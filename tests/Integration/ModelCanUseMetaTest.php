<?php

namespace Vkovic\LaravelModelMeta\Test\Integration;

use Vkovic\LaravelModelMeta\Models\Meta;
use Vkovic\LaravelModelMeta\Test\Support\Models\User;
use Vkovic\LaravelModelMeta\Test\TestCase;

class ModelCanUseMetaTest extends TestCase
{
    /**
     * Valid data provider for: key, value and type
     *
     * @return array
     */
    public function keyValueTypeProvider()
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
     */
    public function it_purges_meta_on_model_deletion()
    {
        $user = factory(User::class)->create();

        $user->setMeta('foo', 'bar');
        $user->setMeta('bar', 'baz');

        $user->delete();

        $this->assertDatabaseMissing('meta', [
            'key' => 'foo', 'value' => 'bar',
            'key' => 'bar', 'value' => 'baz',
        ]);
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_set_and_get_meta($key, $value, $type = null)
    {
        $user = factory(User::class)->create();

        if ($type === null) {
            $user->setMeta($key, $value);
        } else {
            $user->setMeta($key, $value, $type);
        }

        $this->assertSame($user->getMeta($key), $value);
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_create_meta($key, $value, $type = null)
    {
        $user = factory(User::class)->create();

        if ($type === null) {
            $user->setMeta($key, $value);
            $user->updateMeta($key, $value);
        } else {
            $user->setMeta($key, $value, $type);
            $user->updateMeta($key, $value, $type);
        }

        $this->assertSame($user->getMeta($key), $value);
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_update_meta($key, $value, $type = null)
    {
        $user = factory(User::class)->create();

        if ($type === null) {
            $user->setMeta($key, $value);
            $user->updateMeta($key, $value);
        } else {
            $user->setMeta($key, $value, $type);
            $user->updateMeta($key, $value, $type);
        }

        $this->assertSame($user->getMeta($key), $value);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_updating_non_existing_meta()
    {
        $this->expectExceptionMessage("Can't update");

        $user = factory(User::class)->create();

        $user->updateMeta('unexistingKey', '');
    }

    /**
     * @test
     */
    public function it_throws_exception_when_creating_same_meta()
    {
        $this->expectExceptionMessage("Can't create");

        $user = factory(User::class)->create();

        $user->setMeta('foo', 'bar');

        $user->createMeta('foo', '');
    }

    /**
     * @test
     */
    public function it_will_return_default_value_when_key_not_exist()
    {
        $user = factory(User::class)->create();

        $default = str_random();

        $this->assertEquals($default, $user->getMeta('nonExistingKey', $default));
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_check_meta_exists($key, $value)
    {
        $user = factory(User::class)->create();

        $user->setMeta($key, $value);

        $this->assertTrue($user->metaExists($key));
        $this->assertFalse($user->metaExists(str_random()));
    }

    /**
     * @test
     * @group qwe
     */
    public function it_can_count_meta()
    {
        \DB::table((new Meta)->getTable())->truncate();

        //
        // Check zero count
        //

        $user = factory(User::class)->create();

        $this->assertTrue($user->countMeta() === 0);

        //
        // Check count in default realm
        //

        $count = rand(0, 10);
        for ($i = 0; $i < $count; $i++) {
            $key = str_random();
            $value = str_random();
            $user->setMeta($key, $value);
        }

        $this->assertTrue($user->countMeta() === $count);
    }

    /**
     * @test
     */
    public function it_can_get_all_meta()
    {
        \DB::table((new Meta)->getTable())->truncate();

        $user = factory(User::class)->create();

        $key1 = str_random();
        $value1 = str_random();
        $user->setMeta($key1, $value1);

        $key2 = str_random();
        $value2 = str_random();
        $user->setMeta($key2, $value2);

        $this->assertEquals([
            $key1 => $value1,
            $key2 => $value2,
        ], $user->allMeta());
    }


    /**
     * @test
     */
    public function it_can_get_all_keys()
    {
        \DB::table((new Meta)->getTable())->truncate();

        $user = factory(User::class)->create();

        $count = rand(0, 10);

        if ($count === 0) {
            $this->assertEmpty($user->metaKeys());
        }

        $keysToSave = [];
        for ($i = 0; $i < $count; $i++) {
            $key = str_random();
            $keysToSave[] = $key;

            $user->setMeta($key, '');
        }

        $metaKeys = $user->metaKeys();

        foreach ($keysToSave as $keyToSave) {
            $this->assertContains($keyToSave, $metaKeys);
        }
    }

    /**
     * @test
     */
    public function it_can_remove_meta_by_key()
    {
        \DB::table((new Meta)->getTable())->truncate();

        $user = factory(User::class)->create();

        $key = str_random();
        $value = str_random();

        $user->setMeta($key, $value);
        $user->removeMeta($key);

        $this->assertEmpty($user->allMeta());
    }

    /**
     * @test
     */
    public function it_can_purge_meta()
    {
        $user = factory(User::class)->create();

        $count = rand(0, 10);
        for ($i = 0; $i < $count; $i++) {
            $key = str_random();
            $value = str_random();
            $user->setMeta($key, $value);
        }

        $user->purgeMeta();

        $this->assertEmpty($user->allMeta());
    }
}
