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
    # Blame PHP for this requirement ...
    # I want to do
    # public $id = new flames\Field(); 
    # just as much as you!
    public function __init() {
        $this->id = new flames\Field();
    }
}

$user = new User();
var_dump($user);