<?php
namespace flames;

/**
 * Return MySQL DATETIME for the current or given time.
 *
 * @param  string|integer|null  $time  Timestamp or time string to use
 *
 * @return  string  MySQL Datetime (YYYY-MM-DD HH:MM:SS)
 */
function mysql_datetime($time = null)
{
    switch(true) {
        case null === $time:
            $time = time();
            break;
        case is_string($time):
            $time = strtotime($time);
            break;
    }
    return date('Y-m-d G:i:s', $time);
}

/**
 * Returns the name of a class using get_class with the namespaces stripped.
 * This will not work inside a class scope as get_class().
 * 
 * A workaround for that is using get_class_name(get_class());
 *
 * @param  object|string  $object  Object or Class Name to retrieve name

 * @return  string  Name of class with namespaces stripped
 */
function get_class_name($object = null)
{
    if (!is_object($object) && !is_string($object)) {
        return false;
    }
    
    $class = explode('\\', (is_string($object) ? $object : get_class($object)));
    return $class[count($class) - 1];
}