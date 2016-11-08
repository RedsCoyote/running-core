<?php

namespace Running\tests\Core\MultiException;

use Running\Core\Exception;
use Running\Core\ICollection;
use Running\Core\MultiException;

class SomeException extends Exception
{
}

class MultiExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Running\Core\Exception
     */
    public function testInvalidBaseClass()
    {
        $errors = new MultiException(\stdClass::class);
    }

    public function testCreate()
    {
        $errors = new MultiException();
        $this->assertInstanceOf(
            MultiException::class,
            $errors
        );
        $this->assertInstanceOf(
            ICollection::class,
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

    /**
     * @expectedException \Running\Core\Exception
     */
    public function testInvalidClassPrepend()
    {
        $errors = new MultiException(SomeException::class);
        $errors->prepend(new Exception);
    }

    /**
     * @expectedException \Running\Core\Exception
     */
    public function testInvalidClassAppend()
    {
        $errors = new MultiException(SomeException::class);
        $errors->append(new Exception);
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

            $this->assertTrue(false);

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