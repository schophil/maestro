<?php

namespace Maestro\db;

/**
 * Internal class used to represent a parsed clause.
 * @ignore
 */
class Mro_Clause
{

    public $content;
    public $begin;
    public $end;
    public $tokens;

    function __construct($begin)
    {
        $this->begin = $begin;
    }

    function isToken()
    {
        return false;
    }

    function isClause()
    {
        return true;
    }
}