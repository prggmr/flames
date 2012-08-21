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
     * @param  object  $model
     *
     * @return  void
     */
    public function __construct($model) 
    {
        foreach (get_class_methods($this) as $_method) {
            // skip magic methods
            if (stripos('_', $_method) === 0) continue;
            if (stristr($_method, 'on_') === false) continue;
            $_signal = str_replace('on_', '', $_method);
            if (!in_array($_signal, ['select', 'update', 'delete', 'insert'])) {
                throw new \DomainException;
            }
            $_signal = "\\flames\\signals\\$_signal";
            $_signal = new $_signal($model);
            $this->_sig_handlers[] = [
                $_method,
                $_signal
            ];
        }
    }
}