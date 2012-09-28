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
$record = $model->select()->where([
    ['username__endswith' => 'prgr'],
    [
        '|email__like' => 'nwhitin',
        '|password__isnull'
    ]
])->exec()->first();
