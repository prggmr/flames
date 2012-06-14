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
     * The database drive this model uses.
     *
     * @var  null|object
     */
    protected $_driver = null;

    /**
     * Constructs a new model this method is final because it is very
     * important.
     *
     * If a constructor is need in a child use __init instead, thats right
     * two underscores!
     */
    final public function __construct() 
    {
        // Allow for a custom constructor within a Model
        if (method_exists($this, '__init')) {
            $this->__init();
        }
        // Get all the public class properties
        // I find this sloppy ... why!?!?!
        $class = new \ReflectionObject($this);
        $properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $_prop) {
            $_field = $this->{$_prop->getName()};
            if (!$_field instanceof Field) {
                throw new \LogicException(sprintf(
                    'Field %s is not a flames field object',
                    $_prop->getName()
                ));
            }
            $this->_fields[] = [
                $_prop->getName(), 
                $_field
            ];
            // destroy the property!
            unset($this->{$_prop->getName()});
        }
    }

    /**
     * Sets the driver for the model.
     *
     * @param  object  $driver  The database driver.
     *
     * @return  object  $this
     */
    public function set_driver($driver)
    {
        if (!$driver instanceof Driver) {
            throw new \InvalidArgumentException;
        }
        $this->_driver = $driver;
        return $this;
    }


}