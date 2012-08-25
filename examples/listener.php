<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

require_once 'connection.php';

class User extends \flames\Model {
    
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

class User_Listener extends \flames\Listener {

    /**
     * On a select statement
     */
    public function on_select(\flames\events\Select $event) 
    {
        echo "Running SQL $event->sql".PHP_EOL;
    }

}

prggmr\signal_interrupt(new \flames\signal\Select(), function(){
    echo "Interrupting this shit!";
});

/**
 * Register the listener
 */
\prggmr\listen(new User_Listener(new User()));

/**
 * Create a model and do something to it
 */
$model = new User();
$record = $model->select()->exec();

var_dump(\prggmr\prggmr());