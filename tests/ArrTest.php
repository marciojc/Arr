<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use marciojc\Arr;

/**
 * We don't accept new tests because the behavior is already tested
 * for the class equivalents in the laravel/framework repository.
 */
class ArrTest extends TestCase
{
    private $array;

    public function setUp()
    {
        $this->array = array();
        for ($i=0; $i < 1000; $i++) {
            $this->array[] = $i;
        }
    }

    public function testExists()
    {
        $exist = Arr::exists($this->array, 5);
        $this->assertTrue($exist);
    }

    public function testGet()
    {
        $value = Arr::get($this->array, 5);
        $this->assertEquals($value, 5);
    }

    public function testAdd()
    {
        $array = Arr::add($this->array, 404, 0);
        $this->assertContains(404, $this->array);
    }

    public function testLength()
    {
        $size = Arr::length($this->array);
        $this->assertEquals($size, 1000);
    }
}
