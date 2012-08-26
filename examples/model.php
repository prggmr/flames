<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * A Model
 */
require_once 'connection.php';

class Model extends flames\Model {

    public function __init()
    {
        // enable transactions
        $this->get_connection()->set_transactions(true);
    }

}

class User extends Model {
    
    public $username = ['char', ['default' => 1, 'max_length' => 30]];
    public $password = ['datetime'];
    public $email = ['text'];
    public $another = ['boolean'];
    
    /**
     * To string
     */
    public function __toString()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

class Profile extends Model {

    public $first_name = ['char'];
    public $last_name = ['char'];
    public $user = ['foreignkey', ['to' => 'User']];
}
