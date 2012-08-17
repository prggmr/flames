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

class Model extends flames\Model {

    public function __init()
    {
        $this->set_connection(flames\Connections::get());
        // enable transactions
        $this->_connection->set_transactions(true);
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

flames\Connections::add(new flames\driver\MySQL(
    'mysql:dbname=flames;host=127.0.0.1', 
    'root', 
    ''
));

$user = new User();
$profile = new Profile();
$user->create_table();
$profile->create_table();
// Select info
// $user = $user->select()->where(['id' => 1]);

// $prggmr = $user->select(['username' => 'prggmr']);
// $prggmr->delete();

// $user->username = "prggmr";
// $user->password = sha1('newmedia');
// $user->save();