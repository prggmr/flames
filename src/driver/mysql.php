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
 */
class MySQL extends \flames\Driver {

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
        $this->exec($sql);
        return true;
    }
}