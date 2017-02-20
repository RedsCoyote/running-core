<?php

namespace Running\tests\Core\MultiException;

use Running\Core\Exception;
use Running\Core\CollectionInterface;
use Running\Core\MultiException;

class SomeException extends Exception
{
}

class MultiExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $errors = new MultiException();
        $this->assertInstanceOf(
            MultiException::class,
            $errors
        );
        $this->assertInstanceOf(
            CollectionInterface::class,
            $errors
        );
        $this->assertTrue($errors->isEmpty());
    }

    public function testAppend()
    {
        $errors = new MultiException;
        $this->assertTrue($errors->isEmpty());

        $errors->append(new Exception('First'));
        $this->assertFalse($errors->isEmpty());
        $this->assertEquals(1, $errors->count());

        $errors->append(new Exception('Second'));
        $this->assertFalse($errors->isEmpty());
        $this->assertEquals(2, $errors->count());

        $this->assertEquals(
            [new Exception('First'), new Exception('Second')],
            $errors->toArray()
        );
    }

    public function testPrepend()
    {
        $errors = new MultiException;
        $this->assertTrue($errors->isEmpty());

        $errors->prepend(new Exception('First'));
        $this->assertFalse($errors->isEmpty());
        $this->assertEquals(1, $errors->count());

        $errors->prepend(new Exception('Second'));
        $this->assertFalse($errors->isEmpty());
        $this->assertEquals(2, $errors->count());

        $this->assertEquals(
            [new Exception('Second'), new Exception('First')],
            $errors->toArray()
        );
    }

    public function testAdd()
    {
        $errors = new MultiException;
        $this->assertTrue($errors->isEmpty());

        $errors->add(new Exception('First'));
        $this->assertFalse($errors->isEmpty());
        $this->assertEquals(1, $errors->count());

        $errors->add(new Exception('Second'));
        $this->assertFalse($errors->isEmpty());
        $this->assertEquals(2, $errors->count());

        $this->assertInstanceOf(
            \Running\Core\Exception::class,
            $errors[0]
        );
        $this->assertInstanceOf(
            \Running\Core\Exception::class,
            $errors[1]
        );
        $this->assertEquals(new Exception('First'), $errors[0]);
        $this->assertEquals(new Exception('Second'), $errors[1]);
    }

    public function testAddSelf()
    {
        $errors = new MultiException;
        $this->assertTrue($errors->isEmpty());

        $errors->add(new Exception('First'));
        $this->assertFalse($errors->isEmpty());
        $this->assertEquals(1, $errors->count());

        $merged = new MultiException;
        $merged[] = new Exception('Second');
        $merged[] = new Exception('Third');
        $this->assertEquals(2, $merged->count());

        $errors->add($merged);
        $this->assertEquals(3, $errors->count());
        $this->assertEquals(new Exception('First'), $errors[0]);
        $this->assertEquals(new Exception('Second'), $errors[1]);
        $this->assertEquals(new Exception('Third'), $errors[2]);
    }

    /**
     * @expectedException \Running\Core\Exception
     */
    public function testInvalidClassPrepend()
    {
        $errors = new class extends MultiException
        {
            public static function getType()
            {
                return SomeException::class;
            }
        };
        $errors->prepend(new Exception);
    }

    /**
     * @expectedException \Running\Core\Exception
     */
    public function testInvalidClassAppend()
    {
        $errors = new class extends MultiException
        {
            public static function getType()
            {
                return SomeException::class;
            }
        };
        $errors->append(new Exception);
    }

    /**
     * @expectedException \Running\Core\Exception
     */
    public function testInvalidInnserSet()
    {
        $errors = new class extends MultiException
        {
            public static function getType()
            {
                return SomeException::class;
            }
        };
        $errors[] = new Exception;
    }

    public function testThrow()
    {
        try {

            $errors = new MultiException();
            $errors->add(new Exception('Foo'));
            $errors->add(new Exception('Bar'));
            $errors->add(new Exception('Baz'));

            if (!$errors->isEmpty()) {
                throw $errors;
            }

            $this->fail();

        } catch (MultiException $ex) {

            $this->assertEquals(3, $ex->count());

            $this->assertInstanceOf(
                \Running\Core\Exception::class,
                $ex[0]
            );
            $this->assertInstanceOf(
                \Running\Core\Exception::class,
                $ex[1]
            );
            $this->assertInstanceOf(
                \Running\Core\Exception::class,
                $ex[2]
            );

            $this->assertEquals('Foo', $ex[0]->getMessage());
            $this->assertEquals('Bar', $ex[1]->getMessage());
            $this->assertEquals('Baz', $ex[2]->getMessage());

        }
    }

}