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
class Select extends Base {

    use Where;

    /**
     * Builds the SELECT statement query.
     *
     * @return  object  PDOStatement`
     */
    public function build_query(/* ... */)
    {
        $query = "SELECT";
        // Field selection
        $fields = [];
        foreach ($this->_fields as $_field) {
            $fields[] = sprintf('`%s`', $_field);
        }
        $query .= ' '.implode(', ', $fields);
        // Table Selection
        $tables = [];
        foreach ($this->_models as $_model) {
            $tables[] = $_model->get_table();
        }
        $query .= ' FROM '.implode(', ', $tables);
        // WHERE lookup
        $query .= $this->build_where();
        return $this->_models[0]->get_connection()->prepare($query);
    }
}