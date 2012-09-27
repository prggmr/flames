<?php
namespace flames\query\bind;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */


/**
 * Interface that for classes to return bindable values.
 */
interface Value {
    /**
     * Returns the value
     *
     * @return  string
     */
    public function get_value(/* ... */);
}