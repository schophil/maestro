<?php

namespace Maestro\db;

/**
 * Internal class used to represent a parsed token.
 * @ignore
 */
class Mro_Token
{

    public $name;
    public $begin;
    public $length;
    public $isGuardian = false;
    public $ommitType = false;

    function __construct($begin)
    {
        $this->begin = $begin;
    }

    function isToken()
    {
        return true;
    }

    function isClause()
    {
        return false;
    }
}