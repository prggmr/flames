<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

require_once 'connection.php';
require_once 'model.php';

class Select_Listener extends \flames\Listener {
    /**
     * On a select statement
     */
    public function on_select(\flames\query\event\Select $event) 
    {
        echo "Running SQL ".$event->get_statement()->queryString;
    }
}

before(new \flames\signal\Select(), new \xpspl\Handle(function(){
    echo "Interrupting select statements!!".PHP_EOL;
    echo "Maybe I'll check the cache".PHP_EOL;
}, null));

// I will interrupt only on the User Model!!
before(new \flames\signal\model\Select(new User()), function(){
    echo "This will interrupt only user selects!".PHP_EOL;
});

before(new \flames\signal\model\Select(new Profile()), function(){
    echo "This will interrupt only profile selects!".PHP_EOL;
});

/**
 * Register the listener
 */
listen(new Select_Listener());

/**
 * Create a model and do something to it
 */
$model = new User();
$record = $model->select()->exec();

$profile = new Profile();
$profile_record = $profile->select()->exec();