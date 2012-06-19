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
     * Constructs a new model.
     *
     * If a constructor is need in a child use __init instead.
     *
     * The constructor accepts two different parameters for slot 1.
     * 
     * @param  boolean|array  $p1  Provided as a boolean this informs the class
     *         to save a cache copy of itself. Provided as an array this informs
     *         the class to automatically populate itself with the given 
     *         key -> value map.
     *
     * @param  boolean  $igcache  Informs the class to ignore the cache 
     *         completely. This should only be giving if you are sure you do not 
     *         want the cache to load.
     *
     * @return  object  Model
     */
    final public function __construct($p1 = null, $igcache = false) 
    {

        // Turn on cache by default in the model
        $load_cache = true;
        $auto_fill = null;

        if (null !== $p1) {
            $bool = is_bool($p1);
            $array = is_array($p1);
            if (!$array && !$bool) {
                throw new \InvalidArgumentException;
            }
            if ($bool) {
                $load_cache = $bool;
            } else {
                // we will validate this data later
                $auto_fill = $p1;
            }
        }

        $save_cache = (FLAMES_CACHE_MODELS && $load_cache && !$igcache);

        if ($save_cache) {
            $has_cache = $this->_load_cache();
        } 

        if (!$has_cache) {
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
            if ($save_cache) {
                $this->_save_cache();
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
    public function create_table(/* ... */)
    {
        $this->_connection->create_table($this);
    }

    /**
     * Returns the fields for the model.
     *
     * @return  array
     */
    public function get_fields(/* ... */)
    {
        return;
    }

    /**
     * Attempts to load the modal cache.
     *
     * @return  boolean  True if success|False otherwise
     */
    private function _load_cache(/* ... */)
    {
        $fname = $this->_generate_cache_fname();
        $check = $this->_generate_checksum_fname();
        if (!file_exists($fname) || !file_exists($check)) {
            return false;
        }
        $data = json_decode(file_get_contents($fname));
        // tampered?
        if (sha1($data) !== file_get_content($check)) return false;
        foreach ($data as $_field) {
            list($name, $field, $attr) = $_field;
            $attr = get_object_vars($attr);
            // build our constructor
            $construct = '$_field_obj = new '.$field.'([';
            $iattr = [];
            foreach ($attr as $_attr => $_val) {
                $iattr[] = '"'.$_attr.'" => "'.$_val.'"';
            }
            $construct .= implode($iattr, ',') . ']);';
            // HAHA more evil code!
            eval($construct);
            $this->_fields[$name] = $_field_obj;
        }
        return true;
    }

    /**
     * Saves the model cache.
     *
     * @return  void
     */
    private function _save_cache(/* ... */)
    {
        $data = [];
        foreach ($this->_fields as $_name => $_field) {
            $properties = $_field->get_attributes();
            // save all but the value
            unset($properties['__value']);
            $data[] = [$_name, get_class($_field), $properties];
        }
        $write = json_encode($data);
        $check = sha1($write);
        file_put_contents($this->_generate_cache_fname(), $write);
        file_put_contents($this->_generate_checksum_fname(), $check);
    }

    /**
     * Generates the cache filename for the model.
     *
     * @return  string
     */
    private function _generate_cache_fname(/* ... */)
    {
        return sprintf('%s/%s.fmc', 
            FLAMES_CACHE_DIR, 
            str_replace('\\', '', strtolower(get_class($this)))
        );
    }

    /**
     * Generates the cache checksum filename.
     *
     * @return  string
     */
    private function _generate_checksum_fname(/* ... */)
    {
        return sprintf('%s/%s.fmcs', 
            FLAMES_CACHE_DIR, 
            str_replace('\\', '', strtolower(get_class($this)))
        );
    }
}