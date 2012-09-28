<?php
namespace flames\listener;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Listening handle used for running SELECT statements
 */
class Select extends \flames\Listener 
{
    /**
     * Exhaust of the on select
     */
    protected $_on_select_exhaust = 0;

    /**
     * Priority of the on select
     */
    protected $_on_select_priority = 10;

    /**
     * Performs an SQL SELECT query.
     *
     * @return  boolean
     */
    public function on_select(\flames\events\Select $event)
    {
        $statement = $event->get_statement();
        $query = $event->get_query();
        var_dump($statement);
        $query->bind($statement);
        $connection = $query->get_model()->get_connection();
        $transactions = $connection->use_transactions();
        if ($transactions) {
            $connection->beginTransaction();
        }
        try {
            $statement->execute();
        } catch (\PDOException $e) {
            if ($transactions) {
                $connection->rollback();
            }
            throw new \flames\Exception($e->getMessage());
        }
        $event->set_result(new \flames\query\Results_Wrapper(
            $statement->fetchAll(\PDO::FETCH_ASSOC),
            $query->get_model()
        ));
    }
}