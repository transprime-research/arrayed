<?php

namespace Piper\Tests;

use PHPUnit\Framework\TestCase;
use Transprime\Arrayed\{Arrayed, Exceptions\ArrayedException};

class ArrayedTest extends TestCase
{
    public function testArrayedIsCreated()
    {
        $this->assertIsObject(new Arrayed());
    }

    public function testSum()
    {
        $this->assertEquals(3, arrayed(1, 2)->sum());
        $this->assertEquals(
            5,
            arrayed(1, 2, 'ninja')
                ->filter(fn($val) => is_int($val))
                ->map(fn($val) => $val + 1)
                ->sum()
        );
    }

    public function testPassingAnArray()
    {
        $this->assertEquals(
            5,
            arrayed([1, 2, 'ninja'])
                ->filter(fn($val) => is_int($val))
                ->map(fn($val) => $val + 1)
                ->sum()
        );
    }

    public function testReduce()
    {
        $this->assertEquals(
            6,
            arrayed([1, 2, 3])->reduce(fn($acc, $item) => $acc + $item, 0)()
        );

        $this->assertEquals(
            7,
            arrayed(1, 2)
                ->map(fn($i) => $i + 1)
                ->reduce(fn($v, $i) => $v + $i, 2)()
        );
    }

    public function testOffsetMethods()
    {
        $this->assertEquals(
            1,
            arrayed(['a' => 1, 'b' => 2])
                ->offsetGet('a')
        );

        $this->assertEquals(
            ['z' => 1],
            arrayed(['a' => ['z' => 1], 'b' => 2])
                ->offsetGet('a')()
        );

        $this->assertTrue(
            arrayed(['a' => ['z' => 1], 'b' => 2])
                ->offsetExists('b')
        );

        $this->assertEquals(
            3,
            arrayed(['a' => ['z' => 1], 'b' => 2])
                ->offsetSet('c', 3)
                ->count()
        );

        $this->assertEquals(
            ['b' => 2],
            arrayed(['a' => ['z' => 1], 'b' => 2])
                ->offsetUnset('a')()
        );
    }

    public function testMergeMethod()
    {
        $this->assertCount(
           4,
            arrayed(['a' => 1, 'b' => 2])
                ->merge(['z' => 26])
                ->merge(['c' => 2])()
        );

        $this->assertCount(
           4,
            arrayed(['a' => 1, 'b' => 2])
                ->merge(['z' => 26],['c' => 2])()
        );
    }

    public function testAccessibleArray()
    {
        $this->assertCount(
            2,
            arrayed(['a' => 1, 'b' => 2])
        );

        $this->assertEquals(
            2,
            arrayed(['a' => 1, 'b' => 2])['b']
        );

        $this->assertCount(
            2,
            [...arrayed([1, 2])]
        );

        [$a, ] = arrayed([1, 2]);
        $this->assertEquals(
            1,
            $a
        );

        $this->assertTrue(
            arrayed([])->empty()
        );
    }

    public function testMergeRecursiveMethod()
    {
        $this->assertCount(
            2,
            arrayed(['a' => 1, 'b' => 2])
                ->merge(['b' => 4])
                ->mergeRecursive(['b' => 5])
                ->offsetGet('b') //[4, 5]]
        );
    }

    // Future possibility
    //            arrayed(\arrayed(1)(), \arrayed(2)())->map(fn($i) => $i[0]+1)->sum()->done()
}
