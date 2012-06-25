<?php
namespace flames\driver;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * MySQL flames driver.
 *
 * For now this driver is blank :)
 */
class MySQL extends \PDO {

    /**
     * Extend the main db driver class.
     */
    use \flames\Driver;

    /**
     * Latest SQL statement run
     */
    protected $_last_sql = null;

    /**
     * Creates a new table.
     *
     * @param  object  $model  flames\Model instance
     * @param  boolean  $safe  Create if not exists
     *
     * @throws  \flames\Exception  Creation failure
     * 
     * @return  boolean
     */
    public function create_table(\flames\Model $model, $safe = false)
    {
        if ($safe) {
            $safe = 'IF NOT EXISTS';
        } else {
            $safe = '';
        }
        $sql = sprintf(
            'CREATE TABLE %s `%s` ( ',
            $safe,
            $model->get_table()
        );
        $fields = [];
        $keys = [];
        foreach ($model->get_fields() as $_name => $_field) {
            $fields[] = $_field->get_db_field($_name);
            $_keys = $_field->get_db_keys();
            if (null !== $_keys) {
                $keys[] = $_field->get_db_keys();
            }
        }
        $fields = array_merge($fields, $keys);
        $sql .= implode(", ", $fields);
        $sql .= sprintf(
            ' ) ENGINE=%s DEFAULT CHARSET=%s;',
            $model->get_engine(),
            $model->get_charset()
        );
        try {
            $this->exec($sql);
            return true;
        } catch (PDOException $e) {
            throw new \flames\Exception($e->getMessage());
        }
    }

    /**
     * Executes an arbitary SQL statement.
     *
     * @return  int  Number of affected rows.
     */
    public function exec($sql)
    {
        $this->_last_sql = $sql;
        return parent::exec($sql);
    }

    /**
     * Returns the last sql statement run.
     *
     * @return  string
     */
    public function get_sql()
    {
        return $this->_last_sql;
    }
    
}