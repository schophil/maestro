<?php
/**
 * @author Philippe Schottey <pschotte@mac.com>
 * @package db
 */

namespace Maestro\db;

/**
 * Interface for all id generators.
 */
interface Mro_IdGenerator
{

    /**
     * Function that generates a unique id.
     * @param Mro_MysqlSession A connection to the MySQL database.
     * @return string The next available unique id.
     */
    function generate($connection);
}
