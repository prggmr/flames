<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

date_default_timezone_set('UTC');

/**
 * Connect to a database
 */
require_once '../src/flames.php';


// Same connection string as PDO
flames\Connections::add(new flames\driver\MySQL(
    'mysql:dbname=flames;host=127.0.0.1', 
    'root', 
    ''
));
