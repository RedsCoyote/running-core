<?php

namespace Running\tests\Core\Std;

use Running\Core\IHasMagicGetSet;
use Running\Core\IHasSanitize;
use Running\Core\IHasValidation;
use Running\Core\Std;

class testClass extends Std {
    protected function validateFoo($val) {
        return $val>0;
    }
    protected function sanitizeBar($val) {
        return trim($val);
    }
    protected function setBaz($val) {
        $this->__data['baz'] = $val*2;
    }
}

class StdTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $obj = new Std(['foo' => 42, 'bar' => 'bla-bla', 'baz' => [1, 2, 3]]);

        $this->assertInstanceOf(IHasMagicGetSet::class, $obj);
        $this->assertInstanceOf(IHasSanitize::class, $obj);
        $this->assertInstanceOf(IHasValidation::class, $obj);

        $this->assertCount(3, $obj);
        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Std([1, 2, 3]), $obj->baz);
    }

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

    public function testSetter()
    {
        $obj = new testClass();
        $obj->baz = 42;

        $this->assertTrue(isset($obj->baz));
        $this->assertEquals(84, $obj->baz);
    }

    public function testValidate()
    {
        $obj = new testClass();
        $obj->foo = 42;

        $this->assertTrue(isset($obj->foo));
        $this->assertEquals(42, $obj->foo);

        $obj = new testClass();
        $obj->foo = -42;

        $this->assertFalse(isset($obj->foo));
    }

    public function testSanitize()
    {
        $obj = new testClass();
        $obj->bar = '  test    ';

        $this->assertTrue(isset($obj->bar));
        $this->assertEquals('test', $obj->bar);
    }

}