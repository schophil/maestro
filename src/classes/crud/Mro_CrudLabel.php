<?php

namespace Maestro\crud;

/**
 * includes
 */
class Mro_CrudLabel
{

    private $name;

    private $transate;

    function __construct($name, $translate = false)
    {
        $this->name = $name;
        $this->translate = $translate;
    }

    function createHtml()
    {
        return "{$this->name}";
    }

    function __toString()
    {
        return $this->createHtml();
    }
}
