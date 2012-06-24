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
     * Returns the field creation string.
     *
     * @return  string
     */
    public function get_db_field()
    {
        $default = ($this->_default == null) ? 'DEFAULT NULL' : 
            ($this->_default == 'current_timestamp') ? 'CURRENT_TIMESTAMP' :
            null;
        return sprintf($this->_template,
            $this->_name,
            $default
        );
    }
}