<?php
namespace flames\driver;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * MySQL flames driver.
 *
 * For now this driver is blank :)
 */
class MySQL extends \PDO {

    /**
     * Extend the main db driver class.
     */
    use \flames\Driver;
}