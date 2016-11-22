<?php

namespace Running\tests\Core\TObjectAsArray;

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

    public function testIsEmpty()
    {
        $obj = new testClass();
        $this->assertTrue($obj->isEmpty());
        $obj[0] = 1;
        $this->assertFalse($obj->isEmpty());
        unset($obj[0]);
        $this->assertTrue($obj->isEmpty());
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

    public function testDataKey()
    {
        $obj = new testClass;
        $obj['data'] = 42;
        $this->assertEquals(42, $obj['data']);
    }

    public function testCountable()
    {
        $obj = new testClass();

        $this->assertCount(0, $obj);

        $obj[] = 'foo';
        $obj[] = 'bar';

        $this->assertCount(2, $obj);

        unset($obj[0]);

        $this->assertCount(1, $obj);
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

        $this->assertCount(3, $obj);

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

    public function testSerialize()
    {
        $obj = new testClass();
        $obj->fromArray([1=>100, 2=>200, 'foo'=>'bar']);

        $this->assertContains('{a:3:{i:1;i:100;i:2;i:200;s:3:"foo";s:3:"bar";}', serialize($obj));
        $this->assertEquals($obj, unserialize(serialize($obj)));
    }

    public function testJsonSerialize()
    {
        $obj = new testClass();
        $obj->fromArray([1=>100, 2=>200, 'foo'=>'bar']);

        $this->assertEquals('{"1":100,"2":200,"foo":"bar"}', json_encode($obj));
    }
    
    public function testSiblingClasses()
    {
        $obj = new testClass();
        $obj->fromArray(['foo' => new testAnotherClass()]);
        
        $this->assertInstanceOf(testClass::class, $obj);
        $this->assertInstanceOf(testAnotherClass::class, $obj['foo']);
    }

}