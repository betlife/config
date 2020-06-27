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
use InvalidArgumentException;

class Config extends Repository
{

    /**
     *
     * @return array
     */
    public function configs()
    {
        return $this->items();
    }

    /**
     *
     * @param string $key
     * @return string
     */
    public function exists($key)
    {
        return Arr::has($this->items, $key);
    }

    /**
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    /**
     *
     * @param string|null $key
     * @return array
     */
    public function loaded($key = null)
    {
        return !is_null($key) ? $this->offsetGet($key) : $this->items;
    }

    /**
     *
     * @param string $key
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function unload($key)
    {
        if ( !$this->exists($key) ) {
            throw new InvalidArgumentException('Key not found.');
        }

        \Fluent\Support\HashTable::remove($key, $this->items);

        return true;
    }

    /**
     *
     * @param mixed $value
     * @param string|null $key
     * @return array
     */
    public function prepend($value, $key = null)
    {
        return Arr::prepend($this->items, $value, $key);
    }

}
