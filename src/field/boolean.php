<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Boolean
 */
class Boolean extends \flames\Field {
    /**
     * Field type
     */
    protected $_type = 'tinyint';

    /**
     * Max length
     */
    protected $_max_length = 1;

    /**
     * Default value
     */
    protected $_default = 0;


    /**
     * Sets the current value.
     *
     * @param  mixed  $val  Value to set the field.
     *
     * @return  object  this
     */
    public function set_value($val)
    {
        $this->__value = (true == $val);
    }
}