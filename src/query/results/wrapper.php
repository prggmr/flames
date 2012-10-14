<?php
namespace flames\query\results;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * SELECT Query results wrapper
 *
 * Bundles the results from a SELECT query to allow for iteration and simple
 * retrieval of query results.
 */
class Wrapper implements \Iterator, \ArrayAccess {

    use \prggmr\Storage;


    /**
     * Query Result Model
     *
     * @var  object
     */
    protected $_model = null;

    /**
     * Constructs a new Result.
     *
     * @param  array  $array  Query result array
     * @param  object  $model  Model the result represents
     *
     * @return  void
     */
    public function __construct($array, $model)
    {
        $this->_storage = $array;
        $this->_model = get_class($model);
    }

    /**
     * Returns the first result in the record set.
     *
     * @return  object
     */
    public function first(/* ... */)
    {
        return $this->offsetGet(0);
    }

    /**
     * Returns the last result in the record set.
     *
     * @return  object
     */
    public function last(/* ... */)
    {
        return $this->offsetGet($this->count() - 1);
    }

    /**
     * Return the number of results found.
     *
     * @var  integer
     */
    public function count(/* ... */)
    {
        return count($this->_storage);
    }

    /**
     * Returns the current result node.
     *
     * @return  object
     */
    public function current(/* ... */)
    {
        $node = current($this->_storage);
        return $this->_make_model($node);
    }

    /**
     * Returns the last record in the result.
     *
     * @return  object
     */
    public function end(/* ... */)
    {
        return $this->_make_model(end($this->_storage));
    }

    /**
     * Returns the current node key.
     *
     * @return  integer
     */
    public function key(/* ... */)
    {
        return key($this->_storage);
    }

    /**
     * Returns the next record in the result.
     *
     * @return  object
     */
    public function next(/* ... */) 
    {
        $ode = $this->_make_model(next($this->_storage));
    }

    /**
     * Returns the previous record in the result.
     *
     * @return  object
     */
    public function prev(/* ... */)
    {
        return $this->_make_model(prev($this->_storage));
    }

    /**
     * Resets the pointer to the beginning of the result set.
     *
     * @return  void
     */
    public function reset(/* ... */) 
    {
        return reset($this->_storage);
    }

    /**
     * Returns if the current pointer position is valid.
     *
     * @return  boolean
     */
    public function valid(/* ... */)
    {
        return current($this->_storage) !== false;
    }

    /**
     * Rewinds the iterator to the beginning.
     *
     * @return  void
     */
    public function rewind(/* ... */) 
    {
        return $this->reset();
    }

    /**
     * Returns the model at the given index.
     * 
     * @param  integer  $offset  Index offset
     * 
     * @return  object|boolean  Model, False if invalid index
     */
    public function offsetGet($offset) 
    {
        if ($this->offsetExists($offset)) {
            return $this->_make_model($this->_storage[$offset]);
        }
        return false;
    }

    /**
     * Disallow change.
     * 
     * @return  void
     */
    public function offsetSet($offset, $value)
    {
        throw new \LogicException("Wrapper results cannot be modified");
    }

    /**
     * Remove a result.
     * 
     * @param  integer  $offset  Index offset
     * 
     * @return  boolean
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->_storage[$offset]);
            return true;
        }
        return false;
    }

    public function offsetExists($offset)
    {
        return isset($this->_storage[$offset]);
    }
    /**
     * Maps a result to a model object.
     *
     * @param  array  $array  Result array
     *
     * @return  object
     */
    protected function _make_model($array)
    {
        return new $this->_model($array,  true);
    }
}