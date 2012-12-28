<?php
namespace flames\signal;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Base signal used for models to issue - SELECT, UPDATE, DELETE and INSERT
 * commands.
 */
class Base extends \xpspl\Signal {

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
    public function __construct($model = null)
    {
        $this->_model = $model;
        parent::__construct();
    }

    /**
     * Returns the model this signal represents.
     *
     * @return  object  \flames\Model
     */
    public function get_model(/* ... */)
    {
        return $this->_model;
    }
}