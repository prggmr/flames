<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * A Model
 */
require '../src/flames.php';

class User extends flames\Model {
    
    /**
     * Primary key
     * 
     * @type Integer(1)
     */
    public $id;
}

$user = new User();
var_dump($user);