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
    public $another = ['boolean', ['default' => 1]];
    
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

class Credit extends Model {
    public $card_number = ['aes_encrypt', ['key' => 'LJFH34798VY079CG2GHTF78TXC02T7Y0CY8']];
}
