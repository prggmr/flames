<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Binary
 */
class AES_Encrypt extends Binary {
    
    /**
     * The Hash key.
     *
     * @var  string
     */
    protected $_key = '';

    /**
     * Returns a SQL wrapper function to use for insert/update for a field.
     *
     * @return  string
     */
    public function get_save_function($bind)
    {
        return sprintf(
            'AES_ENCRYPT(%s, "%s")',
            $bind,
            $this->_key
        );
    }
}