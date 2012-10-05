<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

require_once 'connection.php';
require_once 'model.php';

/**
 * Create a model and do something to it
 */
// $model = new User();
// $model->username = 'nwhiting';
// $model->save()->exec();

prggmr\before(new \flames\signal\model\Update(new User()), function(){
    echo "RUNNING THIS";
    // var_dump($this);
    var_dump($this->get_statement());
    // var_dump($this->get_query()->get_bind());
});

// $user = new User();
// $user->username = 'prggmr';
// $user->password = 'myPa$$word';
// $user->save()->exec();

$result = User::find([
    'username' => 'pigface',
    'password' => 'myPa$$word'
])->first();
// var_dump($result);
echo $result->username;
$result->username = "pigface";
$result->save()->exec();


// /**
//  * Foreign key fields automatically save!
//  */
// $profile = new Profile();
// $profile->first_name = "Nick";
// $profile->last_name = "Whiting";
// $profile->user->email = "prggmr@gmail.com";
// $profile->user->username = "prggmr";
// $profile->user->password = 'myPa$$word';
// $profile->save()->exec();
// vaR_dump($profile);

/**
 * Now lets select that profile we just saved
 */
// $profile = new Profile();
// $profile = $profile->select()->where(['first_name' => 'Nick'])->exec()->first();
// var_dump($profile);
// echo $profile->user->username.PHP_EOL;

// /**
//  * Now Update
//  */
// $profile->first_name = "Nickolas";
// $profile->save()->exec();
// $profile->delete()->exec();
// var_dump($profile);

// $profile = new Credit();
// $profile->card_number = '527893459823749587234958';
// $profile->save()->exec();