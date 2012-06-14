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
            $this->_fields[] = [
                $_field_name, 
                $_field_obj
            ];
            // destroy the property!
            unset($this->{$_field_name});
        }
    }

    /**
     * Sets the driver for the model.
     *
     * @param  object  $driver  The database driver.
     *
     * @return  object  $this
     */
    final public function set_driver($driver)
    {
        if (!$driver instanceof Driver) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid driver provided to the %s model",
                get_class($this)
            ));
        }
        $this->_driver = $driver;
        return $this;
    }
}