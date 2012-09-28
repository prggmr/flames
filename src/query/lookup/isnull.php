<?php
namespace flames\query\lookup;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * __isnull lookup
 *
 * Builds f IS NULL
 */
class Isnull extends Base {

    /**
     * Returns the SQL Where lookup.
     *
     * @return  string
     */
    public function get_lookup(/* ... */)
    {
        return sprintf(
            "`%s` IS NULL",
            $this->_field
        );
    }

}