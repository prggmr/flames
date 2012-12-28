<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

// library version
define('FLAMES_VERSION', '1.0.0');

// The creator
define('FLAMES_MASTERMIND', 'Nickolas Whiting');

$path = dirname(realpath(__FILE__));

// Add this to include path
set_include_path(
    $path . '/../..' . PATH_SEPARATOR . get_include_path()
);

if (!class_exists('xpspl')) {
    require_once 'xpspl/src/xpspl.php';
}

require_once $path.'/utils.php';
unset($path);