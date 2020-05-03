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
            arrayed(1,2,3,4)->chunk(2)->result()
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
}