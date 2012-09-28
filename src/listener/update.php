<?php
namespace flames\listener;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Listening handle used for running INSERT statements
 */
class Update extends \flames\Listener 
{
    /**
     * Exhaust of the on select
     */
    protected $_on_update_exhaust = null;

    /**
     * Priority of the on select
     */
    protected $_on_update_priority = 10;

    /**
     * Performs an SQL SELECT query.
     *
     * @return  boolean
     */
    public function on_update(\flames\query\event\Update $event)
    {
        $this->exec_query($event);
        return true;
    }
}