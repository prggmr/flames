<?php
namespace flames;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Base Query
 *
 * This allows for building and executing SQL statements.
 */
class Query {

    /**
     * Fields to use in the query.
     *
     * @var  array|null
     */
    protected $_fields = [];

    /**
     * Models the query belongs to.
     */
    protected $_model = null;

    /**
     * Constructs a new query.
     *
     * @param  null|array  Fields to select.
     *
     * @return  object
     */
    public function __construct($fields = null, $model = null) 
    {
        $this->_fields = $fields;
        $this->_model = $model;
    }

    /**
     * Returns the model this query represents.
     *
     * @return  object
     */
    public function get_model(/* ... */)
    {
        return $this->_model;
    }

    /**
     * Executes the query
     *
     * @param  boolean  $return_event  Return the event rather than a Result 
     *                                 object.
     *
     * @return  object  \flames\Event
     */
    public function exec($return_event = false)
    {
        $array = explode('\\', get_class($this));
        $name = array_pop($array);
        $event = '\\flames\\query\\event\\'.$name;
        $model = '\\flames\\signal\\model\\'.$name;
        $signal = '\\flames\\signal\\'.$name;
        $query = $this->build_query();
        if (false === $query) {
            return false;
        }
        $event = new $event($this, $query);
        $model_signal = \prggmr\signal(
            new $model($this->get_model()),
            $event
        );
        $select_signal = \prggmr\signal(
            new $signal(),
            $event
        );
        return ($return_event) ? $event : $event->get_result();
    }

    /**
     * Builds the statement query.
     *
     * @return  object  PDOStatement
     */
    public function build_query(/* ... */)
    {
        throw new \RuntimeException("Method not implemented");
    }
}