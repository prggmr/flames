<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Foreignkey field
 */
class Foreignkey extends \flames\field\Integer {

    /**
     * Model key is to.
     */
    protected $_to = null;

    /**
     * Sets the foreignkey attribute, this checks for a "to" option and verifys
     * the to model exists, if found it creates and set it in the option.
     * 
     * This calls the parent afterwords.
     *
     * @return  void
     */
    public function set_attributes($options = null)
    {
        if (!is_array($options)) {
            throw new \InvalidArgumentException(
                "Foreignkey field must have a relationship"
            );
        }
        if (!isset($options['to'])) {
            if (!isset($options['_to'])) {
                throw new \InvalidArgumentException(
                    "Foreignkey field must have a relationship"
                );
            }
            $to = $options['_to'];
            unset($options['_to']);
        } else {
            $to = $options['to'];
            unset($options['to']);
        }
        if (!class_exists($to)) {
            throw new \LogicException(sprintf(
                "Model %s could not be found",
                $to
            ));
        }
        $this->_to = $to;
        return parent::set_attributes($options);
    }

    /**
     * Returns the field keys.
     *
     * @return  null|string
     */
    public function get_db_keys(/* ... */)
    {
        return sprintf(
            'CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES `%s` (`%s`)',
            sprintf(
                '%s_%s_%s',
                $this->_name,
                $this->_to->get_table(),
                $this->_to->get_primary_key()->get_name()
            ),
            $this->_name,
            $this->_to->get_table(),
            $this->_to->get_primary_key()->get_name()
        );
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
     * @param  boolean  $select  Select the value if not set.
     *
     * @return  object
     */
    public function get_value($select = true)
    {
        if ($select && !is_object($this->__value)) {
            $object = new $this->_to();
            if (null !== $this->__value) {
                $this->__value = $object->select()->where([
                    $object->get_primary_key()->get_name() => $this->__value
                ])->exec()->first();
            } else {
                $this->__value = $object;
            }

        }
        return $this->__value;
    }

    /**
     * Gets the current value converted for the database.
     *
     * @return  mixed
     */
    public function get_bind_value(/* ... */)
    {
        if (is_object($this->__value)) {
            return $this->__value->get_primary_key()->get_bind_value();
        } else {
            return $this->__value;
        }
    }
}