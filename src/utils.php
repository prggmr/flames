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