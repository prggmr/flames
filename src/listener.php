<?php
namespace flames;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Base class for handling flames signals
 */
class Listener extends \xpspl\Listener 
{   
    /**
     * Constructs a new listener.
     *
     * @return  void
     */
    public function __construct() 
    {
        foreach (get_class_methods($this) as $_method) {
            // skip magic methods
            if (stripos('_', $_method) === 0) continue;
            if (stristr($_method, 'on_') === false) continue;
            $_signal = str_replace('on_', '', $_method);
            if (!in_array($_signal, ['select', 'update', 'delete', 'insert'])) {
                throw new \DomainException;
            }
            $_signal = "\\flames\\signal\\$_signal";
            $_signal = new $_signal();
            $_priority = "_{$_method}_priority";
            $_exhaust = "_{$_method}_exhaust";
            $this->_signals[] = [
                $_signal,
                new \xpspl\Handle(
                    [$this, $_method],
                    $this->$_exhaust, 
                    $this->$_priority
                )
            ];
        }
    }

    /**
     * Executes a Query.
     *
     * @param  object  $event  Query Event
     *
     * @return  void
     */
    public function exec_query(query\Event $event)
    {
        return $event->get_flames_query()
               ->get_model()
               ->get_connection()
               ->exec_event_query($event);
    }
}