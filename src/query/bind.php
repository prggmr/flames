<?php
namespace flames\query;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Class that allows for bindable PDO parameters in the query.
 */
trait Bind {

    /**
     * Parameters that are bound.
     *
     * @var  array
     */
    protected $_bind = [];

    /**
     * Flag for determining if the query has been bound.
     *
     * @var  boolean
     */
    protected $_has_bind = false;

    /**
     * Establishes parameters in the SQL Query for binding.
     *
     * @param  string  $name  Parameter name
     * @param  mixed  $value  Parameter value
     *
     * @return  void
     */
    protected function _bind($name, $value)
    {
        if (!$this->_has_bind) { 
            $this->_has_bind = true;
        }
        $this->_bind[$name] = $value;
    }

    /**
     * Converts a variable into a PDO bindable variable.
     *
     * @param  string  $var  Variable to convert.
     *
     * @return  string
     */
    public function to_bind_var($var)
    {
        return sprintf(':%s', $var);
    }

    /**
     * Performs the binding of parameters in the query.
     *
     * @param  object  $statement  PDO Statement
     *
     * @return  void
     */
    public function bind($statement)
    {
        if (!$this->_has_bind) return;
        foreach ($this->_bind as $_name => $_value) {
            switch ($_value) {
                case is_int($_value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($_value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($_value):
                    $type = \PDO::PARAM_NULL;
                    break;
                case is_string($_value):
                    $type = \PDO::PARAM_STR;
                    break;
                default:
                    $type = false;
                    break;
            }
            $statement->bindValue(
                $_name, $_value, $type
            );
        }
    }

    /**
     * Returns the bound parameters
     *
     * @return  null|array
     */
    public function get_bind(/* ... */)
    {
        return $this->_bind;
    }
}