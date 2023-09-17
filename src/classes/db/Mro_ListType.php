<?php
/**
 * @author Philippe Schottey <phschotte@gmail.com>
 * @package db
 */

namespace Maestro\db;

/**
 * A field of type "list" can contain a fix list of values.
 */
class Mro_ListType
{

    private $values;

    /**
     * Default constructor.
     */
    function __construct($values = null)
    {
        if (is_null($values)) {
            $this->values = array();
        } else {
            $this->values = $values;
        }
    }

    /**
     * Returns the database type for this field type.
     * @return integer Database type constant.
     */
    function getDatabaseType()
    {
        return CHAR_TYPE;
    }

    /**
     * Adds a possible value to the list.
     * @param string $value The value.
     */
    function addValue($value)
    {
        $this->values[] = $value;
    }

    /**
     * Returns the array of possible values.
     */
    function getValues()
    {
        return $this->values;
    }

    /**
     * Checks if the given string is a valud value.
     * @param string $value The value to check.
     * @return boolean
     */
    function isValidValue($value)
    {
        return array_key_exists($value, $this->values);
    }
}
