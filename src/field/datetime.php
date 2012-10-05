<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Datetime Field
 */
class Datetime extends \flames\Field {

    /**
     * Field create template
     */
    protected $_template = '`%s` datetime %s';

    /**
     * Default value for current time
     */
    const CURRENT_TIMESTAMP = 1;

    /**
     * Default
     */

    /**
     * Returns the field creation string.
     *
     * @return  string
     */
    public function get_db_field()
    {
        $default = ($this->_default == null) ? 'DEFAULT NULL' : 'NOT NULL';
        return sprintf($this->_template,
            $this->_name,
            $default
        );
    }

    /**
     * Sets the current value.
     *
     * @param  mixed  $val  Value to set the field.
     * @param  $db  @ignored
     *
     * @return  void
     */
    public function set_value($val, $db = false)
    {
        $this->__value = strtotime($val);
    }

    /**
     * Gets the current value converted for the database.
     *
     * @return  mixed
     */
    public function get_bind_value(/* ... */)
    {
        if (null === $this->__value) {
            $this->__value = time();
        }
        return \flames\mysql_datetime($this->__value);
    }
}