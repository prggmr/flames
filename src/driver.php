<?php
namespace flames;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Database Drive trait ... mmm not sure what to put in here yet.
 *
 * I know this will be the database drivers itself for sure :)
 */
abstract class Driver extends \PDO {

    /**
     * Latest SQL statement run
     */
    protected $_last_sql = null;

    /**
     * Use transactions
     */
    protected $_transactions = false;

    /**
     * Executes an arbitary SQL statement.
     *
     * @return  int  Number of affected rows.
     */
    public function exec($sql)
    {
        if ($this->_transactions) {
            $this->beginTransaction();
        }
        $this->_last_sql = $sql;
        $result = parent::exec($sql);
        if (false === $result) {
            if ($this->_transactions) {
                $this->rollBack();
            }
            $this->throw_db_exception();
        }
        if ($this->_transactions) {
            $this->commit();
        }
        return $result;
    }

    /**
     * Throws an exception based on the error provided in the errorInfo.
     *
     * @return  void
     */
    public function throw_db_exception(/* ... */)
    {
        $error = $this->errorInfo();
        throw new \flames\Exception(sprintf(
            '(%s) %s',
            $error[1], $error[2]
        ));
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
    
    /**
     * Sets the flag to using DB transactions.
     *
     * @param  boolean  $flag  True|Flase
     * 
     * @return  void
     */
    public function set_transactions($flag)
    {
        if (true === $flag || false === $flag) {
            $this->_transaction = $flag;
            return;
        }
        throw new \InvalidArgumentException();
    }

    /**
     * Returns if transactions are to be used for queries.
     *
     * @return  boolean
     */
    public function use_transactions(/* ... */)
    {
        return $this->_transaction;
    }
}