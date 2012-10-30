<?php
namespace flames\query\lookup;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * __notnull lookup
 *
 * Builds f IS NOT NULL
 */
class Notnull extends Base {

    /**
     * Returns the SQL Where lookup.
     *
     * @return  string
     */
    public function get_lookup(/* ... */)
    {
        return sprintf(
            "`%s` IS NOT NULL",
            $this->_field->get_db_field_name()
        );
    }

}