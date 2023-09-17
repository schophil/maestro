<?php
// vim: set ts=4 sw=4 autoindent:
/**
 * @author Philippe Schottey <phschotte@gmail.com>
 * @package db
 */

namespace Maestro\db;

/**
 * Generates an id based on the current date. Not 100% safe!
 */
class Mro_DefaultGenerator implements Mro_IdGenerator {

	/**
	 * Generate a new id.
	 *
	 * @param object $connection A database connection
	 */
    function generate($connection) {
        $date = getdate();
        $month = $this->expand("{$date['mon']}");
        $day = $this->expand("{$date['mday']}");
        $hours = $this->expand("{$date['hours']}");
        $minutes = $this->expand("{$date['minutes']}");
        $seconds = $this->expand("{$date['seconds']}");
        	
        return "{$date['year']}{$month}{$day}{$hours}{$minutes}{$seconds}";
    }

    private function expand($str) {
        if (strlen($str) == 1) {
            return '0' . $str;
        }
        return $str;
    }
}
