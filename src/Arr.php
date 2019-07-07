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
}
