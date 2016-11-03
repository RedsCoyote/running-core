<?php

namespace Running\tests\Core;

use Running\Core\IArrayable;
use Running\Core\IObjectAsArray;
use Running\Core\TObjectAsArray;

class testClass
    implements IObjectAsArray
{
    use TObjectAsArray;
}

class testAnotherClass
    implements IObjectAsArray
{
    use TObjectAsArray;
}

class testWithGetterClass
    implements IObjectAsArray
{
    use TObjectAsArray;
    protected function getFoo()
    {
        return 42;
    }
}

class testWithSetterClass
    implements IObjectAsArray
{
    use TObjectAsArray;
    protected function setFoo($val)
    {
        $this->__data['foo'] = $val*2;
    }
}

class TObjectAsArrayTest extends \PHPUnit_Framework_TestCase
{

    public function testInterfaces()
    {
        $obj = new testClass();

        $this->assertInstanceOf(IObjectAsArray::class,  $obj);
        $this->assertInstanceOf(\ArrayAccess::class,    $obj);
        $this->assertInstanceOf(\Countable::class,      $obj);
        $this->assertInstanceOf(\Iterator::class,       $obj);
        $this->assertInstanceOf(IArrayable::class,      $obj);
    }

    public function testGetData()
    {
        $obj = new testClass();
        $obj[1] = 100;
        $obj[2] = '200';
        $obj['foo'] = 'bar';
        $obj[] = 'baz';

        $this->assertEquals([1=>100, 2=>'200', 'foo'=>'bar', 3=>'baz'], $obj->getData());
    }

    public function testArrayAccess()
    {
        $obj = new testClass();
        $obj[1] = 100;
        $obj[2] = '200';
        $obj[] = 300;

        $this->assertTrue(isset($obj[1]));
        $this->assertTrue(isset($obj[2]));
        $this->assertTrue(isset($obj[3]));

        $this->assertEquals(100, $obj[1]);
        $this->assertEquals('200', $obj[2]);
        $this->assertEquals(300, $obj[3]);

        unset($obj[2]);

        $this->assertTrue(isset($obj[1]));
        $this->assertFalse(isset($obj[2]));
        $this->assertTrue(isset($obj[3]));
    }

    public function testCountable()
    {
        $obj = new testClass();

        $this->assertEquals(0, count($obj));

        $obj[] = 'foo';
        $obj[] = 'bar';

        $this->assertEquals(2, count($obj));

        unset($obj[0]);

        $this->assertEquals(1, count($obj));
    }

    public function testIterator()
    {
        $obj = new testClass();
        $obj['foo'] = 100;
        $obj['bar'] = 200;
        $obj[300]   = 'baz';

        $res = '';
        foreach ($obj as $key => $val) {
            $res .= $key . '=' . $val . ';';
        }

        $this->assertEquals('foo=100;bar=200;300=baz;', $res);
    }

    public function testFromArray()
    {
        $obj = new testClass();
        $obj->fromArray(['foo' => 100, 'bar' => 200, 'baz' => ['one' => 1, 'two' => 2]]);

        $this->assertInstanceOf(testClass::class, $obj);
        $this->assertInstanceOf(testClass::class, $obj['baz']);

        $this->assertEquals(3, count($obj));

        $this->assertEquals(100, $obj['foo']);
        $this->assertEquals(200, $obj['bar']);

        $this->assertEquals((new testClass())->fromArray(['one' => 1, 'two' => 2]), $obj['baz']);
        $this->assertEquals(1, $obj['baz']['one']);
        $this->assertEquals(2, $obj['baz']['two']);
    }

    public function testToArray()
    {
        $obj = new testClass();
        $obj->fromArray(['foo' => 100, 'bar' => 200, 'baz' => ['one' => 1, 'two' => 2]]);
        $arr = $obj->toArray();

        $this->assertTrue(is_array($arr));
        $this->assertEquals(
            ['foo' => 100, 'bar' => 200, 'baz' => ['one' => 1, 'two' => 2]],
            $arr
        );

        $obj = new testClass();
        $obj['foo'] = 100;
        $obj['bar'] = 200;
        $obj['baz'] = (new testAnotherClass())->fromArray(['one' => 1, 'two' => 2]);
        $arr = $obj->toArray();

        $this->assertTrue(is_array($arr));
        $this->assertEquals(
            ['foo' => 100, 'bar' => 200, 'baz' => (new testAnotherClass())->fromArray(['one' => 1, 'two' => 2])],
            $arr
        );
    }

    public function testToArrayRecursive()
    {
        $obj = new testClass();
        $obj['foo'] = 100;
        $obj['bar'] = 200;
        $obj['baz'] = (new testAnotherClass())->fromArray(['one' => 1, 'two' => 2]);
        $arr = $obj->toArrayRecursive();

        $this->assertTrue(is_array($arr));
        $this->assertEquals(
            ['foo' => 100, 'bar' => 200, 'baz' => ['one' => 1, 'two' => 2]],
            $arr
        );
    }

    public function testGetter()
    {
        $obj = new testWithGetterClass();
        $obj[1] = 100;
        $obj['foo'] = 200;

        $this->assertEquals(100, $obj[1]);
        $this->assertEquals(42,  $obj['foo']);
    }

    public function testSetter()
    {
        $obj = new testWithSetterClass();
        $obj[1] = 100;
        $obj['foo'] = 200;

        $this->assertEquals(100, $obj[1]);
        $this->assertEquals(400,  $obj['foo']);
    }

}