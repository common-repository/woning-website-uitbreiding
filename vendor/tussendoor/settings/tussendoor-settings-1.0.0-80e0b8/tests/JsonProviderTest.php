<?php

namespace Tussendoor\Settings\Tests;

use PHPUnit\Framework\TestCase;
use Tussendoor\Settings\Manager;
use Tussendoor\Settings\Providers\SettingsProviderJson;

/**
 * Test the Manager class
 */
class JsonProviderTest extends TestCase
{

    protected $storagePath;
    protected $storageFile;


    public function setUp()
    {
        $this->storagePath = dirname(__DIR__).'/';
        $this->storageFile = 'storage.json';
    }

    /**
     * The Json provider should create a json file for storage if it does not exist.
     */
    public function testProviderCreatesJsonFile()
    {
        new SettingsProviderJson($this->storagePath, $this->storageFile);

        $this->assertTrue(file_exists($this->storagePath.$this->storageFile));
    }

    /**
     * The provider should always implement the SettingsProviderInterface interface.
     */
    public function testProviderImplementsInterface()
    {
        $provider = new SettingsProviderJson($this->storagePath, $this->storageFile);

        $this->assertInstanceOf('Tussendoor\Settings\Providers\SettingsProviderInterface', $provider);
    }

    /**
     * When a setting does not exist, get() should return null by default.
     */
    public function testNonExistingSettingReturnsNull()
    {
        $provider = new SettingsProviderJson($this->storagePath, $this->storageFile);
        $manager = new Manager($provider);
        $setting = $manager->get('foo');

        $this->assertEquals(null, $setting);
    }

    /**
     * The default return value when a setting is not found should be settable.
     */
    public function testCanSetDefaultReturnValue()
    {
        $provider = new SettingsProviderJson($this->storagePath, $this->storageFile);
        $manager = new Manager($provider);
        $setting = $manager->get('foo', false);

        $this->assertEquals(false, $setting);
    }

    /**
     * Create a new setting and check if the values was actually saved.
     */
    public function testCreatingSetting()
    {
        $provider = new SettingsProviderJson($this->storagePath, $this->storageFile);
        $manager = new Manager($provider);
        $saved = $manager->save('foo', 'bar');

        $this->assertEquals(true, $saved);
        $this->assertEquals('bar', $manager->get('foo'));
    }

    /**
     * Test that an array can be used to save settings in bulk.
     */
    public function testCreateSettingsWithArray()
    {
        $provider = new SettingsProviderJson($this->storagePath, $this->storageFile);
        $manager = new Manager($provider);
        $manager->save([
            'option_name'   => 'value',
            'lorem'         => 'ipsum',
            'dolor'         => 'setamet',
        ]);

        $this->assertEquals('value', $manager->get('option_name'));
        $this->assertEquals('ipsum', $manager->get('lorem'));
        $this->assertEquals('setamet', $manager->get('dolor'));
    }


    public function testCreateSettingsWithMultiDimensionalArray()
    {
        $provider = new SettingsProviderJson($this->storagePath, $this->storageFile);
        $manager = new Manager($provider);
        $manager->save([
            'array_one'   => [
                'array_two' => [
                    'array_three' => [
                        'array_four'    => 'value',
                    ],
                ],
            ],
        ]);

        $saved = $manager->get('array_one');

        $this->assertTrue(is_array($saved));
        $this->assertEquals('value', $saved['array_two']['array_three']['array_four']);
    }


    public function testCannotSaveObjects()
    {
        $provider = new SettingsProviderJson($this->storagePath, $this->storageFile);
        $manager = new Manager($provider);

        $this->expectException(\Exception::class);
        $manager->save('object', new \stdClass());
    }

    /**
     * Test that arrays can be saved as a setting value.
     */
    public function testCreateSettingWithArrayValue()
    {
        $provider = new SettingsProviderJson($this->storagePath, $this->storageFile);
        $manager = new Manager($provider);
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
        $provider = new SettingsProviderJson($this->storagePath, $this->storageFile);
        $manager = new Manager($provider);
        $this->assertTrue($manager->has('foo'));

        $manager->save('foo', 'baz');

        $this->assertEquals('baz', $manager->get('foo'));
    }

    /**
     * Test if all settings can be returned at once.
     */
    public function testGettingAllOptions()
    {
        $provider = new SettingsProviderJson($this->storagePath, $this->storageFile);
        $manager = new Manager($provider);
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
        $provider = new SettingsProviderJson($this->storagePath, $this->storageFile);
        $manager = new Manager($provider);
        $deleted = $manager->delete('foo');

        $this->assertEquals(true, $deleted);
        $this->assertEquals(null, $manager->get('foo'));
    }
    
    /**
     * Remove the json storage file (if there's any) before setting up this class.
     */
    public static function setUpBeforeClass()
    {
        if (file_exists(dirname(__DIR__).'/storage.json')) {
            unlink(dirname(__DIR__).'/storage.json');
        }
    }

    /**
     * Remove the json storage file after completing all tests.
     */
    public static function tearDownAfterClass()
    {
        if (file_exists(dirname(__DIR__).'/storage.json')) {
            unlink(dirname(__DIR__).'/storage.json');
        }
    }
}
