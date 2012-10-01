<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * ENUM field
 */
class Enum extends \flames\Field {

    /**
     * Field type
     */
    protected $_type = 'enum';

    /**
     * Field create template
     */
    protected $_template = '`%s` ENUM(\'%s\') %s';

    /**
     * Choices for the field.
     */
    protected $_choices = [];

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
            implode('\',\'', $this->_choices),
            $default
        );
    }
}