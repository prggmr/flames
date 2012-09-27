<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * The INTEGER
 */
class Integer extends \flames\Field {

    /**
     * Field type
     */
    protected $_type = 'int';

    /**
     * Default
     */
    protected $_max_length = 11;

    /**
     * Field create template
     */
    protected $_template = '`%s` %s(%s) unsigned %s';

    /**
     * Sets the current value.
     *
     * @param  mixed  $val  Value to set the field.
     *
     * @return  object  this
     */
    public function set_value($val)
    {
        $this->__value = intval($val);
    }
}