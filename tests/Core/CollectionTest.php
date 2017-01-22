<?php

namespace Running\tests\Core\Collection;

use Running\Core\Collection;
use Running\Core\CollectionInterface;

class CollectionTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $collection = new Collection([100, 200, 300, [400, 500]]);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertCount(4, $collection);
        $this->assertEquals(
            [100, 200, 300, new Collection([400, 500])],
            $collection->getData()
        );
        $this->assertEquals(
            [100, 200, 300, [400, 500]],
            $collection->toArray()
        );
        $this->assertEquals(100, $collection[0]);
        $this->assertEquals(200, $collection[1]);
        $this->assertEquals(300, $collection[2]);
        $this->assertEquals(new Collection([400, 500]), $collection[3]);
    }

}