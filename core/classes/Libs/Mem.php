<?php

namespace Daxdoxsi\Abcphp\Libs;

class Mem
{
    public static array $storage = [];

    /**
     * @param string $varName
     * @return mixed
     */
    public static function get(string $varName): mixed
    {
        return self::$storage[$varName] ?? '';
    }

    /**
     * @param string $varName
     * @param mixed $value
     */
    public static function set(string $varName, mixed $value): void
    {
        self::$storage[$varName] = $value;
    }

}