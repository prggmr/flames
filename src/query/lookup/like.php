<?php
namespace flames\query\lookup;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * __like lookup
 *
 * Builds f LIKE ?
 */
class Like extends Base {

    /**
     * Returns the SQL Where lookup.
     *
     * @return  string
     */
    public function get_lookup(/* ... */)
    {
        return sprintf(
            '`%s` LIKE %s',
            $this->_field->get_db_field_name(),
            $this->_key
        );
    }

    /**
     * Returns the value
     *
     * @return  string
     */
    public function get_bind_value(/* ... */)
    {
        return '%'.$this->_field->get_value().'%';
    }
}