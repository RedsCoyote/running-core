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
        $collection = new testClass([100, 200, 300]);

        $this->assertInstanceOf(ICollection::class, $collection);
        $this->assertCount(3, $collection);
        $this->assertEquals(
            [100, 200, 300],
            $collection->toArray()
        );
        $this->assertEquals(100, $collection[0]);
        $this->assertEquals(200, $collection[1]);
        $this->assertEquals(300, $collection[2]);
    }

    public function testAppendPrependAdd()
    {
        $collection = new testClass();

        $collection->append(100);
        $collection->append(200);

        $this->assertCount(2, $collection);
        $this->assertEquals(100, $collection[0]);
        $this->assertEquals(200, $collection[1]);
        $this->assertEquals(
            [100, 200],
            $collection->toArray()
        );

        $collection->prepend(300);

        $this->assertCount(3, $collection);
        $this->assertEquals(300, $collection[0]);
        $this->assertEquals(100, $collection[1]);
        $this->assertEquals(200, $collection[2]);
        $this->assertEquals(
            [300, 100, 200],
            $collection->toArray()
        );

        $collection->add(400);

        $this->assertCount(4, $collection);
        $this->assertEquals(300, $collection[0]);
        $this->assertEquals(100, $collection[1]);
        $this->assertEquals(200, $collection[2]);
        $this->assertEquals(400, $collection[3]);
        $this->assertEquals(
            [300, 100, 200, 400],
            $collection->toArray()
        );
    }

    public function testMerge()
    {
        $collection = new testClass([1, 2]);

        $collection->merge([3, 4]);
        $this->assertCount(4, $collection);
        $expected = new testClass([1, 2, 3, 4]);
        $this->assertEquals($expected->toArray(), $collection->toArray());

        $collection->merge(new testClass([5, 6]));
        $this->assertCount(6, $collection);
        $expected = new testClass([1, 2, 3, 4, 5, 6]);
        $this->assertEquals($expected->toArray(), $collection->toArray());
    }

    public function testSlice()
    {
        $collection = new testClass([10, 20, 30, 40, 50]);
        $this->assertEquals(
            new testClass([30, 40, 50]),
            $collection->slice(2)
        );
        $this->assertEquals(
            new testClass([40, 50]),
            $collection->slice(-2)
        );
        $this->assertEquals(
            new testClass([30, 40]),
            $collection->slice(2, 2)
        );
        $this->assertEquals(
            new testClass([40]),
            $collection->slice(-2, 1)
        );
    }

    public function testFirstLast()
    {
        $collection = new testClass([10, 20, 30, 40, 50]);
        $this->assertEquals(
            10,
            $collection->first()
        );
        $this->assertEquals(
            50,
            $collection->last()
        );
    }

    public function testExistElement()
    {
        $collection = new testClass();
        $el1 = new \Running\Core\Std(['id' => 1, 'title' => 'foo', 'text' => 'FooFooFoo']);
        $collection->append($el1);
        $el2 = new \Running\Core\Std(['id' => 2, 'title' => 'bar', 'text' => 'BarBarBar']);
        $collection->append($el2);
        $collection->append(42);

        $this->assertFalse($collection->existsElement([]));
        $this->assertTrue($collection->existsElement(['id' =>  1]));
        $this->assertFalse($collection->existsElement(['id' =>  3]));
        $this->assertTrue($collection->existsElement(['title' =>  'foo']));
        $this->assertTrue($collection->existsElement(['title' =>  'foo', 'text' => 'FooFooFoo']));
        $this->assertFalse($collection->existsElement(['title' =>  'foo', 'text' => 'BarBarBar']));
    }

    public function testSort()
    {
        $collection = new testClass([10 => 1, 30 => 3, 20 => 2, 'a' => -1, 'b' => 0, 'c' => 42, 1 => '1', '111', '11']);

        $result = $collection->asort();

        $expected = new testClass(['a' => -1, 'b' => 0, 1 => '1', 10 => 1, 20 => 2, 30 => 3, 32 => '11', 'c' => 42, 31 => '111']);
        $this->assertEquals($expected->toArray(), $result->toArray());

        $result = $collection->ksort();

        $expected = new testClass(['a' => -1, 'b' => 0, 'c' => 42, 1 => '1', 10 => 1, 20 => 2, 30 => 3, 31 => '111', 32 => '11']);
        $this->assertEquals($expected->toArray(), $result->toArray());

        $result = $collection->uasort(function ($a, $b) { return $a < $b ? 1 : ($a > $b ? -1 : 0);});

        $expected = new testClass([31 => '111', 'c' => 42, 32 => '11', 30 => 3, 20 => 2, 10 => 1, 1 => '1', 'b' => 0, 'a' => -1]);
        $this->assertEquals($expected->toArray(), $result->toArray());

        $result = $collection->uksort(function ($a, $b) { return $a < $b ? 1 : ($a > $b ? -1 : 0);});

        $expected = new testClass([32 => '11', 31 => '111', 30 => 3, 20 => 2, 10 => 1, 1 => '1', 'c' => 42, 'b' => 0, 'a' => -1]);
        $this->assertEquals($expected->toArray(), $result->toArray());

        $collection = new testClass([0 => '12', 1 => '10', 2 => '2', 3 => '1']);
        $result = $collection->natsort();
        $expected = new testClass([3 => '1', 2 => '2', 1 => '10', 0 => '12']);
        $this->assertEquals($expected->toArray(), $result->toArray());

        $collection = new testClass([0 => 'IMG0.png', 1 => 'img12.png', 2 => 'img10.png', 3 => 'img2.png', 4 => 'img1.png', 5 => 'IMG3.png']);
        $result = $collection->natcasesort();
        $expected = new testClass([0 => 'IMG0.png', 4 => 'img1.png', 3 => 'img2.png',  5 => 'IMG3.png', 2 => 'img10.png', 1 => 'img12.png']);
        $this->assertEquals($expected->toArray(), $result->toArray());
    }

}