<?php

namespace Maestro\html;

/**
 * Object representing the raw HTML header.
 */
class Mro_Header
{

    private $exitAfterSend;
    private $lines;

    function __construct($exitAfterSend = false)
    {        $this->exitAfterSend = $exitAfterSend;
        $this->lines = array();
    }

    function add($line)
    {
        $this->lines[] = $line;
    }

    function addLocation($location)
    {
        $this->add("Location: {$location}");
    }

    function isExitAfterSend()
    {
        return $this->exitAfterSend;
    }

    function send()
    {
        foreach ($this->lines as $line) {
            header($line);
        }

        if ($this->exitAfterSend) {
            exit;
        }
    }
}
