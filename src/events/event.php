<?php
namespace flames\events;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Base event used for models to issue - SELECT, UPDATE, DELETE and INSERT
 * commands.
 */
class Event extends \prggmr\Event {

    /**
     * The query object this event represents
     *
     * @var  object
     */
    protected $_query = null;

    /**
     * The sql statement for the given event.
     *
     * @var  string
     */
    protected $_statement = null;

    /**
     * Constructs a new flames query event.
     *
     * @param  object  $model
     * @param  object  $sql
     *
     * @return  void
     */
    public function __construct($query, \PDOStatement $statement)
    {
        $this->_query = $query;
        $this->_statement = $statement;
    }

    /**
     * Returns the SQL statement for the event.
     *
     * @return  boolean
     */
    public function get_statement(/* ... */) 
    {
        return $this->_statement;
    }

    /**
     * Returns the query being used in the signal.
     *
     * @return  object
     */
    public function get_query(/* ... */)
    {
        return $this->_query;
    }
}