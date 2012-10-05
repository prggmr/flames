<?php
namespace flames\query\lookup;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Base Where lookup
 *
 * This allows for building where lookup statements.
 */
class Base {

    /**
     * Field used in the lookup.
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
     * @param  mixed  $value  The value for the lookup
     *
     * @return  void
     */
    public function __construct($field, $key = null)
    {
        $this->_field = $field;
        $this->_key = $key;
    }

    /**
     * Returns the SQL Where lookup.
     *
     * @return  string
     */
    public function get_lookup(/* ... */)
    {
        throw new \RuntimeException('Method not implemented');
    }
}