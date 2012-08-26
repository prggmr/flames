<?php
namespace flames\query;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Select Query
 *
 * This allows for building and executing a SELECT statement
 */
class Select {

    /**
     * Fields to select in the query.
     *
     * @var  array|null
     */
    protected $_fields = null;

    /**
     * Models used within the select query.
     * 
     */
    protected $_models = null;

    /**
     * Constructs a new select query.
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
     * Executes the select query.
     *
     * @return  object  \flames\Event
     */
    public function exec()
    {
        $event = new \flames\events\Select($this->_models[0], 'SELECT * FROM nowhere');
        $model_signal = \prggmr\signal(
            new \flames\signal\model\Select($this->_models[0]),
            $event
        );
        $select_signal = \prggmr\signal(
            new \flames\signal\Select(),
            $event
        );
    }
}