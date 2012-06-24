<?php
namespace flames;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * The model
 *
 * It does stuff to what I'm unsure!
 */
class Model {

    /**
     * Fields in this model.
     *
     * @var  array
     */
    protected $_fields = [];

    /**
     * Is this model dirty?
     *
     * @var  boolean
     */
    protected $_dirty = false;

    /**
     * The database connection driver this model uses.
     *
     * @var  null|object
     */
    protected $_connection = null;

    /**
     * Field names to ignore when constructing the model.
     *
     * @var  array
     */
    protected $_ignore = [
        '_ignore', '_connection', '_dirty', '_fields', '_table'
    ];

    /**
     * Table name
     *
     * @var  string
     */
    protected $_table = null;

    /**
     * Constructs a new model.
     *
     * If a constructor is need in a child use __init instead.
     *
     * @return  object  Model
     */
    final public function __construct() 
    {
        $properties = get_object_vars($this);
        foreach ($properties as $_name => $_property) {
            if (in_array($_name, $this->_ignore)) continue;
            $name = null;
            $attributes = null;
            if (!is_array($_property)){
                $name = $_property;
            } else {
                if (!isset($_property[0])) {
                    throw new \RuntimeException("Unknown field type");
                }
                $name = $_property[0];
                if (isset($_property[1])) {
                    $attributes = $_property[1];
                }
            }
            if (stripos($name, '\\') === false) {
                $name = "\\flames\\field\\$name";
            }
            $field = new $name;
            if (!$field instanceof Field) {
                throw new \InvalidArgumentException(sprintf(
                    "Not a valid field %s",
                    $name
                ));
            }
            $field->set_attributes($attributes);
            $this->_fields[$_name] = $field;
            unset($this->$_name);
        }
        // Allow for a custom constructor within a Model
        if (method_exists($this, '__init')) {
            $this->__init();
        }
    }

    /**
     * Sets the driver for the model.
     *
     * @param  object  $connection  The database driver connection.
     *
     * @return  object  $this
     */
    final public function set_connection($connection)
    {
        $this->_connection = $connection;
        return $this;
    }

    /**
     * Sets a model property.
     *
     * @param  string  $prop  Property to set.
     * @param  mixed  $val  Value to set the property.
     *
     * @return  void
     */
    final public function __set($prop, $val)
    {
        if (!isset($this->_fields[$prop])) {
            throw new \RuntimeException(sprintf(
                "Model %s has no field %s",
                get_class($this), $prop
            ));
        }
        $this->_dirty = true;
        $this->_fields[$prop]->set_value($val);
    }

    /**
     * Gets a model property.
     *
     * @param  string  $prop  Property to set.
     * @param  mixed  $val  Value to set the property.
     *
     * @return  void
     */
    final public function __get($prop)
    {
        if (!isset($this->_fields[$prop])) {
            throw new \RuntimeException(sprintf(
                "Model %s has no field %s",
                get_class($this), $prop
            ));
        }
        return $this->_fields[$prop]->get_value($val);
    }

    /**
     * Returns if the model is currently dirty and needs to be written.
     *
     * @return  boolean
     */
    final public function is_dirty()
    {
        return $this->_dirty;
    }

    /**
     * To string implementation.
     */
    public function __toString()
    {
        throw new \RuntimeException(sprintf(
            'Model %s has no toString method implemented',
            get_class($this)
        ));
    }

    /**
     * Creates the table in the database.
     *
     * @return  void
     */
    public function create_table(/* ... */)
    {
        $this->_connection->create_table($this);
    }

    /**
     * Returns the name of the table for the model.
     *
     * @return  string
     */
    public function get_table(/* ... */)
    {
        $this->_table;
    }

    /**
     * Returns the fields for the model.
     *
     * @return  array
     */
    public function get_fields(/* ... */)
    {
        return $this->_fields;
    }
}