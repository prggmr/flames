<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * PrimaryKey Field
 */
class Primary extends \flames\field\Integer {

    /**
     * Field type
     */
    protected $_type = 'int';

    /**
     * Field create template
     */
    protected $_template = '`%s` %s(%s) unsigned %s AUTO_INCREMENT';

    /**
     * Returns the field keys.
     *
     * @return  null|string
     */
    public function get_db_keys(/* ... */)
    {
        return sprintf(
            'PRIMARY KEY (`%s`)',
            $this->_name
        );
    }

    /**
     * Sets the current value.
     *
     * @param  mixed  $val  Value to set the field.
     *
     * @return  object  this
     */
    public function set_value($val)
    {
        if ($val !== null) {
            $val = intval($val);
        }
        $this->__value = $val;
    }
}