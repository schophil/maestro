<?php

namespace Maestro\html;

/**
 * A Mro_DateField is a HTML input field that only accepts date values.
 */
class Mro_DateField extends Mro_TextField
{

    private $format;

    /**
     * Constructor.
     * @param $name Tha name of this input field.
     * @param $editable Indicates if this input field is editable.
     */
    function __construct($name, $editable = true)
    {
        parent::__construct($name, $editable);

        // overwrite the main CSS class
        $this->removeAllClasses();
        $this->addClass('mroDateField');
    }

    /**
     * Registers the date format to use.
     * @param string $format
     */
    function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Returns the date format to use in this date input field.
     * @return string
     */
    function getFormat()
    {
        return $this->format;
    }

    function writeValue($value)
    {
        if (!is_null($value) && is_a($value, 'Mro_DateTime')) {
            $text = $value->format($this->format);
            parent::writeValue($text);
        }
    }

    /**
     * The value read from the context will be interpreted as a date.
     * @return Mro_DateTime
     */
    function readValue($context)
    {
        $value = parent::readValue($context);
        if (!is_null($value)) {
            $date = new Mro_DateTime();
            $date->deformat($value, $this->format);
            return $date;
        }
        return $value;
    }
}
