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
     * Values to bind
     */

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
     * @return  string
     */
    public function build_where(/* ... */)
    {
        if (count($this->_where) == 0) return null;
        return "WHERE ".$this->_build_where($this->_where);
    }

    /**
     * Builds the where lookup.
     */
    protected function _build_where($fields)
    {
        $query = "";
        foreach ($this->_where as $_field => $_value) {
            if (is_array($_value)) {
                // Build a grouped where
                $query .= $this->_build_group_block($_value);
            } else {
                $query .= $this->_build_field_block($_field, $_value);
            }
        }
        return $query;
    }

    /**
     * Builds a grouped where statement.
     *
     * @param  array  $params  Where statement parameters.
     *
     * @return  string
     */
    protected function _build_group_block($array)
    {
        // Parse the first block and check for join clauses if not set
        $first = current($array);
        $clause = $this->_where_clause($first, false);
        $block = sprintf(
            '%s(',
            ($clause['clause'] !== false) ? ' '.$clause['clause'] : ''
        );
        $block .= $this->_build_where($array);
        $block .= ')';
        return $block;
    }

    /**
     * Parses and builds a field block.
     *
     * @param  string  $field  The field name
     * @param  null|string|array  $value  The field value.
     *
     * @return  object  lookup\Base object
     */
    protected function _build_field_block($field = null, $value = null)
    {
        $block = '';
        $key = null;
        $bind = false;
        // For fields that do not use values
        if (null === $field) {
            $clause = $this->_where_clause($value);
            $bind = false;
            $value = null;
        } else {
            $clause = $this->_where_clause($field);
        }
        $field = $clause['field'];
        $clause = $cluse['clause'];
        if ($bind) {
            $key = $this->to_bind_var($field);
        }
        $lookup = $this->generate_lookup($field, $key, $value);
        if ($bind) {
            $this->_bind($key, $lookup);
        }
        return sprintf(
            '%s%s',
            ($clause !== false) ? ' '.$clause : '',
            $lookup->get_lookup()
        );
    }
    
    /**
     * Returns a join clause for the given field.
     *
     * @param  string  $field
     *
     * @return  array  ['clause'=>('AND','OR'),'field'=>'Field without clause']
     */
    protected function _where_clause($field, $default = false)
    {
        switch (true) {
            case (stripos('&', $field) !== false):
                $clause = 'AND';
                $field = str_replace('&', '', $field);
                break;
            case (stripos('|', $field) !== false):
                $clause = 'OR';
                $field = str_replace('|', '', $field);
                break;
            default:
                $clause = $default;
                break;
        }
        return ['clause' => $clause, 'field' => $field];
    }

    /**
     * Parses where lookup syntax based on "__lookup" structure, returning a 
     * SQL useable where lookup.
     *
     * @param  string  $string  String to parse
     * @param  string  $key  The PDO bind key
     *
     * @return  string
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
        return new $name($field, $key, $value);
    }
}