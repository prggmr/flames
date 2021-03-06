<?php
namespace flames;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * The model
 */
class Model implements query\bind\Value {

    /**
     * Fields in this model.
     *
     * @var  array
     */
    protected $_fields = [];

    /**
     * Field aliases.
     *
     * @var  array
     */
    protected $_aliases = [];

    /**
     * Temporary fields returned from a query
     *
     * @var  array
     */
    protected $_tmp = [];

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
     * Table name.
     *
     * @var  string
     */
    protected $_table = null;

    /**
     * Storage engine.
     */
    protected $_engine = 'InnoDB';

    /**
     * Model charset.
     */
    protected $_charset = 'utf8';

    /**
     * Primary key field set to false to prevent autogeneration.
     */
    protected $_primary = null;

    /**
     * Constructs a new model.
     *
     * If a constructor is need in a child use __init instead.
     *
     * @param  array  $values  Default values for the model properties
     * @param  boolean  $set  Tells the field objects to ignore any set functions.
     *
     * @return  object  Model
     */
    final public function __construct($values = [], $set = false) 
    {
        $properties = get_object_vars($this);
        foreach ($properties as $_name => $_property) {
            if (strpos($_name, '_') === 0) continue;
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
                $name = sprintf(
                    "\\flames\\field\\%s",
                    ucfirst($name)
                );
            }
            $field = new $name;
            if (!$field instanceof Field) {
                throw new \InvalidArgumentException(sprintf(
                    "Not a valid field %s",
                    $name
                ));
            } elseif (false !== $this->_primary) {
                if (null == $this->_primary) {
                    if ($field instanceof field\Primary) {
                        $this->_primary = $field;
                    }
                }
            }
            $attributes['name'] = $_name;
            $field->set_attributes($attributes);
            $this->_fields[$_name] = $field;
            if ($field->get_name() !== $field->get_db_field_name()) {
                $this->_aliases[$field->get_db_field_name()] = $field->get_name();
            }
            unset($this->$_name);
        }
        // Do we have a name
        if (null === $this->_table) {
            $table = get_class($this);
            if (strpos($table, '\\') === 0) {
                $table = substr($table, 1);
            }
            $table = strtolower(str_replace('\\', '_', $table));
            $this->_table = $table;
        }
        // Do we not have a primary
        // Auto add if not false
        if (null === $this->_primary) {
            $primary = new field\Primary();
            if (strpos($this->_table, '_') !== false) {
                $name = explode('_', $this->_table);
                $name = sprintf(
                    '%s_id',
                    $name[count($name) - 1]
                );
            } else {
                $name = sprintf(
                    '%s_id',
                    $this->_table
                );
            }
            $primary->set_attributes([
                'name' => $name
            ]);
            // So Jesse Boyer can have the ID first ... it bugs the crap outta him ...
            $tmp = [$name => $primary];
            $this->_fields = $tmp + $this->_fields;
            $this->_primary = $primary;
        }
        if (is_array($values) && count($values) != 0) {
            foreach ($values as $_key => $_value) {
                // This must be made to the get_field to allow for aliases and
                // tmp fields being setup properly from the DB
                $this->get_field($_key, true)->set_value($_value, $set);
            }
        }
        // Model construction does not make it dirty
        $this->_dirty = false;
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
    final public function __set($field, $val)
    {
        $field = $this->get_field($field);
        $this->_dirty = true;
        $field->set_value($val);
        $field->mark_dirty();
    }

    /**
     * Gets a model property.
     *
     * @param  string  $field  Property to set.
     * @param  mixed  $val  Value to set the property.
     *
     * @return  void
     */
    final public function __get($field)
    {
        return $this->get_field($field)->get_value();
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
        return (string) $this->get_primary_key()->get_value();
    }

    /**
     * Creates the table in the database.
     *
     * @param  boolean  $safe  Create the table if it doesnt exist
     *
     * @return  void
     */
    public function create_table($safe = false)
    {
        return $this->get_connection()->create_table($this, $safe);
    }

    /**
     * Returns the name of the table for the model.
     *
     * @return  string
     */
    public function get_table(/* ... */)
    {
        return $this->_table;
    }

