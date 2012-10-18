<?php
namespace flames\query\operator;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * MIN SELECT operator
 *
 * Allows for performing MIN(field) AS field
 */
class Min extends Base {

    /**
     * Generates the operation
     *
     * @return  string
     */
    public function get_operation(/* ... */)
    {
        $field = $this->_field->get_db_field_name();
        return sprintf('MIN(`%s`) AS `%s`',
            $field,
            $field
        );
    }
}