<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use marciojc\Arr;

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

    public function testFilter()
    {
        function even($item, $index)
        {
            return $item % 2 === 0 && $index < 10;
        };

        $filtered = Arr::filter($this->array, 'even');
        $this->assertEquals($filtered, array(0 => 0, 2 => 2, 4 => 4, 6 => 6, 8 => 8));
    }

    public function testFind()
    {
        function evenGreatTen($item, $index)
        {
            return $item % 2 === 0 && $index >= 10;
        };

        $item = Arr::find($this->array, 'evenGreatTen');
        $this->assertEquals($item, 10);
    }

    public function testFlat()
    {
        $multiArray = [1, 2, [3, 4, [5, 6]]];
        $multiArray2 = [1, 2, [3, 4, [5, 6]]];
        $flattedArray = Arr::flat($multiArray, 1);
        $flattedArray2 = Arr::flat($multiArray2);
        $this->assertEquals($flattedArray, [1, 2, 3, 4, [5, 6]]);
        $this->assertEquals($flattedArray2, [1, 2, 3, 4, 5, 6]);
    }

    public function testConcat()
    {
        $array1 = [1, 2];
        $array2 = [3, 4];
        $concatArray = Arr::concat($array1, $array2);
        $this->assertEquals($concatArray, [1, 2, 3, 4]);
    }
}
