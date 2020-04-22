<?php

use Transprime\Arrayed\Arrayed;

if (! function_exists('arrayed')) {
    /**
     * New up a Arrayed
     *
     * @param $value
     * @param null $callback
     * @return Arrayed
     */
    function arrayed($value, $other = null) {
        return new Arrayed();
    }
}