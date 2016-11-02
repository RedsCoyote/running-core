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

}