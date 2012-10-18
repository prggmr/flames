<?php
namespace flames\query\operator;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Base SELECT operators
 *
 * This allows for building operations into select statements.
 */
class Base {

    /**
     * Field used in the lookup.
     *
     * @var  string
     */
    protected $_field = null;

    /**
     * Constructs the class.
     *
     * @param  object  $field  \flames\Field
     *
     * @return  void
     */
    public function __construct($field)
    {
        $this->_field = $field;
    }

    /**
     * Generates the operation
     *
     * @return  string
     */
    public function get_operation(/* ... */)
    {
        throw new \RuntimeException('Method not implemented');
    }
}