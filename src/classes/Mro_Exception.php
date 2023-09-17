<?php
// vim: set sw=4 ts=4:
/**
 * @author Philippe Schottey <phschotte@gmail.com>
 * @package maestro
 */

namespace Maestro;

use Exception;

/**
 * The exception class used in the maestro framework.
 */
class Mro_Exception extends Exception {

	/**
	 * Default constructor.
	 *
	 * @param mixed $message Must be string-able.
	 * @param integer $code Optional error code.
	 */
	public function __construct($message, $code = 0) {
		parent::__construct("$message", $code);
	}
}
