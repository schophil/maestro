<?php

namespace Maestro\db;

/**
 * BLOB type containing an XHTML fragment.
 * XHTML edited using TinyMCE.
 * Database type will be: CLOB.
 */
class Mro_XhtmlType
{

    /**
     * Returns the database type for this abstract type.
     * @return integer The database type constant.
     */
    function getDatabaseType(): int
    {
        return CLOB_TYPE;
    }
}
