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
class Field {

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
     * Construct a field object.
     */
    public function __construct($options = null)
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
                        "Field %s does not have the option %s",
                        str_replace('flames\\field\\', '', get_class($this)),
                        $_option
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
    public function get_value($val)
    {
        return $this->__value;
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
}