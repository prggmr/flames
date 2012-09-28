<?php
namespace flames\query;
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
     * Result of the SQL query statement.
     *
     * @var  string
     */
    protected $_result = null;

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

    /**
     * Sets the results of the query.
     *
     * @param  boolean|object|integer  $result  Result of the query
     *
     * @return  void
     */
    public function set_result($result)
    {
        $this->_result = $result;
    }

    /**
     * Returns the event query results.
     *
     * @return  boolean|object|integer
     */
    public function get_result(/* ... */)
    {
        return $this->_result;
    }
}