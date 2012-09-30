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
class Insert extends \flames\Listener 
{
    /**
     * Exhaust of the on select
     */
    protected $_on_insert_exhaust = null;

    /**
     * Priority of the on select
     */
    protected $_on_insert_priority = 10;

    /**
     * Performs an SQL SELECT query.
     *
     * @return  boolean
     */
    public function on_insert(\flames\query\event\Insert $event)
    {
        $this->exec_query($event);
        $model = $event->get_query()->get_model();
        $model->get_primary_key()->set_value($event->insert_id);
        return $event->insert_id;
    }
}