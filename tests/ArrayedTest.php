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
}