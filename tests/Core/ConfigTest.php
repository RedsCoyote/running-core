<?php

namespace Running\tests\Core\Config;

use Running\Core\Config;
use Running\Core\SingleStorageInterface;
use Running\Core\HasMagicGetSetInterface;
use Running\Core\HasSanitizingInterface;
use Running\Core\HasValidationInterface;
use Running\Core\ObjectAsArrayInterface;
use Running\Core\Std;
use Running\Fs\PhpFile;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

    const TMP_PATH = __DIR__ . '/tmp';

    protected function setUp()
    {
        mkdir(self::TMP_PATH);
        file_put_contents(self::TMP_PATH . '/return.php', "<?php return ['foo' => 42, 'bar' => 'bla-bla', 'baz' => [1, 2, 3]];");
    }

    public function testConstructWData()
    {
        $obj = new Config(['foo' => 42, 'bar' => 'bla-bla', 'baz' => [1, 2, 3]]);

        $this->assertInstanceOf(ObjectAsArrayInterface::class, $obj);
        $this->assertInstanceOf(HasMagicGetSetInterface::class, $obj);
        $this->assertInstanceOf(HasSanitizingInterface::class, $obj);
        $this->assertInstanceOf(HasValidationInterface::class, $obj);
        $this->assertInstanceOf(Std::class, $obj);
        $this->assertInstanceOf(SingleStorageInterface::class, $obj);
        $this->assertInstanceOf(Config::class, $obj);

        $this->assertCount(3, $obj);
        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Config([1, 2, 3]), $obj->baz);
    }

    public function testConstructWFile()
    {
        $obj = new Config(new PhpFile(self::TMP_PATH . '/return.php'));

        $this->assertInstanceOf(Config::class, $obj);
        $this->assertCount(3, $obj);
        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Config([1, 2, 3]), $obj->baz);
    }

    public function testSetGetStorage()
    {
        $obj = new Config();

        $this->assertNull($obj->getStorage());

        $file = new PhpFile(self::TMP_PATH . '/return.php');
        $obj->setStorage($file);

        $this->assertEquals($file, $obj->getStorage());
    }

    public function testMagicStorage()
    {
        $obj = new Config();

        $this->assertNull($obj->storage);
        $this->assertNull($obj->getStorage());

        $obj->storage = 'test.txt';

        $this->assertEquals('test.txt', $obj->storage);
        $this->assertNull($obj->getStorage());

        $obj->setStorage(new PhpFile('example.php'));

        $this->assertEquals('test.txt', $obj->storage);
        $this->assertEquals(new PhpFile('example.php'), $obj->getStorage());
    }

    /**
     * @expectedException \Running\Core\Exception
     * @expectedExceptionMessage Empty config storage
     */
    public function testLoadEmptyFile()
    {
        $obj = new Config();
        $obj->load();
        $this->fail();
    }

    public function testLoad()
    {
        $obj = new Config();
        $file = new PhpFile(self::TMP_PATH . '/return.php');
        $file->load();
        $obj->setStorage(new PhpFile(self::TMP_PATH . '/return.php'))->load();

        $this->assertEquals($file, $obj->getStorage());

        $this->assertCount(3, $obj);
        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Config([1, 2, 3]), $obj->baz);

        $obj->foo = null;
        unset($obj->bar);
        $obj->baz = 'nothing';

        $obj->load();

        $this->assertCount(3, $obj);
        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Config([1, 2, 3]), $obj->baz);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testSet()
    {
        $config = new Config();
        $config->set(42);
        $this->fail();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGet()
    {
        $config = new Config(new PhpFile(self::TMP_PATH . '/return.php'));
        $data = $config->get();
        $this->fail();
    }

    protected function tearDown()
    {
        unlink(self::TMP_PATH . '/return.php');
        rmdir(self::TMP_PATH);
    }

}