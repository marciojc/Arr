<?php

namespace marciojc;

use ArrayAccess;

class Arr
{

    /**
     * Determine whether the given value is array accessible.
     *
     * @param  mixed  $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  array   $array
     * @param  string  $prepend
     * @return array
     */
    public static function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && ! empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }

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
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param  array  $array
     * @param  string|array  $keys
     * @return bool
     */
    public static function has($array, $keys)
    {
        $keys = (array) $keys;

        if (! $array || $keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;

            if (static::exists($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (static::accessible($subKeyArray)
                    && static::exists($subKeyArray, $segment)
                ) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }
        return true;
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
     * Returns the index of the first element using the given callback.
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return mixed|null
     */
    public static function findIndex($array, callable $callback = null)
    {
        $found = false;
        $i = 0;
        $total = count($array);
        $result = -1;

        if (!is_null($callback) and !empty($array)) {
            while (!$found && $i < $total) {
                $item = $array[$i];

                if (call_user_func($callback, $item, $i)) {
                    $result = $i;
                    $found = true;
                }

                $i++;
            }
        }

        return $result;
    }

    /**
     * Returns the value of the first element using the given callback.
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return mixed|null
     */
    public static function find($array, callable $callback = null, $default = null)
    {
        $found = false;
        $i = 0;
        $total = count($array);
        $result = $default;

        if (is_null($callback)) {
            if (!empty($array)) {
                $result = $array[0];
            }
        } else {
            $key = self::findIndex($array, $callback);

            if ($key > -1) {
                $result = $array[$key];
            }
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

    /**
     * Map an array and flatten the result by a single level.
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return array
     */
    public static function flatMap($array, callable $callback)
    {
        return array_map($callback, self::flat($array));
    }

    /**
     * If the given value is not an array and not null, wrap it in one.
     *
     * @param  mixed  $value
     * @return array
     */
    public static function wrap($value)
    {
        if (is_null($value)) {
            return [];
        }
        return is_array($value) ? $value : [$value];
    }

    /**
     * Verify if all elments in the array pass the test implemented by the provided function
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return boolean
     */
    public static function every($array, callable $callback)
    {
        if (!is_array($array)) {
            return false;
        }

        $result = true;

        foreach ($array as $key => $value) {
            $result = $result && call_user_func($callback, $value, $key);
        }

        return $result;
    }

    /**
     * Verify if some elment in the array pass the test implemented by the provided function
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return boolean
     */
    public static function some($array, callable $callback)
    {
        if (!is_array($array)) {
            return false;
        }

        $result = false;
        $key = self::findIndex($array, $callback);

        if ($key > -1) {
            $result = true;
        }

        return $result;
    }

    /**
     * Verify if an array includes a certain value
     *
     * @param  array  $array
     * @param  string  $value
     * @return boolean
     */
    public static function contains($array, $value)
    {
        return in_array($value, $array);
    }

    /**
     * Remove one item from a given array.
     *
     * @param  array  $array
     * @param  string  $key
     * @return void
     */
    public static function forget(&$array, $value)
    {
        $original = &$array;
        $key = self::findIndex($array, function ($item, $index) use ($value) {
            if ($item === $value) {
                return $index;
            }
        });

        if ($key > -1) {
            unset($array[$key]);
        }
    }
}
