<?php

declare(strict_types=1);

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
            [[1, 2], [3, 4]],
            arrayed(1, 2, 3, 4)->chunk(2)->result()
        );
    }

    public function testMap()
    {
        $array1 = [1, 2];
        $array2 = ['one', 'two'];

        $this->assertEquals(
            ['ONE', 'TWO'],
            arrayed($array2)
                ->map(function ($value) {
                    return strtoupper($value);
                })->result()
        );

        $this->assertEquals(
            [
                [1 => 'one'],
                [2 => 'two'],
            ],
            arrayed($array1)
                ->map(function ($first, $second) {
                    return [$first => $second];
                }, $array2)
                ->result()
        );

        $this->assertEquals(
            [
                [1, 'one'],
                [2, 'two'],
            ],
            arrayed($array1)->map(null, $array2)->result()
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

    public function testKeysExists()
    {
        $this->assertEquals(
            true,
            arrayed(['a' => 'b', 'c' => 'd'])->keysExists(['a', 'c'])
        );
    }

    public function testReverse()
    {
        $this->assertEquals(
            ['c' => 'd', 'a' => 'b'],
            arrayed(['a' => 'b', 'c' => 'd'])->reverse()->result()
        );
    }

    public function testDiffUassoc()
    {
        $first = ['a' => 'b', 'c' => 'd'];
        $second = ['2' => 'b', 'c' => 'd'];
        $third = ['2' => 'b', 'k' => 'd'];
        $callback = function ($first, $second) {
            return $first === $second;
        };

        $this->assertEquals(
            array_diff_uassoc($first, $second, $callback),
            arrayed($first)->diffUassoc($callback, $second)->result()
        );

        $this->assertEquals(
            array_diff_uassoc($first, $second, $third, $callback),
            arrayed($first)->diffUassoc($callback, $second, $third)->result()
        );
    }

    public function testDiffKey()
    {
        $first = ['a' => 'b', 'c' => 'd'];
        $second = ['2' => 'b', 'c' => 'd'];
        $third = ['2' => 'b', 'k' => 'd', 'm' => 'a'];
        $fourth = ['m' => 'b', 'h' => 'd', 's' => 'a'];

        $this->assertEquals(
            array_diff_key($first, $second),
            arrayed($first)->diffKey($second)->result()
        );

        $this->assertEquals(
            array_diff_key($first, $second, $third, $fourth),
            arrayed($first)->diffKey($second, $third, $fourth)->result()
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

        // array_pop
        $data = ['a' => 'b', 'c' => 'd'];

        $this->assertEquals(
            'd',
            arrayed($data)->pop(),
        );

        // array_shift.
        $this->assertEquals(
            'b',
            $arr = arrayed($data)->shift(),
        );
    }

    public function testHead(): void
    {
        $data = ['a', 'b', 'c', 'd'];

        $this->assertSame(
            ['a'],
            arrayed($data)->head()->result(),
        );

        $data = ['a' => 'b', 'c' => 'd'];

        $this->assertSame(
            ['a' => 'b'],
            arrayed($data)->head()->result(),
        );
    }
}
