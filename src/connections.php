<?php
namespace flames;
/**
 * Copyright 2010-12 Nickolas Whiting. All rights reserved.
 * Use of this source code is governed by the Apache 2 license
 * that can be found in the LICENSE file.
 */

/**
 * DB Connections
 */
final class Connections {
    
    /**
     * Current available connections.
     * 
     * @var  array
     */
    private static $_connections = [];

    /**
     * Default connection.
     * 
     * @var  string
     */
    private static $_default = null;

    /**
     * You cannot construct the connections!!
     */
    private function __construct() {}

    /**
     * Adds a new driver connection.
     * 
     * @param  object  $driver  flames\Driver
     * @param  string  $name  Driver name identifier
     * @param  boolean  $default  Make this default
     *
     * @return  void
     */
    public static function add($driver, $name = null, $default = false)
    {
        if (null === $name) {
            $name = str_replace('flames\\driver\\', '', get_class($driver));
        } elseif (!is_string($name)) {
            throw new \InvalidArgumentException("Invalid driver name");
        }
        $uses = class_uses($driver);
        if (in_array('\flames\Driver', $uses) && $driver instanceof \PDO) {
            throw new \InvalidArgumentException("Driver is not valid");
        }
        if (isset(static::$_connections[$name])) {
            throw new \RuntimeException(sprintf(
                "Driver by name %s already exists",
                $name
            ));
        }
        if ($default || static::$_default === null) {
            static::$_default = $name;
        } 
        static::$_connections[$name] = $driver;
    }

    /**
     * Returns a DB driver connection.
     * 
     * @param  string|null  $name  DB driver or null for default.
     * 
     * @return  boolean|object  DB Driver|False if not exist
     */
    public static function get($name = null)
    {
        if ($name === null) {
            if (static::$_default === null) {
                throw new \RuntimeException(
                    "A default connection has not been established."
                );
            }
            return static::$_connections[static::$_default];
        }
        if (!isset(static::$_connections[$name])) {
            throw new LogicException(sprintf(
                "Unknown connection %s",
                $name
            ));
        }
        return static::$_connections[static::$_default];
    }

    /**
     * Returns the currently available connections.
     *
     * @return  array
     */
    public function get_connections()
    {
        return array_keys(static::$_connections);
    }
}