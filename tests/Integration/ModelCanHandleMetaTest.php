<?php

namespace Vkovic\LaravelMeta\Test\Unit;

use Vkovic\LaravelModelMeta\Models\Meta;
use Vkovic\LaravelModelMeta\Test\Support\Models\User;
use Vkovic\LaravelModelMeta\Test\TestCase;

class MetaFacadeTest extends TestCase
{
    /**
     * Valid data provider for: key and value
     *
     * @return array
     */
    public function keyValueProvider()
    {
        return [
            // key | value
            [str_random(), str_random()],
            [str_random(), null],
            [str_random(), 1],
            [str_random(), 1.1],
            [str_random(), true],
            [str_random(), false],
            [str_random(), []],
            [str_random(), range(1, 10)],
        ];
    }

    /**
     * @test
     */
    public function it_saves_to_correct_realm()
    {
        $user = factory(User::class)->create();

        $user->setMeta('foo', '');

        $this->assertDatabaseHas('meta', [
            'realm' => Meta::getRealm()
        ]);
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
     * @dataProvider keyValueProvider
     */
    public function it_can_set_and_get_meta($key, $value)
    {
        $user = factory(User::class)->create();

        $user->setMeta($key, $value);

        $this->assertSame($user->getMeta($key), $value);
    }

    /**
     * @test
     * @dataProvider keyValueProvider
     */
    public function it_can_create_meta($key, $value)
    {
        $user = factory(User::class)->create();

        $user->createMeta($key, $value);

        $this->assertSame($user->getMeta($key), $value);
    }

    /**
     * @test
     * @dataProvider keyValueProvider
     */
    public function it_can_update_meta($key, $value)
    {
        $newValue = str_random();
        $user = factory(User::class)->create();

        $user->setMeta($key, $value);
        $user->updateMeta($key, $newValue);

        $this->assertSame($user->getMeta($key), $newValue);
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
        $default = str_random();

        $user = factory(User::class)->create();

        $this->assertEquals($default, $user->getMeta('nonExistingKey', $default));
    }

    /**
     * @test
     * @dataProvider keyValueProvider
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
     */
    public function it_can_count_meta()
    {
        $user = factory(User::class)->create();

        // Check zero count
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
        $user = factory(User::class)->create();

        $key1 = str_random();
        $value1 = str_random();
        $user->setMeta($key1, $value1);

        $key2 = str_random();
        $value2 = range(0, 10);
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
        $user = factory(User::class)->create();

        $this->assertEmpty($user->metaKeys());

        $keysToSave = [];
        for ($i = 0; $i < rand(1, 10); $i++) {
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
        $user = factory(User::class)->create();

        $key = str_random();
        $value = str_random();

        $user->setMeta($key, $value);
        $user->removeMeta($key);

        $this->assertEquals(0, $user->countMeta());
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

        $this->assertEquals(0, $user->countMeta());
    }
}
