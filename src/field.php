<?php
namespace flames;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * The base field!
 */
class Field implements query\bind\Value {

    /**
     * The default value of this field.
     */
    protected $_default = null;

    /**
     * The max length of the field.
     */
    protected $_max_length = null;

    /**
     * The current value of the field.
     */
    protected $__value = null;

    /**
     * Field type
     */
    protected $_type = null;

    /**
     * Field create template
     */
    protected $_template = '`%s` %s(%s) %s';

    /**
     * The name of the field.
     */
    protected $_name = null;

    /**
     * Construct a field object.
     */
    public function __construct(/* ... */){}

    /**
     * Sets the field attributes
     * 
     * @param  array  $options  Field attribute options
     *
     * @throws  InvalidArgumentException  Unknown attribute
     * 
     * @return  void
     */
    public function set_attributes($options = null)
    {
        if (null !== $options && is_array($options)) {
            // set the field options
            foreach ($options as $_option => $_val) {
                if (stripos($_option, '_') !== 0){
                    $_internal = '_'.$_option;
                } else {
                    $_internal = $_option;
                }
                if (!property_exists($this, $_internal)) {
                    throw new \LogicException(sprintf(
                        "Unknown attribute %s for %s",
                        $_option,
                        str_replace('flames\\field\\', '', get_class($this))
                    ));
                }
                $this->{$_internal} = $_val;
            }
        }
    }

    /**
     * Returns the default value for this field.
     *
     * @return  null|string|int
     */
    final public function get_default()
    {
        return $this->_default;
    }

    /**
     * Returns an option for the field.
     *
     * @param  string  $option  Option to retrieve.
     *
     * @return  string|int|null|boolean
     */
    final public function get_opt($option)
    {
        if (strpos($option, '_') !== 0){
            $option = '_'.$option;
        }
        if (!property_exists($this, $option)) {
            return false;
        }
        return $this->$option;
    }

    /**
     * Sets the current value.
     *
     * @param  mixed  $val  Value to set the field.
     *
     * @return  object  this
     */
    public function set_value($val)
    {
        $this->__value = $val;
    }

    /**
     * Gets the current value.
     *
     * @return  mixed
     */
    public function get_value(/* ... */)
    {
        return $this->__value;
    }

    /**
     * Gets the value converted for the database.
     *
     * @return  mixed
     */
    public function get_bind_value(/* ... */)
    {
        return (null === $this->__value) ? $this->_default : $this->__value;
    }

    /**
     * Returns the fields attributes.
     *
     * @return  array
     */
    final public function get_attributes(/* ... */)
    {
        return get_object_vars($this);
    }

    /**
     * Returns the field creation string.
     *
     * @return  string
     */
    public function get_db_field()
    {
        $default = (null === $this->_default) ? 'DEFAULT NULL' : 'DEFAULT '.$this->_default;
        return sprintf($this->_template,
            $this->_name,
            $this->_type, 
            $this->_max_length, 
            $default
        );
    }

    /**
     * Returns the field keys.
     *
     * @return  null|string
     */
    public function get_db_keys(/* ... */)
    {
        return null;
    }

    /**
     * Returns a SQL wrapper function to use for insert/update for a field.
     *
     * @param  string  $bind  The PDO Bind parameter for the save.
     *
     * @return  string
     */
    public function get_save_function($bind)
    {
        return $bind;
    }

    /**
     * Returns the field name.
     *
     * @return  string
     */
    public function get_name(/* ... */)
    {
        return $this->_name;
    }
}