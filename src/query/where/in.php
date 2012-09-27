<?php
namespace flames\query\where;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * __in Clause
 *
 * Builds f IN [?, ?, ?]
 */
class In extends Base {

    /**
     * Returns the SQL Where clause.
     *
     * @return  string
     */
    public function get_clause(/* ... */)
    {
        return sprintf(
            "%s IN [%s]",
            $this->_field,
            $this->_key
        );
    }

}