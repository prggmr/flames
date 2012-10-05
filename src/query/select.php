<?php
namespace flames\query;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Select Query
 *
 * This allows for building and executing a SELECT statement
 */
class Select extends \flames\Query {

    use Where;

    /**
     * Builds the SELECT statement query.
     *
     * @return  object  PDOStatement`
     */
    public function build_query(/* ... */)
    {
        $query = ["SELECT"];
        $model = $this->get_model();
        // Field selection
        $fields = [];
        foreach ($this->_fields as $_field) {
            if (!$_field instanceof \flames\Field) {
                $_field = $model->get_field($_field);
            }
            $fields[] = sprintf('`%s`', $_field->get_db_field_name());
        }
        $query[] = implode(', ', $fields);
        // Table Selection
        $query[] = 'FROM';
        $query[] = $model->get_table();
        // WHERE lookup
        $query[] = $this->build_where();
        return $model->get_connection()->prepare(implode(" ", $query));
    }
}