<?php
namespace flames\query\operator;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * SUM SELECT operator
 *
 * Allows for performing SUM(field) AS field
 */
class Sum extends Base {

    /**
     * Generates the operation
     *
     * @return  string
     */
    public function get_operation(/* ... */)
    {
        $field = $this->_field->get_db_field_name();
        return sprintf('SUM(`%s`) AS `%s`',
            $field,
            $field
        );
    }
}