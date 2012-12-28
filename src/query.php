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
     * ORDER BY clause
     */
    protected $_orderby = null;

    /**
     * LIMIT clause
     */
    protected $_limit = null;

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
        $signal = '\\flames\\signal\\'.$name;
        $query = $this->build_query();
        if (false === $query) {
            return false;
        }
        $event = new $event($this, $query);
        emit(
            new $signal($this->get_model()),
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

    /**
     * Adds an order by clause to the query.
     *
     * @param  string  $field  Field to order by
     * @param  string  $dir  Direction of the order.
     *
     * @return  this
     */
    public function order_by($field, $dir = 'ASC')
    {
        if (strpos($field, ',') !== false) {
            $field = explode(',', $field);
        } else {
            $field = [$field];
        }

        $fields = [];
        foreach ($field as $_field) {
            try {
                $_field = $this->get_model()->get_field($_field);
                $fields[] = sprintf('`%s`', $_field->get_db_field_name());
            } catch (\Exception $e) {
                // Non field name such as RAND()
                $fields[] = $_field;
            }
        }

        $this->_orderby = sprintf('ORDER BY %s %s', 
            implode(", ", $fields), 
            strtoupper($dir)
        );

        return $this;
    }

    /**
     * Adds a LIMIT clause to the query.
     *
     * @param  integer  $start  Start of the limit
     * @param  integer  $amount  Amount of results to return.
     *
     * @return  this
     */
    public function limit($start, $amount)
    {
        $this->_limit = sprintf('LIMIT %d,%d',
            intval($start),
            intval($amount)
        );
        return $this;
    }
}