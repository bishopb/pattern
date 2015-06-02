<?php
namespace BishopB\Pattern;

class EventFixtures
{
    static public function with($t)
    {
        return $t;
    }

    static public function prime()
    {
        tiny();
        small();
        medium();
        large();
        huge();
    }

    static public function single($char = 'a')
    {
        return $char;
    }

    static public function tiny($char = 'a')
    {
        return static::stringer($char, 2);
    }

    static public function small($char = 'a')
    {
        return static::stringer($char, 4);
    }

    static public function medium($char = 'a')
    {
        return static::stringer($char, 8);
    }

    static public function large($char = 'a')
    {
        return static::stringer($char, 16);
    }

    static public function huge($char = 'a')
    {
        return static::stringer($char, 24);
    }

    static public function stringer($char, $size)
    {
        $key = $char . $size;
        if (empty(static::$cache[$key])) {
            static::$cache[$key] = str_repeat($char, 2 << $size);
        }
        return static::$cache[$key];
    }

    static protected $cache = array ();
}
