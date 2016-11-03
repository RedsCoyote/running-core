<?php

namespace Running\tests\Core\TCollection;

use Running\Core\ICollection;
use Running\Core\TCollection;

class testClass
    implements ICollection
{
    use TCollection;
}

class TCollectionTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $obj = new testClass([100, 200, 300]);

        $this->assertInstanceOf(ICollection::class, $obj);
        $this->assertCount(3, $obj);
        $this->assertEquals(100, $obj[0]);
        $this->assertEquals(200, $obj[1]);
        $this->assertEquals(300, $obj[2]);
    }

    public function testAppendPrependAdd()
    {
        $obj = new testClass();

        $obj->append(100);
        $obj->append(200);

        $this->assertCount(2, $obj);
        $this->assertEquals(100, $obj[0]);
        $this->assertEquals(200, $obj[1]);

        $obj->prepend(300);

        $this->assertCount(3, $obj);
        $this->assertEquals(300, $obj[0]);
        $this->assertEquals(100, $obj[1]);
        $this->assertEquals(200, $obj[2]);

        $obj->add(400);

        $this->assertCount(4, $obj);
        $this->assertEquals(300, $obj[0]);
        $this->assertEquals(100, $obj[1]);
        $this->assertEquals(200, $obj[2]);
        $this->assertEquals(400, $obj[3]);
    }

}