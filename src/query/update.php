<?php
namespace flames\query;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Update Query
 *
 * This allows for building and executing a UPDATE statement
 */
class Update extends \flames\Query {

    use Where;

    /**
     * Builds the SELECT statement query.
     *
     * @return  object  PDOStatement`
     */
    public function build_query(/* ... */)
    {
        $model = $this->get_model();
        if (!$model->is_dirty()) {
            return false;
        }
        $query = ["UPDATE"];
        $query[] = $model->get_table();
        $query[] = 'SET';
        // Field selection
        $values = [];
        foreach ($this->_fields as $_field) {
            if ($_field instanceof \flames\field\Primary) {
                continue;
            }
            if ($_field instanceof \flames\field\ForeignKey) {
                $fk = $_field->get_value(false);
                if (is_object($fk)) {
                    $fk->save()->exec();
                }
            }
            $name = $_field->get_db_field_name();
            $value = $_field;
            $bind = $this->to_bind_var($name);
            $this->_bind($name, $value);
            $values[] = sprintf(
                "%s = %s",
                sprintf('`%s`', $name),
                $_field->get_save_function($bind)
            );
        }
        $query[] = implode(", ", $values);
        // Add the primary key for the where
        $primary = $model->get_primary_key();
        $this->where([$primary->get_name() => $primary->get_value()]);
        $query[] = $this->build_where();
        // Table Selection
        $exec = $model->get_connection()->prepare(implode(" ", $query));
        $model->clean();
        return $exec;
    }
}