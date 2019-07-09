<?php

namespace marciojc;

class Arr
{
    /**
     * Add an element to an array.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    public static function add($array, $key, $value)
    {
        if (is_null(static::get($array, $key))) {
            static::set($array, $key, $value);
        }
        return $array;
    }

    /**
     * Get an item from an array.
     *
     * @param  array  $array
     * @param  int  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        return $array;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  array  $array
     * @param  int  $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        return array_key_exists($key, $array);
    }

    /**
     * Get the length of an array.
     *
     * @param  array  $array
     * @return int
     */
    public static function length($array)
    {
        return count($array);
    }

    /**
     * Filter the array using the given callback.
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return array
     */
    public static function filter($array, callable $callback)
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Returns the value of the first element using the given callback.
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return mixed|null
     */
    public static function find($array, callable $callback)
    {
        $found = false;
        $i = 0;
        $total = count($array);
        $result = null;

        while (!$found && $i < $total) {
            $item = $array[$i];

            if ($callback($item, $i)) {
                $result = $item;
                $found = true;
            }

            $i++;
        }

        return $result;
    }

    /**
     * Flat a multi-dimensional array into a single level.
     *
     * @param  array  $array
     * @param  int  $depth
     * @return array
     */
    public static function flat($array, $depth = INF)
    {
        $result = [];

        foreach ($array as $item) {
            if (! is_array($item)) {
                $result[] = $item;
            } else {
                $values = $depth === 1
                    ? array_values($item)
                    : static::flat($item, $depth - 1);
                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Merge two arrays
     *
     * @param  array1  $array
     * @param  array2  $array
     * @return array
     */
    public static function concat($array1, $array2)
    {
        return array_merge($array1, $array2);
    }
}
