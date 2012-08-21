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
     * The model object this event represents
     *
     * @var  object
     */
    protected $_model = null;

    /**
     * The sql query code generated for the given event.
     *
     * @var  string
     */
    public $sql = null;

    /**
     * The original SQL code injected into the event.
     *
     * @var  string
     */
    protected $_original_sql = null;

    /**
     * Constructs a new flames event.
     *
     * @param  object  $model
     * @param  object  $sql
     *
     * @return  void
     */
    public function __construct($model, $sql)
    {
        $this->_model = $model;
        $this->_original_sql = $this->sql = $sql;
    }
    
    /**
     * Returns the original SQL statement given to the event.
     *
     * @return  boolean
     */
    public function get_original_sql(/* ... */) 
    {
        return $this->_original_sql;
    }

    /**
     * Returns the model being used in the signal.
     *
     * @return  object
     */
    public function get_model(/* ... */)
    {
        return $this->_model;
    }
}