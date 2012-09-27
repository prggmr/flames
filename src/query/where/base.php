<?php
namespace flames\query\where;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Base Where Clause
 *
 * This allows for building where clause statements.
 */
class Base {

    /**
     * Field used in the clause.
     *
     * @var  string
     */
    protected $_field = null;

    /**
     * Key for the PDO Bind
     *
     * @var  string
     */
    protected $_key = null;

    /**
     * Constructs the class.
     *
     * @param  string  $field  
     * @param  string|null  $key  PDO bind key
     * @param  mixed  $value  The value for the clause
     *
     * @return  void
     */
    public function __construct($field, $key = null, $value = null)
    {
        $this->_field = $field;
        $this->_key = $key;
    }

    /**
     * Returns the SQL Where clause.
     *
     * @return  string
     */
    public function get_clause(/* ... */)
    {
        throw new \RuntimeException('Method not implemented');
    }

    /**
     * Returns the field value.
     *
     * @return  string
     */
    public function get_value(/* ... */)
    {
        return $this->_value;
    }
}