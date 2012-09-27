<?php
namespace flames\query;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Where Statement used for SELECT, INSERT, UPDATE, DELETE.
 */
trait Where {

    use Bind;

    /**
     * Fields to use in the clause.
     *
     * @var  array|null
     */
    protected $_where = null;

    /**
     * Values to bind
     */

    /**
     * Provides a where clause based on the given array for the SQL query.
     *
     * @param  array  $params
     *
     * @return  $this
     */
    public function where($fields)
    {
        if (!is_array($fields)) {
            throw new \InvalidArgumentException(
                "Where fields must be an array"
            );
        }
        $this->_where = $fields;
        return $this;
    }

    /**
     * Builds the where clause.
     *
     * Currently only simple where clauses are supported.
     *
     * @return  string
     */
    public function build_where(/* ... */)
    {
        if (count($this->_fields) == 0) return null;
        $query = " WHERE";
        foreach ($this->_where as $_field => $_value) {
            // Allow for clause that do not require values
            if (is_int($_field)) {
                $key = null;
                $_field = $_value;
                $clause = $this->get_where_clause($_field);
            } else {
                $key = $this->to_bind_var($_field);
                $clause = $this->get_where_clause(
                    $key,
                    $_field,
                    $_value
                );
                $this->_bind($key, $clause);
            }
            $query .= sprintf(
                "%s %s",
                ($this->_is_bound) ? ' AND' : '',
                $clause->get_clause()
            );
        }
        return $query;
    }

    /**
     * Parses where clause syntax based on "__clause" structure, returning a 
     * SQL useable where clause.
     *
     * @param  string  $string  String to parse
     * @param  string  $key  The PDO bind key
     *
     * @return  string
     */
    public function get_where_clause($string, $key = null, $value = null)
    {
        if (stripos($string, '__') === false) {
            $clause = 'e';
            $field = $string;
        } else {
            $array = explode('__', $string);
            $field = array_shift($array);
            $clause = array_shift($array);
        }

        $name = sprintf(
            '\\flames\\query\\where\\%s',
            ucfirst($clause)
        );
        if (!class_exists($name)) {
            throw new \InvalidArgumentException(sprintf(
                "where clause %s is invalid",
                $clause
            ));
        }
        return new $name($field, $key, $value);
    }
}