    /**
     * Returns a specific field in the model.
     *
     * @param  string  $field  Field name
     * @param  boolean  $tmp  Set as a temp field if doesnt exit
     *
     * @return  object
     */
    public function get_field($field, $tmp = false)
    {
        if (!isset($this->_fields[$field])) {
            if (!isset($this->_aliases[$field])) {
                if (!isset($this->_tmp[$field])) {
                    if ($tmp) {
                        $_field = new Field();
                        $_field->set_attributes([
                            'name' => $field,
                            'field' => $field
                        ]);
                        $this->_tmp[$field] = $_field;
                    } else {
                        throw new \RuntimeException(sprintf(
                            "Model %s has no field %s",
                            get_class($this), $field
                        ));
                    }
                }
                return $this->_tmp[$field];
            }
            $field = $this->_aliases[$field];
        }
        return $this->_fields[$field];
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

    /**
     * Returns the model storage engine.
     *
     * @return  string
     */
    public function get_engine(/* ... */)
    {
        return $this->_engine;
    }

    /**
     * Returns the model charset.
     *
     * @return  string
     */
    public function get_charset(/* ... */)
    {
        return $this->_charset;
    }

    /**
     * Returns the models primary keys, null if not exists.
     *
     * @return  null|object
     */
    public function get_primary_key(/* ... */)
    {
        return $this->_primary;
    }

    /**
     * Returns if the model has the given field.
     *
     * @return  boolean
     */
    public function has_field($field)
    {
        try {
            $this->get_field($field);
            return true;
        } catch (\RuntimeException $e) {
            return false;
        }
    }

    /**
     * Selects records from the database.
     *
     * @param  array  $fields  Array of fields to select.
     *
     * @return  \flames\Query
     */
    public function select($fields = null) 
    {
        if (null === $fields) {
            $fields = array_keys($this->_fields);
        }
        if (!defined('FLAMES_SELECT_REGISTERED')) {
            define('FLAMES_SELECT_REGISTERED', true);
            listen(new \flames\listener\Select());
        }
        return new \flames\query\Select($fields, $this);
    }

    /**
     * Saves a model record.
     *
     * If the model has a primary key set it will attempt to update the model 
     * otherwise it will attempt to insert a new record.
     *
     * @return  \flames\Query
     */
    public function save(/* ... */)
    {
        if ($this->get_primary_key()->get_value() === null) {
            if (!defined('FLAMES_INSERT_REGISTERED')) {
                define('FLAMES_INSERT_REGISTERED', true);
                listen(new \flames\listener\Insert());
            }
            $query = new \flames\query\Insert($this->get_fields(), $this);
        } else {
            if (!defined('FLAMES_UPDATE_REGISTERED')) {
                define('FLAMES_UPDATE_REGISTERED', true);
                listen(new \flames\listener\Update());
            }
            $query = new \flames\query\Update($this->get_fields(), $this);
        }
        return $query;
    }

    /**
     * Deletes records from the database.
     *
     * @return  \flames\Query
     */
    public function delete() 
    {
        if (!defined('FLAMES_DELETE_REGISTERED')) {
            define('FLAMES_DELETE_REGISTERED', true);
            listen(new \flames\listener\Delete());
        }
        return new \flames\query\Delete(null, $this);
    }

    /**
     * Marks a model as no longer dirty
     *
     * @return  void
     */
    public function clean(/* ... */)
    {
        $this->_dirty = false;
    }

    /**
     * Static method for finding results.
     *
     * @param  array  $where  Where search lookup.
     * @param  array|null  $select  Fields to select
     *
     * @return  \flames\query\results\Wrapper
     */
    public static function find($where = [], $select = null)
    {
        $model = get_called_class();
        $model = new $model();
        return $model->select($select)->where($where);
    }
    
    /**
     * Returns the connection instance
     *
     * @return  object
     */
    public function get_connection()
    {
        return Connections::get($this->_connection);
    }

    /**
     * Gets the bind value.
     *
     * @return  integer
     */
    public function get_bind_value(/* ... */)
    {
        return $this->get_primary_key()->get_value();
    }
}