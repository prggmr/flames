<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

// library version
define('FLAMES_VERSION', '0.0.0-dev');

// The creator
define('FLAMES_MASTERMIND', 'Nickolas Whiting');


$dir = dirname(realpath(__FILE__));

// start'er up
require $dir.'/driver.php';
require $dir.'/driver/mysql.php';
require $dir.'/model.php';
require $dir.'/field.php';
require $dir.'/field/integer.php';
require $dir.'/field/char.php';
