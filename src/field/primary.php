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
class Primary extends \flames\field\Integer {

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
}