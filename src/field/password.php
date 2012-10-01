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
     * Sets the current value.
     *
     * @param  mixed  $val  Value to set the field.
     *
     * @return  object  this
     */
    public function set_value($val)
    {
        $hash_func = $this->_hash;
        $this->__value = $hash_func($val);
    }

    /**
     * Gets the current value converted for the database.
     *
     * @return  mixed
     */
    public function get_db_value(/* ... */)
    {
        if (null === $this->__value) {
            $this->__value = time();
        }
        return \flames\mysql_datetime($this->__value);
    }
}