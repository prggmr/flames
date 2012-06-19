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
     * @type Integer(['default' => 1, 'max_length' => 75]) 
     */
    public $id;
    /**
     * @type Integer(['default' => 1, 'max_length' => 75]) 
     */
    public $name;
    /**
     * @type Char(['default' => 1, 'max_length' => 75]) 
     */
    public $first_name;
    /** 
     * @type Char(['default' => 1, 'max_length' => 75]) 
     */
    public $last_name;
    
    /**
     * To string
     */
    public function __toString()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

$user = new User(false);

// DEBUGGING SOME PERFORMANCE STUFF
// function milliseconds(/* ... */) {
//     return round(microtime(true) * 1000);
// }

// $microtime = milliseconds();
// for ($i = 0; $i != 10; $i++) {
//     $user = new User(false);
// }
// echo "Time taken : " . (( milliseconds() - $microtime ) / 6000 ). PHP_EOL;
// $microtime = milliseconds();
// for ($i = 0; $i != 10; $i++) {
//     $user = new User();
// }
// echo "Time taken : " . (( milliseconds() - $microtime ) / 6000 ). PHP_EOL;