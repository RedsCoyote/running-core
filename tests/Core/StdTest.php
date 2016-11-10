<?php

namespace Running\tests\Core\Std;

use Running\Core\Exception;
use Running\Core\IHasMagicGetSet;
use Running\Core\IHasSanitize;
use Running\Core\IHasValidation;
use Running\Core\MultiException;
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

class testClassWExceptions extends Std {
    protected function validateFoo($val) {
        if ($val < 0) {
            throw new Exception('Minus');
        }
        return true;
    }
    protected function validateBar($val) {
        if (strlen($val) < 3) {
            yield new Exception('Small');
        }
        if (false !== strpos($val, '0')) {
            yield new Exception('Zero');
        }
        return true;
    }
    protected function validateBaz($val) {
        if ($val > 100) {
            throw new Exception('Large');
        }
        return true;
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

    public function testFill()
    {
        $obj1 = new Std(['foo' => 1, 'bar' => 2]);

        $this->assertEquals(1, $obj1->foo);
        $this->assertEquals(2, $obj1->bar);
        $this->assertEquals(new Std(['foo' => 1, 'bar' => 2]), $obj1);

        $data = ['bar' => 22, 'baz' => 3];
        $obj1->fill($data);

        $this->assertEquals(1, $obj1->foo);
        $this->assertEquals(22, $obj1->bar);
        $this->assertEquals(3, $obj1->baz);
        $this->assertEquals(new Std(['foo' => 1, 'bar' => 22, 'baz' => 3]), $obj1);
    }

    public function testFillWException()
    {
        $obj1 = new testClassWExceptions(['foo' => 1, 'bar' => 'hello']);

        $this->assertEquals(1, $obj1->foo);
        $this->assertEquals('hello', $obj1->bar);
        $this->assertEquals(new testClassWExceptions(['foo' => 1, 'bar' => 'hello']), $obj1);

        $data = ['foo' => -1, 'baz' => 200];
        try {
            $obj1->fill($data);
            $this->fail();
        } catch (\Throwable $errors) {

            $this->assertInstanceOf(MultiException::class, $errors);
            $this->assertEquals(2, count($errors));

            $this->assertInstanceOf(Exception::class, $errors[0]);
            $this->assertEquals('Minus', $errors[0]->getMessage());
            $this->assertInstanceOf(Exception::class, $errors[1]);
            $this->assertEquals('Large', $errors[1]->getMessage());

        }
    }

    public function testFillWExceptions1()
    {
        $obj1 = new testClassWExceptions(['foo' => 1, 'bar' => 'hello']);
        $data = ['bar' => '0f'];
        try {
            $obj1->fill($data);
            $this->fail();
        } catch (\Throwable $errors) {

            $this->assertInstanceOf(MultiException::class, $errors);
            $this->assertEquals(2, count($errors));

            $this->assertInstanceOf(Exception::class, $errors[0]);
            $this->assertEquals('Small', $errors[0]->getMessage());
            $this->assertInstanceOf(Exception::class, $errors[1]);
            $this->assertEquals('Zero', $errors[1]->getMessage());

        }
    }

    public function testFillWExceptions2()
    {
        $obj1 = new testClassWExceptions(['foo' => 1, 'bar' => 'hello']);
        $data = ['foo' => -1, 'bar' => '0f'];
        try {
            $obj1->fill($data);
            $this->fail();
        } catch (\Throwable $errors) {

            $this->assertInstanceOf(MultiException::class, $errors);
            $this->assertEquals(3, count($errors));

            $this->assertInstanceOf(Exception::class, $errors[0]);
            $this->assertEquals('Minus', $errors[0]->getMessage());
            $this->assertInstanceOf(Exception::class, $errors[0]);
            $this->assertEquals('Small', $errors[1]->getMessage());
            $this->assertInstanceOf(Exception::class, $errors[1]);
            $this->assertEquals('Zero', $errors[2]->getMessage());

        }
    }

}