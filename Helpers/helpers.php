<?php

use Transprime\Arrayed\Arrayed;

if (! function_exists('arrayed')) {
    /**
     * New up a Arrayed
     *
     * @param $value
     * @return Arrayed
     */
    function arrayed(...$value) {
        return new Arrayed(...$value);
    }
}