<?php
namespace flames\field;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * Slug Field
 */
class Slug extends Char {

    /**
     * Default length
     */
    protected $_max_length = 75;

    /**
     * Slug fields are always unique.
     */
    protected $_unique = true;

    /**
     * Sets the current value and automatically transforms into a URL slug.
     *
     * @param  mixed  $val  Value to set the field.
     * @param  boolean  $db  Value if coming from db and already slugified.
     *
     * @return  object  this
     */
    public function set_value($val, $db = false)
    {
        $this->__value = self::slugify($val);
    }

    /**
     * Transforms the giving text into a slug.
     *
     * @param  string  $text  Text to transform.
     *
     * @return  string
     */
    public static function slugify($text)
    {
        return preg_replace('/\W+/', '-', strtolower(
            iconv('UTF-8', 'ASCII//TRANSLIT', $text)
        ));
    }
}