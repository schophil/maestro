<?php

namespace Maestro\db;

/**
 * A field of type "text" contains a text fragment.
 * database type: CHAR_TYPE
 */
class Mro_TextType
{

    private $max;

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
    function setMaxSize($max): void
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
