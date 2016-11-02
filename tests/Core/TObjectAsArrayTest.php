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

}