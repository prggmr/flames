<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Char Field
 */
class Char extends \flames\Field {

    /**
     * Default length
     */
    protected $_max_length = 255;

    /**
     * Field type
     */
    protected $_type = 'varchar';
}