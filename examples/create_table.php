<?php
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

require_once 'connection.php';
require_once 'model.php';

// Create a table using the create_table method

// Create the user table
$user = new User();
$user->create_table(true);

// Create the profile table
$profile = new Profile();
// Give the "true" parameter to make it create safe ( IF NOT EXISTS )
$profile->create_table(true);

$credt = new Credit();
$credt->create_table(true);

