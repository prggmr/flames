<?php
namespace flames\query;
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
class Base {

    /**
     * Fields to use in the query.
     *
     * @var  array|null
     */
    protected $_fields = null;

    /**
     * Models used within the query.
     */
    protected $_models = null;

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
        $this->_models[] = $model;
    }

    /**
     * Returns the model this query represents.
     *
     * @return  object
     */
    public function get_model(/* ... */)
    {
        return $this->_model[0];
    }

    /**
     * Executes the query.
     *
     * @return  object  \flames\Event
     */
    public function exec()
    {
        $name = array_pop(explode('\\', get_class($this)));
        $event = '\\flames\\events\\'.$name;
        $model = '\\flames\\signal\\model\\'.$name;
        $signal = '\\flames\\signal\\'.$name;
        $event = new $event($this, $this->build_query());
        $model_signal = \prggmr\signal(
            new $model($this->get_model()),
            $event
        );
        $select_signal = \prggmr\signal(
            new $signal(),
            $event
        );
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