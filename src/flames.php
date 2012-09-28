<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

// library version
define('FLAMES_VERSION', '1.0.0-RC1');

// The creator
define('FLAMES_MASTERMIND', 'Nickolas Whiting');

// Add this to include path
$flames_path = dirname(realpath(__FILE__));
set_include_path($flames_path.'/../../' . PATH_SEPARATOR . get_include_path());

if (!class_exists('prggmr')) {
    require_once 'prggmr/src/prggmr.php';
}