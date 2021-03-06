<?php

namespace BWibrew\SiteSettings\Tests;

use BWibrew\SiteSettings\Setting;
use BWibrew\SiteSettings\Tests\Models\User;

class SettingTest extends TestCase
{
    /** @test */
    public function it_has_a_name()
    {
        $setting = factory(Setting::class)->create(['name' => 'a_setting_name']);

        $this->assertEquals('a_setting_name', $setting->name);
    }

    /** @test */
    public function it_has_a_value()
    {
        $setting = factory(Setting::class)->create(['value' => 'Hello World!']);

        $this->assertEquals('Hello World!', $setting->value);
    }

    /** @test */
    public function it_can_register_a_new_setting()
    {
        $user = factory(User::class)->create();

        $setting = Setting::register('registered_setting', null, $user);

        $this->assertEquals('registered_setting', $setting->name);
    }

    /** @test */
    public function it_can_register_a_new_setting_with_a_value()
    {
        $user = factory(User::class)->create();
        Setting::register('new_setting', 'setting value', $user);

        $setting = Setting::where('name', 'new_setting')->first();

        $this->assertEquals('new_setting', $setting->name);
        $this->assertEquals('setting value', $setting->value);
    }

    /** @test */
    public function it_can_update_the_setting_name()
    {
        $user = factory(User::class)->create();
        $setting = factory(Setting::class)->create(['name' => 'original_name', 'value' => '']);

        $setting->updateName('new_name', $user);

        $this->assertEquals('new_name', $setting->name);
    }

    /** @test */
    public function it_can_update_the_setting_value()
    {
        $user = factory(User::class)->create();
        $setting = factory(Setting::class)->create(['name' => 'name', 'value' => 'original value']);

        $setting->updateValue('new value', $user);

        $this->assertEquals('new value', $setting->value);
    }

    /** @test */
    public function it_can_get_the_value()
    {
        factory(Setting::class)->create(['name' => 'setting_name', 'value' => 'setting value']);

        $value = Setting::getValue('setting_name');

        $this->assertEquals('setting value', $value);
    }

    /** @test */
    public function it_updates_the_user_id_when_updating_a_setting()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $setting = factory(Setting::class)->create(['name' => 'original_name', 'value' => 'original value']);
        $this->actingAs($user1);

        $setting->updateValue('new value', $user1);

        $this->assertEquals($user1->id, $setting->updated_by);

        $this->actingAs($user2);

        $setting->updateName('new_name', $user2);

        $this->assertEquals($user2->id, $setting->updated_by);
    }

    /** @test */
    public function it_sets_the_user_id_when_registering_a_setting()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $setting = Setting::register('setting_name', null, $user);

        $this->assertEquals($user->id, $setting->updated_by);
    }

    /** @test */
    public function it_can_get_the_updated_by_user_id()
    {
        factory(Setting::class)->create(['name' => 'setting_name', 'value' => 'value name', 'updated_by' => 1]);

        $user_id = Setting::getUpdatedBy('setting_name');

        $this->assertEquals(1, $user_id);
    }

    /** @test */
    public function it_can_get_the_updated_timestamp()
    {
        $setting = factory(Setting::class)->create(['name' => 'setting_name']);

        $timestamp = Setting::getWhenUpdated('setting_name');

        $this->assertEquals($setting->updated_at, $timestamp);
    }
}
