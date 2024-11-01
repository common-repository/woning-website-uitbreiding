<?php

namespace Tussendoor\Settings\Tests;

use PHPUnit\Framework\TestCase;
use Tussendoor\Settings\Manager;

/**
 * Test the Manager class with the default Options provider
 */
class ManagerTest extends TestCase
{

    /**
     * When a setting does not exist, get() should return null by default.
     */
    public function testNonExistingSettingReturnsNull()
    {
        $manager = new Manager();
        $setting = $manager->get('foo');

        $this->assertEquals(null, $setting);
    }

    /**
     * The default return value when a setting is not found should be settable.
     */
    public function testCanSetDefaultReturnValue()
    {
        $manager = new Manager();
        $setting = $manager->get('foo', false);

        $this->assertEquals(false, $setting);
    }

    /**
     * Create a new setting and check if the values was actually saved.
     */
    public function testCreatingSetting()
    {
        $manager = new Manager();
        $saved = $manager->save('foo', 'bar');

        $this->assertEquals(true, $saved);
        $this->assertEquals('bar', $manager->get('foo'));
    }

    /**
     * Test that an array can be used to save settings in bulk.
     */
    public function testCreateSettingsWithArray()
    {
        $manager = new Manager();
        $manager->save(['option_name' => 'value', 'lorem' => 'ipsum', 'dolor' => 'setamet']);

        $this->assertEquals('value', $manager->get('option_name'));
        $this->assertEquals('ipsum', $manager->get('lorem'));
        $this->assertEquals('setamet', $manager->get('dolor'));
    }

    
    public function testCannotSaveObjects()
    {
        $manager = new Manager();

        $this->expectException(\Exception::class);
        $manager->save('object', new \stdClass());
    }

    /**
     * Test that arrays can be saved as a setting value.
     */
    public function testCreateSettingWithArrayValue()
    {
        $manager = new Manager();
        $manager->save('array_value', ['foo' => 'bar', 'baz' => 'quz']);

        $saved = $manager->get('array_value');
        $this->assertTrue(is_array($saved));
        $this->assertArrayHasKey('foo', $saved);
    }

    /**
     * An existing setting should be updatable.
     */
    public function testUpdatingSetting()
    {
        $manager = new Manager();
        $this->assertTrue($manager->has('foo'));

        $manager->save('foo', 'baz');

        $this->assertEquals('baz', $manager->get('foo'));
    }

    /**
     * Test if all settings can be returned at once.
     */
    public function testGettingAllOptions()
    {
        $manager = new Manager();
        $options = $manager->getAll();
        $currentCount = count($options);

        $manager->save((string)rand(2, 5), (string)rand(2, 5));

        $this->assertEquals(($currentCount + 1), count($manager->getAll()));
    }

    /**
     * Test the deletion of a setting.
     */
    public function testDeleteSettings()
    {
        $manager = new Manager();
        $deleted = $manager->delete('foo');

        $this->assertEquals(true, $deleted);
        $this->assertEquals(null, $manager->get('foo'));
    }

    /**
     * Since we need to 'fake' the WordPress option functions, we'll use a global variable as storage.
     * This method makes sure the storage is empty before executing any tests.
     */
    public static function setUpBeforeClass()
    {
        global $setting;
        $setting = [];
    }

    /**
     * Since we need to 'fake' the WordPress option functions, we'll use a global variable as storage.
     * This method makes sure the storage is empty after executing all tests.
     */
    public static function tearDownAfterClass()
    {
        global $setting;
        $setting = [];
    }
}
