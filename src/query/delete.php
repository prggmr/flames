<?php
namespace flames\query;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Delete Query
 *
 * This allows for building and executing a DELETE statement
 */
class Delete extends \flames\Query {

    use Where;

    /**
     * Builds the SELECT statement query.
     *
     * @return  object  PDOStatement`
     */
    public function build_query(/* ... */)
    {
        $model = $this->get_model();
        // Add the primary key for the where
        $primary = $model->get_primary_key();
        $primary_name = $primary->get_name();
        if (null === $primary->get_value()) return false;
        $query = ["DELETE FROM"];
        $query[] = $model->get_table();
        // Add the primary key for the where
        $this->where([$primary_name => $primary->get_value()]);
        $query[] = $this->build_where();
        // Table Selection
        $exec = $model->get_connection()->prepare(implode(" ", $query));
        return $exec;
    }
}