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
     * Construct a field object
     */
    public function __construct($default = null)
    {
        $this->_default = $default;
    }

}