<?php

namespace Transprime\Arrayed\Tests;

use PHPUnit\Framework\TestCase;
use Transprime\Arrayed\{Arrayed, Interfaces\ArrayedInterface};

class ArrayedTest extends TestCase
{
    public function testArrayedIsCreated()
    {
        $this->assertIsObject(new Arrayed());
    }

    public function testIsArrayed()
    {
        $this->assertInstanceOf(ArrayedInterface::class, new Arrayed());
        $this->assertInstanceOf(ArrayedInterface::class, arrayed());
        $this->assertInstanceOf(ArrayedInterface::class, Arrayed::on());
    }

    public function testInitialDataIsCorrectlyRetrieved()
    {
        $data = ['1', 2];
        $data2 = ['1', 2];

        $this->assertSame(arrayed($data)->keys()->raw(), $data);
        $this->assertSame(arrayed(...$data2)->keys()->raw(), $data2);
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
                ->merge(['z' => 26], ['c' => 2])()
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

        [$a,] = arrayed([1, 2]);
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

    public function testFlipMethod()
    {
        $this->assertEquals(
            'b',
            arrayed(['a' => 1, 'b' => 2])
                ->flip()
                ->offsetGet(2) //b
        );
    }

    public function testIntersectMethod()
    {
        $this->assertCount(
            1,
            arrayed(['a' => 'z', 'b' => 'y'])
                ->flip()
                ->intersect(['z' => 'a'])
        );

        $this->assertCount(
            2,
            arrayed(['a' => 'z', 'b' => 'y', 'c'])
                ->intersect(arrayed(['a' => 'z', 'c', 'm'])())
        );

        $this->assertCount(
            1,
            arrayed(['a' => 'z', 'b' => 'y', 'c'])
                ->flip()
                ->intersect(['a', 0], [0])
        );

        $this->assertCount(
            1,
            arrayed(['a' => 'z', 'b' => 'y', 'c'])
                ->flip()
                ->intersect(['a', 0])
                ->intersect([0])
        );
    }

    public function testValuesMethod()
    {
        $this->assertEquals(
            ['a', 'b'],
            arrayed(['a' => 'z', 'b' => 'y'])
                ->flip()
                ->values()()
        );

        $this->assertSameSize(
            ['a', 'b'],
            arrayed(['a' => 'z', 'b' => 'y'])
                ->flip()
                ->values()
        );
    }

    public function testKeysMethod()
    {
        $this->assertEquals(
            ['z', 'y'],
            arrayed(['a' => 'z', 'b' => 'y'])
                ->flip()
                ->keys()()
        );
    }

    public function testContainsMethod()
    {
        $this->assertTrue(
            arrayed(['a' => 'z', 'b' => 'y'])
                ->contains('y')
        );

        $this->assertTrue(
            arrayed(['a' => 'www', 'b' => 'dot', 'c' => null])
                ->contains('')
        );

        $this->assertFalse(
            arrayed(['a' => 'www', 'b' => 'dot', 'c' => null])
                ->contains('', true)
        );
    }

    public function testUsingArrayedAsConstructorValues()
    {
        $this->assertEquals(
            5,
            arrayed(\arrayed(1), \arrayed(2))
                ->map(fn($i) => $i[0] + 1)
                ->sum()
        );
    }

    public function testWithPiper()
    {
        $this->assertEquals(
            ['c' => 'com'],
            arrayed(['a' => 'www', 'b' => 'dot'])
                ->flip()
                ->pipe('array_diff', ['www' => 'a', 'c' => 'com'])()
        );

        $this->assertEquals(
            ['com' => 1],
            arrayed(['a' => 'www', 'b' => 'dot'])
                ->flip()
                ->pipe('array_diff', ['www' => 'a', 'c' => 'com'])
                ->pipe('array_count_values')()
        );

        $this->assertEquals(
            ['a', 'b'],
            arrayed(['a' => 'www', 'b' => 'dot', 'c' => 'www'])
                ->pipe('array_unique')
                ->flip()
                ->values()()
        );
    }

    public function testExtraCallableOnResultMethod()
    {
        $this->assertEquals(
            'name,age',
            arrayed(['a' => 'name', 'b' => 'age'])
                ->values()
                ->result(fn($val) => implode(',', $val))
        );

        $this->assertEquals(
            'name,age',
            arrayed(['a' => 'name', 'b' => 'age'])
                ->values()(fn($val) => implode(',', $val))
        );

        $this->assertEquals(
            'nameage',
            arrayed(['a' => 'name', 'b' => 'age'])
                ->values()
                ->result('implode')
        );
    }

    public function testJsonable()
    {
        $data = ['a' => 1, 'b' => 2];

        $this->assertJson(arrayed($data));
        $this->assertStringContainsString(arrayed($data), json_encode($data));
        $this->assertEquals(json_encode($data), arrayed($data));
        $this->assertSame(json_encode($data), json_encode(arrayed($data)));
    }
}
