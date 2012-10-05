<?php
namespace flames\signal\model;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Base signal used for models issuing - SELECT, UPDATE, DELETE and INSERT
 * commands.
 *
 * This allows for selecting events based 
 */
class Base extends \prggmr\signal\Complex {

    /**
     * The model object this signal represents
     */
    protected $_model = null;

    /**
     * Constructs a new flames model.
     *
     * @param  object  $model
     *
     * @return  void
     */
    public function __construct($model)
    {
        $this->_model = $model;
    }
    
    /**
     * Compares the event signal given against itself.
     *
     * @param  string|integer  $signal  Signal to evaluate
     *
     * @return  boolean
     */
    public function evaluate($signal = null) 
    {
        if (!$signal instanceof $this) return false;
        if ($signal->get_model() instanceof $this->_model) return true;
        return false;
    }

    /**
     * Returns the model being used in the signal.
     *
     * @return  object
     */
    public function get_model(/* ... */)
    {
        return $this->_model;
    }
}