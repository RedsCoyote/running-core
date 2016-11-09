<?php

namespace Running\tests\Core\Config;

use Running\Core\Config;
use Running\Core\IHasMagicGetSet;
use Running\Core\IHasSanitize;
use Running\Core\IHasValidation;
use Running\Core\IObjectAsArray;
use Running\Core\Std;
use Running\Fs\File;

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

        $this->assertInstanceOf(IObjectAsArray::class, $obj);
        $this->assertInstanceOf(IHasMagicGetSet::class, $obj);
        $this->assertInstanceOf(IHasSanitize::class, $obj);
        $this->assertInstanceOf(IHasValidation::class, $obj);
        $this->assertInstanceOf(Std::class, $obj);
        $this->assertInstanceOf(Config::class, $obj);

        $this->assertCount(3, $obj);
        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Config([1, 2, 3]), $obj->baz);
    }

    public function testConstructWFile()
    {
        $obj = new Config(new File(self::TMP_PATH . '/return.php'));

        $this->assertInstanceOf(Config::class, $obj);
        $this->assertCount(3, $obj);
        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Config([1, 2, 3]), $obj->baz);
    }

    /*
    public function testMerge()
    {
        $obj1 = new Std(['foo' => 1]);
        $obj1->merge(['bar' => 2]);
        $this->assertEquals(1, $obj1->foo);
        $this->assertEquals(2, $obj1->bar);
        $this->assertEquals(new Std(['foo' => 1, 'bar' => 2]), $obj1);

        $obj2 = new Std(['foo' => 11]);
        $obj2->merge(new Std(['bar' => 21]));
        $this->assertEquals(11, $obj2->foo);
        $this->assertEquals(21, $obj2->bar);
        $this->assertEquals(new Std(['foo' => 11, 'bar' => 21]), $obj2);

        $obj2 = new Std(['foo' => 11, 'bar' => 12]);
        $obj2->merge(new Std(['bar' => 21]));
        $this->assertEquals(11, $obj2->foo);
        $this->assertEquals(21, $obj2->bar);
        $this->assertEquals(new Std(['foo' => 11, 'bar' => 21]), $obj2);
    }
    */

    protected function tearDown()
    {
        unlink(self::TMP_PATH . '/return.php');
        rmdir(self::TMP_PATH);
    }

}