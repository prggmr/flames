<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Password Field
 */
class Password extends Char {

    /**
     * Hash function to use when storing and comparing DB.
     */
    protected $_hash = 'sha1';

    /**
     * Password salt.
     */
    protected $_salt = '';

    /**
     * Default length
     */
    protected $_max_length = 75;

    /**
     * Sets the current value.
     *
     * @param  mixed  $val  Value to set the field.
     * @param  boolean  $db  Value it coming from db and already hashed.
     *
     * @return  object  this
     */
    public function set_value($val, $db = false)
    {
        if (!$db) {
            $hash_func = $this->_hash;
            $this->__value = $hash_func($this->_salt . $val . $this->_salt);
        } else {
            $this->__value = $val;
        }
    }
}