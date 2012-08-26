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
class Listener extends \prggmr\Listener 
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
            $_priority = "{$_method}_priority";
            $_exhaust = "{$_method}_exhaust";
            $this->_sig_handlers[] = [
                new \prggmr\Handle(
                    [$this, $_method],
                    $this->$_exhaust, 
                    $this->$_priority
                ),
                $_signal
            ];
        }
    }
}