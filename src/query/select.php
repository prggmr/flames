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
            $fields[] = $this->_build_select_field($_field);
        }
        $query[] = implode(', ', $fields);
        // Table Selection
        $query[] = 'FROM';
        $query[] = '`' . $model->get_table() . '`';
        // WHERE lookup
        $query[] = $this->build_where();
        if (null !== $this->_orderby) {
            $query[] = $this->_orderby;
        }
        if (null !== $this->_limit) {
            $query[] = $this->_limit;
        }
        return $model->get_connection()->prepare(implode(" ", $query));
    }

    /**
     * Builds a field for selection this allows for select modifiers such as 
     * AVG, ROUND, MIN, MAX etc by using field__(operator)
     *
     * @param  string  $field  Field name
     *
     * @return  string
     */
    protected function _build_select_field($field)
    {
        if (strpos($field, '__') === false) {
            if (!$field instanceof \flames\Field) {
                $field = $this->get_model()->get_field($field);
            }
            return sprintf('`%s`', 
                $field->get_db_field_name()
            );
        }
        list($field, $operator) = explode('__', $field);
        $operator = '\\flames\\query\\operator\\'.ucfirst($operator);
        if (!$field instanceof \flames\Field) {
            $field = $this->get_model()->get_field($field, true);
        }
        $operator = new $operator($field);
        return $operator->get_operation();
    }
}