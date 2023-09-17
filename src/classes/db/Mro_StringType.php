<?php
/**
 * @author Philippe Schottey <pschotte@mac.com>
 * @package db
 */

namespace Maestro\db;

/**
 * A field of type "list" can contain a fix list of values.
 * database type: CHAR_TYPE
 */
class Mro_StringType
{
    private int $max;

    /**
     * Default constructor.
     */
    function __construct($max = 0)
    {
        $this->max = $max;
    }

    /**
     * Returns the database type for this abstract type.
     * @return integer The database type constant.
     */
    function getDatabaseType(): int
    {
        return CHAR_TYPE;
    }

    /**
     * Sets the maximal size.
     * @param integer $max The maximal size of the string
     */
    function setMaxSize(int $max): void
    {
        $this->max = $max;
    }

    /**
     * Gets the maximal size of this string.
     * @return integer The maximal size.
     */
    function getMaxSize(): int
    {
        return $this->max;
    }
}
