<?php

namespace Transprime\Arrayed\Tests;

use PHPUnit\Framework\TestCase;

class ArrayPrefixTraitTest extends TestCase
{
    public function testChangeKeyCase()
    {
        $this->assertEquals(
            ['ABC' => 'cde'],
            arrayed(['abc' => 'cde'])->changeKeyCase(CASE_UPPER)->result()
        );
    }

    public function testChunk()
    {
        $this->assertEquals(
            [[1,2], [3,4]],
            arrayed(1, 2, 3, 4)->chunk(2)->result()
        );
    }

    public function testColumn()
    {
        $array = [
            ['a' => 1, 'b' => 4],
            ['a' => 2, 'b' => 3],
        ];

        $this->assertEquals(
            [4, 3],
            arrayed($array)->column('b')->result()
        );
    }

    public function testCountValues()
    {
        $this->assertEquals(
            ['a' => 2, 'b' => 2, 1 => 1, 4 => 1],
            arrayed(['a', 1, 'b', 4, 'b', 'a'])->countValues()->result()
        );
    }

    public function testDiffAssoc()
    {
        $array2 = ['a' => 2, 'b' => 2];
        $this->assertEquals(
            ['c' => 3],
            arrayed(['a' => 2, 'c' => 3, 'b' => 2])->diffAssoc($array2)->result()
        );
    }

    public function testDiff()
    {
        $array2 = ['a', 'b'];
        $this->assertEquals(
            ['c'],
            arrayed(['a', 'c', 'b'])->diff($array2)->values()->result()
        );
    }

    public function testUnImplementedArrayPrefixFunction()
    {
        // array_combine
        $keys = ['a', 'b'];
        $values = ['name', 'data'];

        $this->assertEquals(
            array_combine($keys, $values),
            arrayed($keys)->combine($values)->result()
        );
    }
}
