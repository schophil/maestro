<?php

namespace Maestro\db;

use Maestro\util\Mro_DateTime;
use Monolog\Registry;

/**
 * Result set implementation for mysql.
 */
class Mro_MysqlResultSet
{

    private \mysqli_result $result;
    private $log;
    private $row;

    /**
     * Default constructor.
     *
     * @param unknown $result A PHP mysql result set object.
     */
    function __construct(\mysqli_result $result)
    {
        $this->result = $result;
        $this->log = Registry::getInstance('db');
    }

    /**
     * Positions the pointer to the row with the given index.
     *
     * @param int $rowNumber
     * @return boolean True if it succeeded.
     */
    function forward(int $rowNumber)
    {
        return mysqli_data_seek($this->result, $rowNumber);
    }

    /**
     * Sets the iterator to the next row if one is available.
     *
     * @return boolean True if a next row is available, else False.
     */
    function next()
    {
        $this->row = mysqli_fetch_row($this->result);
        return $this->row != false;
    }

    /**
     * Gets the value for a specific column of the current row.
     *
     * @param integer $index Index of the column in the result set.
     * @return string The value, else null.
     */
    function get($index): ?string
    {
        if ($this->row != false) {
            return $this->row[$index];
        }
        return null;
    }

    /**
     * Gets the date value of a column.
     *
     * @param integer $index Index of the column in the result set.
     * @return Mro_DateTime
     */
    function getDate($index): ?Mro_DateTime
    {
        if ($this->row != false) {
            $value = $this->row[$index];
            if (!is_null($value)) {
                $this->log->debug("reading date value {$value}");
                $date = new Mro_DateTime();
                $date->deformat($value, 'Y-M-D');
                return $date;
            }
            return null;
        }
        return null;
    }

    /**
     * Gets the datetime value of a column.
     *
     * @param integer $index Index of the column in the result set.
     * @return Mro_DateTime
     */
    function getDateTime($index): ?Mro_DateTime
    {
        if ($this->row != false) {
            $value = $this->row[$index];
            if (!is_null($value)) {
                $this->log->debug("reading date time value {$value}");
                $date = new Mro_DateTime();
                $date->deformat($value, 'Y-M-D h:m:s');
                return $date;
            }
            return null;
        }
        return null;
    }

    /**
     * Gets the string value for a specific column of the current row.
     *
     * @param integer $index Index of the column in the result set.
     * @return string The value, else null.
     */
    function getString($index): ?string
    {
        return $this->get($index);
    }

    /**
     * Gets the integer value for a specific column of the current row.
     *
     * @param integer $index Index of the column in the result set.
     * @return integer The value, else null.
     */
    function getInteger($index): ?int
    {
        if ($this->row != false) {
            $value = $this->row[$index];
            if (!is_null($value)) {
                return intval($value);
            }
        }
        return null;
    }
}
