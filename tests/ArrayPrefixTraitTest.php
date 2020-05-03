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
}