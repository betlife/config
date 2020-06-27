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

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class Repository implements Countable, ArrayAccess, IteratorAggregate
{

    /**
     *
     * @var array
     */
    protected $items = [];

    /**
     * Create new instance of repository
     *
     * @param mixed $items
     * @return void
     */
    public function __construct($items = [])
    {
        foreach ( $items as $key => $value ) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     *
     * @return array
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * Returns the items count
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Returns instance of array iterator with items
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Does the offset exists in items?
     *
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     *
     * @param string $offset
     * @param mixed  $value
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     *
     * {@inheritDoc}
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     *
     * {@inheritDoc}
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     *
     * {@inheritDoc}
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    /**
     *
     * {@inheritDoc}
     */
    public function __unset($name)
    {
        $this->offsetUnset($name);
    }

}
