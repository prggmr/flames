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

if (!defined('FLAMES_CACHE_DIR')) {
    define('FLAMES_CACHE_DIR', '/tmp/.flames-php-cache');
}

if (false !== FLAMES_CACHE_DIR) {
    if (!is_dir(FLAMES_CACHE_DIR)) {
        mkdir(FLAMES_CACHE_DIR);
    }

    if (!is_writeable(FLAMES_CACHE_DIR)) {
        throw new RuntimeException(sprintf(
            "flames cache dir %s is not writeable"
        ), FLAMES_CACHE_DIR);
    }
    define('FLAMES_CACHE_MODELS', true);
} else {
    define('FLAMES_CACHE_MODELS', false);
}

$dir = dirname(realpath(__FILE__));

// start'er up
require $dir.'/connections.php';
require $dir.'/driver.php';
require $dir.'/driver/mysql.php';
require $dir.'/model.php';
require $dir.'/field.php';
require $dir.'/field/integer.php';
require $dir.'/field/char.php';
require $dir.'/field/primary.php';

// if (!class_exists('prggmr')) {
//     require_once 'prggmr/src/prggmr.php';
// }
