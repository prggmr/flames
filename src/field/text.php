<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Text field
 */
class Text extends \flames\Field {

    /**
     * Field type
     */
    protected $_type = 'text';

    /**
     * Field create template
     */
    protected $_template = '`%s` text %s';

    /**
     * Returns the field creation string.
     *
     * @return  string
     */
    public function get_db_field()
    {
        $default = ($this->_default == null) ? null : 
            ($this->_default == '') ? 'NOT NULL' : null;
        return sprintf($this->_template,
            $this->_name,
            $default
        );
    }
}