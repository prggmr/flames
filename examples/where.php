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
$model = new User();

$query = $model->select()->where([
    '&username__in' => ['xpspl', 'jream']
])->order_by('RAND()', 'DESC')->limit(0, 25);

$query->exec();

$results = $record->get_result();

var_dump($record->get_parameters());

var_dump($record);

// echo $record->user_id.PHP_EOL;
// $record->username = "jboyer";
// $record->delete()->exec();
// echo $record->user_id.PHP_EOL;

/**
 * Count Records
 */
// $user_total = User::find()->exec->count();