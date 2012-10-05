<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Decimal
 */
class Decimal extends \flames\Field {
    /**
     * Field type
     */
    protected $_type = 'decimal';

    /**
     * Length
     * First Number - How many integers
     * Second Number - How many decimal spaces
     */
    protected $_max_length = "5,2";

    /**
     * Default value
     * Decimal places according to the max length will be added
     */
    protected $_default = 0;

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
        $this->__value = floatval($val);
    }
}