<?php
/**
 * @author Philippe Schottey <pschotte@mac.com>
 * @package db
 */

namespace Maestro\db;

/**
 * The type for integer fields.
 */
class Mro_IntegerType
{

    private $min;

    private $max;

    /**
     * Constructor.
     * @param integer $min The minimal value for the values;
     * @param integer $max The maximal value for the values;
     */
    function __construct($min = NULL, $max = NULL)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Returns the real database type.
     * @return integer Database type.
     */
    function getDatabaseType()
    {
        return NUMBER_TYPE;
    }

    /**
     * Returns the minimal value.
     * @return integer The minimal value for the values. NULL if no min.
     */
    function getMin()
    {
        return $this->min;
    }

    /**
     * Returns the maximal value.
     * @return integer The maximal value for the values. NULL if no max.
     */
    function getMax()
    {
        return $this->max;
    }

    /**
     * Checks if a given number is in range for this integer type.
     * @param integer $number The number to check.
     */
    function inRange($number)
    {
        if (!is_null($this->min) && $number < $this->min) {
            return false;
        }
        if (!is_null($this->max) && $number > $this->max) {
            return false;
        }
        return true;
    }
}
