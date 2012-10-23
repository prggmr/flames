<?php
namespace flames\query;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Insert Query
 *
 * This allows for building and executing a INSERT statement
 */
class Insert extends \flames\Query {

    use Bind;

    /**
     * Builds the SELECT statement query.
     *
     * @return  object  PDOStatement`
     */
    public function build_query(/* ... */)
    {
        $query = ["INSERT INTO"];
        $model = $this->get_model();
        $query[] = '`' . $model->get_table() . '`';
        $query[] = '(';
        // Field selection
        $fields = [];
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
            $fields[] = sprintf('`%s`', $name);
            $values[] = $_field->get_save_function($bind);
        }
        $query[] = implode(", ", $fields);
        $query[] = ') VALUES (';
        $query[] = implode(", ", $values);
        $query[] = ')';
        // Table Selection
        // WHERE lookup
        $exec = $model->get_connection()->prepare(implode(" ", $query));
        $model->clean();
        return $exec;
    }
}