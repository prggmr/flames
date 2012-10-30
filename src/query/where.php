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
     * Fields to use in the lookup.
     *
     * @var  array|null
     */
    protected $_where = null;

    /**
     * Provides a where lookup based on the given array for the SQL query.
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
     * Returns the where query.
     *
     * @param  object  $model  Model object for the where
     *
     * @return  string
     */
    public function build_where()
    {
        if (count($this->_where) == 0) return null;
        $query = [];
        $query[] = "WHERE";
        $query[] = $this->_build_where($this->_where);
        return implode(" ", $query);
    }

    /**
     * Builds the where lookup.
     */
    protected function _build_where($fields, $count = 0)
    {
        $query = [];
        foreach ($fields as $_field => $_value) {
            if (is_array($_value)) {
                // Build a grouped where
                $query[] = $this->_build_group_block($_value, $count);
            } else {
                if (is_int($_field)) {
                    $_field = null;
                }
                $query[] = $this->_build_field_block($_field, $_value, $count);
            }
            $count++;
        }
        return implode(" ", $query);
    }

    /**
     * Builds a grouped where statement.
     *
     * @param  array  $params  Where statement parameters.
     *
     * @return  string
     */
    protected function _build_group_block($array, $count = 0)
    {
        // Parse the first block and check for join clauses if not set
        $first = key($array);
        $clause = $this->_where_clause($first, $count);
        $block = [];
        if (false !== $clause['clause']) {
            $block[] = $clause['clause'];
            $new = [$clause['field'] => $array[$first]];
            unset($array[$first]);
            $array = $new + $array;
        }
        $block[] = '(';
        $block[] = $this->_build_where($array);
        $block[] = ')';
        return implode(" ", $block);
    }

    /**
     * Parses and builds a field block.
     *
     * @param  string  $field  The field name
     * @param  null|string|array  $value  The field value.
     *
     * @return  string  Field lookup block
     */
    protected function _build_field_block($field = null, $value = null, $count = 0)
    {
        $block = [];
        $key = null;
        $bind = true;
        $clause = false;
        // For fields that do not use values
        if (null === $field) {
            $clause = $this->_where_clause($value, $count);
            $bind = false;
            $value = null;
        } else {
            $clause = $this->_where_clause($field, $count);
        }
        $field = $clause['field'];
        $clause = $clause['clause'];
        if ($bind) {
            $key = $this->to_bind_var($field);
        }
        $lookup = $this->generate_lookup($field, $key, $value);
        if ($bind) {
            $this->_bind($key, $lookup);
        }
        if (false !== $clause) {
            $block[] = $clause;
        }
        $block[] = $lookup->get_lookup();
        return implode(" ", $block);
    }
    
    /**
     * Returns a join clause for the given field.
     *
     * @param  string  $field
     *
     * @return  array  ['clause'=>('AND','OR'),'field'=>'Field without clause']
     */
    protected function _where_clause($field, $count = 0)
    {
        $default = ($count >= 1) ? 'AND' : false;
        switch (true) {
            case (stripos($field, '&') !== false):
                $clause = 'AND';
                $field = str_replace('&', '', $field);
                break;
            case (stripos($field, '|') !== false):
                $clause = 'OR';
                $field = str_replace('|', '', $field);
                break;
            default:
                $clause = $default;
                break;
        }
        return ['clause' => (false === $default) ? false : $clause, 'field' => $field];
    }

    /**
     * Generates a lookup object based on the "fieldname__lookup" structure.
     *
     * @param  string  $string  Field string to parse
     * @param  string  $key  The PDO bind key
     *
     * @return  array
     */
    public function generate_lookup($string, $key = null, $value = null)
    {
        if (stripos($string, '__') === false) {
            $lookup = 'e';
            $field = $string;
        } else {
            $array = explode('__', $string);
            $field = array_shift($array);
            $lookup = array_shift($array);
        }
        $field = $this->_model->get_field($field);
        $field->set_value($value);
        $name = sprintf(
            '\\flames\\query\\lookup\\%s',
            ucfirst($lookup)
        );
        if (!class_exists($name)) {
            throw new \InvalidArgumentException(sprintf(
                "where lookup %s is invalid",
                $lookup
            ));
        }
        return new $name($field, $key);
    }
}