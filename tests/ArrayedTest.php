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
        $this->assertEquals(3, arrayed(1, 2)->sum()->done());
        $this->assertEquals(
            5,
            arrayed(1, 2, 'ninja')
                ->filter(fn($val) => is_int($val))
                ->map(fn($val) => $val + 1)
                ->sum()()
        );

        $this->assertEquals(
            7,
            arrayed(1, 2)->map(fn($i) => $i + 1)->reduce(fn($v, $i) => $v + $i, 2)()
        );
    }

    public function testPassingAnArray()
    {
        $this->assertEquals(
            7,
            arrayed([1, 2, 'ninja'])
                ->filter(fn($val) => is_int($val))
                ->map(fn($val) => $val + 1)
                ->sum()()
        );
    }

    // Future possibility
    //            arrayed(\arrayed(1)(), \arrayed(2)())->map(fn($i) => $i[0]+1)->sum()->done()
}
