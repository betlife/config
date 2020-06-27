<?php

/**
 *
 * (c) Suurshater Gabriel <suurshater.ihyongo@st.futminna.edu.ng>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author   Suurshater Gabriel
 * @version  1.0
 */

namespace Fluent\Config;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Fluent\Contracts\Config\Adapter;
use Fluent\Config\Adapters\ArrayAdapter;

class Configure
{

    /**
     *
     * @var array
     */
    protected static $values = [];

    /**
     *
     * @var array
     */
    protected static $engines = [];

    /**
     *
     * @param string $key
     * @return bool
     */
    public static function exists($key)
    {
        return Arr::has(static::$values, $key);
    }

    /**
     *
     * @return array
     */
    public static function values()
    {
        return static::$values;
    }

    /**
     *
     * @return \Illuminate\Support\Collection
     */
    public static function collection()
    {
        return Collection::make(static::$values);
    }

    /**
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return Arr::get(static::$values, $key, $default);
    }

    /**
     *
     * @param type $name
     * @param \Fluent\Contracts\Config\Adapter $engine
     */
    public static function create($name, Adapter $engine)
    {
        Arr::set(static::$engines, $name, $engine);
    }

    /**
     *
     * @param string $name
     * @param bool   $merge
     * @return void
     */
    public static function load($name, $merge = true)
    {
        $engine = static::engine($name);
        if ( !$engine ) {
            return;
        }

        $values = $engine->read();

        static::write($merge ? array_merge(static::$values, $values) : $values);
    }

    /**
     *
     * @param string $name
     * @return void
     */
    public static function unload($name)
    {
        if ( !static::configured($name) ) {
            return;
        }

        unset(static::$engines[$name]);
    }

    /**
     * 
     * @return void
     */
    public static function reset()
    {
        static::$values = [];
    }

    /**
     *
     * @param string $name
     * @param mixed  $data
     * @param string $to
     * @return boolean
     */
    public static function dump($name, $data, $to)
    {
        $engine = static::engine($name);

        if ( $engine ) {
            return $engine->write($data, $to);
        }

        return false;
    }

    /**
     *
     * @param string|array $key
     * @param mixed        $value
     */
    public static function write($key, $value = null)
    {
        if ( !is_null($value) ) {
            $key = [$key => $value];
        }

        foreach ( $key as $k => $v ) {
            Arr::set(static::$values, $k, $v);
        }
    }

    /**
     *
     * @param string $name
     * @return array|\Fluent\Config\EngineInterface
     */
    public static function loaded($name = null)
    {
        return !is_null($name) ? static::$engines[$name] : array_keys(static::$engines);
    }

    /**
     *
     * @param string $name
     * @return bool
     */
    public static function configured($name)
    {
        return isset(static::$engines[$name]);
    }

    /**
     *
     * @param string $name
     * @return void|\Fluent\Contracts\Config\Adapter
     */
    protected static function engine($name)
    {
        if ( !static::configured($name) ) {
            if ( $name != 'default' ) {
                return;
            }

            static::create($name, new ArrayAdapter);
        }

        return static::$engines[$name];
    }

}
