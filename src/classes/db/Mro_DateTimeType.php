<?php
/**
 * @author Philippe Schottey <phschotte@mac.com>
 * @package db
 */

namespace Maestro\db;

/**
 * Represents the date type.
 */
class Mro_DateTimeType
{

    private string $externalFormat;

    /**
     * Constructor.
     * @param string $internalFormat The format used in towards the database.
     * @param string $crudFormat The format used in crud application.
     */
    function __construct(string $externalFormat = 'D/M/Y h:m:s')
    {
        $this->externalFormat = $externalFormat;
    }

    /**
     * Returns the external format to use for the dates of this type.
     */
    function getExternalFormat(): string
    {
        return $this->externalFormat;
    }

    /**
     * Returns the database type.
     * @return integer Database type constant.
     */
    function getDatabaseType(): string
    {
        return DATE_TIME_TYPE;
    }
}

