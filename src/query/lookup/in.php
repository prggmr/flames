<?php
namespace flames\query\lookup;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * __in lookup
 * 
 * Builds f IN [?, ?, ?]
 */
class In extends Base {

    public function __construct()
    {
        throw new \RuntimeException("In lookups are currently not supported");
    }

    /**
     * Returns the SQL Where lookup.
     *
     * @return  string
     */
    public function get_lookup(/* ... */)
    {
        return sprintf(
            "%s IN [%s]",
            $this->_field->get_db_field_name(),
            $this->_key
        );
    }

}