<?php

namespace Maestro\util;

/**
 * Utility class to help trace execution time.
 */
class Mro_Trace
{

    private $start;

    /**
     * Indicates the start of the measurement.
     */
    function start()
    {
        $this->start = microtime(true);
    }

    /**
     * Indicates the end of the measurement.
     * @return float The measured time in miliseconds.
     */
    function end()
    {
        $end = microtime(true);
        return ($end - $this->start) * 1000;
    }
}
