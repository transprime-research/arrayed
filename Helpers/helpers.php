<?php

use Transprime\Arrayed\Arrayed;
use Transprime\Arrayed\Interfaces\ArrayedInterface;

if (! function_exists('arrayed')) {
    /**
     * New up a Arrayed
     *
     * @param $value
     * @return ArrayedInterface
     */
    function arrayed(...$value)
    {
        return new Arrayed(...$value);
    }
}