<?php
/**
 * @author Philippe Schottey <ph.schottey@gmail.com>
 * @package util
 */

namespace Maestro\util;

/**
 * Simple date time class. It encapsulates the default PHP representation of a
 * datetime.
 */
class Mro_DateTime
{

    private $date;

    /**
     * Default constructor.
     */
    function __construct($date = null)
    {
        if (isset($date)) {
            $this->date = $date;
        } else {
            $this->date = getDate();
        }
    }

    /**
     * Returns the year of the date.
     * @return integer The year.
     */
    function getYear()
    {
        return $this->date['year'];
    }

    /**
     * Sets the year of the date.
     * @param integer $year A valid year as integer.
     */
    function setYear($year)
    {
        return $this->date['year'] = $year;
    }

    /**
     * Returns the month of the date.
     * @return integer $month The month as integer.
     */
    function getMonth()
    {
        return $this->date['mon'];
    }

    /**
     * Sets the month of the date.
     * @param integer $month A valid month as integer.
     */
    function setMonth($month)
    {
        return $this->date['mon'] = $month;
    }

    /**
     * Returns the day of the date (number to month).
     * @return integer Integer expressing the day of the month.
     */
    function getDay()
    {
        return $this->date['mday'];
    }

    /**
     * Sets the day of the date (number to month).
     * @param integer $day The day of the month as integer.
     */
    function setDay($day)
    {
        return $this->date['mday'] = $day;
    }

    /**
     * Returns the hours of the date.
     * @return integer Integer expressing the hours of the time.
     */
    function getHours()
    {
        return $this->date['hours'];
    }

    /**
     * Sets the hours of the date.
     * @param integer $hours A valid number for hours.
     */
    function setHours($hours)
    {
        return $this->date['hours'] = $hours;
    }

    /**
     * Returns the minutes of this datetime.
     * @return integer The minutes as integer.
     */
    function getMinutes()
    {
        return $this->date['minutes'];
    }

    /**
     * Sets the minutes on this datetime.
     * @param integer $minutes A valid number for minutes.
     */
    function setMinutes($minutes)
    {
        return $this->date['minutes'] = $minutes;
    }

    /**
     * Returns the current seconds.
     * @return integer The number of seconds.
     */
    function getSeconds()
    {
        return $this->date['seconds'];
    }

    /**
     * Sets the number of seconds.
     * @param integer $seconds A valid number of seconds.
     */
    function setSeconds($seconds)
    {
        return $this->date['seconds'] = $seconds;
    }

    /**
     * Formats this date following the given pattern.
     * @param string $format The format to use.
     * @return string A formatted string representation of the date & time.
     */
    function format($format)
    {
        $text = '';
        $i = 0;
        $max = strlen($format);
        while ($i < $max) {
            $char = $format[$i];
            if ($char === 'Y') {
                $text = $text . $this->getYear();
            } elseif ($char === 'M') {
                $text = $text . $this->_digit($this->getMonth(), 2);
            } elseif ($char === 'D') {
                $text = $text . $this->_digit($this->getDay(), 2);
            } elseif ($char === 'h') {
                $text = $text . $this->_digit($this->getHours(), 2);
            } elseif ($char === 'm') {
                $text = $text . $this->_digit($this->getMinutes(), 2);
            } elseif ($char === 's') {
                $text = $text . $this->_digit($this->getSeconds(), 2);
            } else {
                $text = $text . $char;
            }
            $i++;
        }
        return $text;
    }

    /**
     * Deformats a given date as string and initializes this date to the found
     * time.
     * @param string $dateTimeString The string expressing the datetime.
     * @param string $format The format used in the datetime string.
     */
    function deformat($dateTimeString, $format)
    {
        // reset date
        $this->setYear(0);
        $this->setMonth(0);
        $this->setDay(0);
        $this->setHours(0);
        $this->setMinutes(0);
        $this->setSeconds(0);
        // parse format
        $i = 0;
        $position = 0;
        $max = strlen($format);
        while ($i < $max) {
            $char = $format[$i];
            if ($char === 'Y') {
                $this->setYear(intval(substr($dateTimeString, $position, 4)));
                $position += 4;
            } elseif ($char === 'M') {
                $this->setMonth(intval(substr($dateTimeString, $position, 2)));
                $position += 2;
            } elseif ($char === 'D') {
                $this->setDay(intval(substr($dateTimeString, $position, 2)));
                $position += 2;
            } elseif ($char === 'h') {
                $this->setHours(intval(substr($dateTimeString, $position, 2)));
                $position += 2;
            } elseif ($char === 'm') {
                $this->setMinutes(intval(substr($dateTimeString, $position, 2)));
                $position += 2;
            } elseif ($char === 's') {
                $this->setSeconds(intval(substr($dateTimeString, $position, 2)));
                $position += 2;
            } else {
                $position++;
            }
            $i++;
        }
    }

    /**
     * Creates a string representation of this object.
     * @return string A string representation.
     */
    function __toString()
    {
        return $this->format('D/M/Y');
    }

    private function _digit($value, $size)
    {
        $text = strval($value);
        while (strlen($text) < $size) {
            $text = '0' . $text;
        }
        return $text;
    }
}
