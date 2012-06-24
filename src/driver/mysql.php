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
     * Creates a new table.
     *
     * @param  object  $model  flames\Model instance
     *
     * @return  void
     */
    public function create_table(\flames\Model $model)
    {
        $create_str = "CREATE TABLE `test` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `test` varchar(255) DEFAULT NULL,
              `userid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              CONSTRAINT `test_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        
        $sql = 'CREATE TABLE `'.$model->get_table().'` (';
        foreach ($model->get_fields as $_name => $_field) {

        }
    }
}