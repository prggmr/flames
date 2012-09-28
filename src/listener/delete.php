<?php
namespace flames\listener;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Listening handle used for running DELETE statements
 */
class Delete extends \flames\Listener 
{
    /**
     * Exhaust of the on select
     */
    protected $_on_delete_exhaust = 0;

    /**
     * Priority of the on select
     */
    protected $_on_delete_priority = 10;

    /**
     * Performs an SQL SELECT query.
     *
     * @return  boolean
     */
    public function on_delete(\flames\query\event\Delete $event)
    {
        $this->exec_query($event);
        return true;
    }
}