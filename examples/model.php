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
    
    public $id = ['Integer', ['default' => 1, 'max_length' => 75]];
    public $name = ['Integer', ['default' => 1, 'max_length' => 75]];
    public $first_name = ['Char', ['default' => 1, 'max_length' => 75]];
    public $last_name = ['Char', ['default' => 1, 'max_length' => 75]];

    protected $_table = 'user';
    
    /**
     * To string
     */
    public function __toString()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

$user = new User(false);
// var_dump($user);
// DEBUGGING SOME PERFORMANCE STUFF
function milliseconds(/* ... */) {
    return round(microtime(true) * 1000);
}

$microtime = milliseconds();
for ($i = 0; $i != 100000; $i++) {
    $user = new User(false);
}
echo "Time taken : " . (( milliseconds() - $microtime ) / 6000 ). PHP_EOL;
// $microtime = milliseconds();
// for ($i = 0; $i != 10; $i++) {
//     $user = new User();
// }
// echo "Time taken : " . (( milliseconds() - $microtime ) / 6000 ). PHP_EOL;