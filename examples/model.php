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

class Profile extends flames\Model {

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
$user->set_connection(flames\Connections::get());
$profile = new Profile();
$profile->set_connection(flames\Connections::get());
$user->create_table();
$profile->create_table();

// $prggmr = $user->select(['username' => 'prggmr']);
// $prggmr->delete();

// $user->username = "prggmr";
// $user->password = sha1('newmedia');
// $user->save();

// var_dump($user);
// DEBUGGING SOME PERFORMANCE STUFF
// function milliseconds(/* ... */) {
//     return round(microtime(true) * 1000);
// }

// $microtime = milliseconds();
// for ($i = 0; $i != 10; $i++) {
//     $user = new User();
// }
// echo "Time taken : " . (( milliseconds() - $microtime ) / 6000 ). PHP_EOL;
// $microtime = milliseconds();
// for ($i = 0; $i != 10; $i++) {
//     $user = new User();
// }
// echo "Time taken : " . (( milliseconds() - $microtime ) / 6000 ). PHP_EOL;