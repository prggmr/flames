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
            $key = $this->_bind($_field, $_value);
            $query .= sprintf(
                "%s %s = %s",
                ($this->_is_bound) ? ' AND' : '',
                $_field,
                $key
            );
        }
        return $query;
    }
}