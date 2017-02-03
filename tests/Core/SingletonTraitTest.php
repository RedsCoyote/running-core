<?php

namespace Running\tests\Core\SingletonTrait;

use Running\Core\SingletonInterface;
use Running\Core\SingletonTrait;

class testClass1
    implements SingletonInterface {
    use SingletonTrait;
}
class testClass2
    implements SingletonInterface {
    use SingletonTrait;
    public $x;
    public $y;
    protected function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
}

class SingletonTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testWoArguments()
    {
        $obj1 = testClass1::instance();
        $obj2 = testClass1::instance();

        $this->assertSame($obj2, $obj1);
    }

    public function testWArguments()
    {
        $obj1 = testClass2::instance(1, -1);
        $obj2 = testClass2::instance(1, -1);

        $this->assertSame($obj2, $obj1);
        $this->assertEquals(1, $obj1->x);
        $this->assertEquals(-1, $obj1->y);
    }

    public function testConstruct()
    {
        $reflector = new \ReflectionClass(testClass1::class);
        $this->assertFalse( $reflector->getMethod('__construct')->isPublic() );
    }

    public function testClone()
    {
        $reflector = new \ReflectionClass(testClass1::class);
        $this->assertFalse( $reflector->getMethod('__clone')->isPublic() );
    }

}