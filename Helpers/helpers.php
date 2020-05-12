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

if (! function_exists('configured')) {
    /**
     * Get config value
     *
     * <br> Up to firs level without Laravel config
     *
     * @param $value
     * @param null $default
     * @return mixed
     */
    function configured($value, $default = null)
    {
        if (function_exists('config')) {
            return config($value, $default);
        }

        $keys = explode('.', $value);

        $data = require (__DIR__.'/../config/'.$keys[0].'.php');

        foreach ($keys as $index => $key) {
            if ($index < 1) {
                continue;
            }

            return $data[$key] ?? $default;
        }

        return $data;
    }
}