<?php
namespace flames;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * The base field!
 */
class Field {

    /**
     * The default value of this field.
     */
    protected $_default = null;

    /**
     * Returns the default value for this field.
     *
     * @return  null|string|int
     */
    public function get_default_value()
    {
        return $this->_default;
    }

}