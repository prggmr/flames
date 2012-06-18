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
     * Constructs a new model this method is final because it is very
     * important.
     *
     * If a constructor is need in a child use __init instead, thats right
     * two underscores!
     */
    final public function __construct(/* ... */) 
    {
        if (FLAMES_CACHE_MODELS) {
            if (!file_exists(sprintf('%s/%s.fmc')))
        } else {
            // Get all the public class properties
            $class = new \ReflectionObject($this);
            $properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC);
            foreach ($properties as $_prop) {
                $_field_name = $_prop->getName();
                $_field = $this->{$_field_name};
                $doc = explode("\n", str_replace(["*", "/"], "", $_prop->getDocComment()));
                $block = null;
                foreach ($doc as $_block) {
                    $_block = trim($_block);
                    if ($_block != "" && strpos($_block, '@type') === 0) {
                        $block = $_block;
                        break;
                    }
                }
                if (null === $block) {
                    throw new \LogicException(sprintf(
                        "Field %s does not have a type definition"
                    ), $_field);
                }
                // Parse the field
                $_field_obj = trim(str_replace("@type", "", $_block));
                if (stripos($_field_obj, '(') !== false) {
                    // This is evil hahaha!
                    $_eval = '$_field_obj = new flames\\field\\'.$_field_obj.';';
                    eval($_eval);
                } else {
                    $_field_obj = "flames\\field\\$_field_obj";
                    $_field_obj = new $_field_obj;
                }
                // Check if type
                if (!$_field_obj instanceof Field) {
                    throw new \LogicException(sprintf(
                        'Field %s is not a flames field object',
                        $_prop->getName()
                    ));
                }
                // Add field
                $this->_fields[$_field_name] = $_field_obj;
                // destroy the property!
                unset($this->{$_field_name});
            }
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
    public function create_table()
    {
        $this->_connection->create_table($this);
    }

    /**
     * Returns the fields for the model.
     *
     * @return  array
     */
    public function get_fields()
    {
        return 
    }
}