<?php

namespace Maestro\html;

/**
 * A Mro_TextField is a HTML input field should be used for passing textual values.
 * All values will be interpreted as strings.
 */
class Mro_TextField extends Mro_HtmlElement
{

    private $name;
    private $value;
    private $editable;
    private $enabled;
    private $alt;
    private $maxLength;

    /**
     * Constructor.
     * @param string $name The unique name of this text field.
     * @param boolean $editable Indicates if this field is editable.
     */
    function __construct($name, $editable = true)
    {
        parent::__construct();

        $this->name = $name;
        $this->editable = $editable;
        $this->addClass('mroTextField');
        $this->enabled = true;
    }

    /**
     * Sets the maximal length of this string field.
     * @param int $maxLength The maxima length of this input field.
     */
    function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * Returns the maximal length of this input field.
     * @return integer.
     */
    function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * Sets the value for the alt attribute.
     * @param object $alt
     */
    function setAlt($alt)
    {
        $this->alt = $alt;
    }

    /**
     * Returns the value for the alt attribute.
     * @return object
     */
    function getAlt()
    {
        return $this->alt;
    }

    /**
     * Returns the name of this input field.
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Sets this input field editable or not.
     * @param boolean $editable
     */
    function setEditable($editable)
    {
        $this->editable = $editable;
    }

    /**
     * Checks if this input field is editable.
     * @return boolean
     */
    function isEditable()
    {
        return $this->editable;
    }

    /**
     */
    function enable()
    {
        $this->enabled = true;
    }

    /**
     */
    function disable()
    {
        $this->enabled = false;
    }

    /**
     */
    function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Registers the value that should be shown in the input field.
     * Since this is a text field the value will be converted to string.
     * @param object $value .
     */
    function writeValue($value)
    {
        $this->value = "{$value}";
    }

    /**
     * Reads the value for this input field from the conductor context.
     * Since this is a text field the value will be returned as string.
     * @param Mro_Context $context
     * @return string
     */
    function readValue($context)
    {
        $value = $context->getArg($this->name);

        if (!is_null($value)) {
            $value = trim($value);
            if ($value === '') {
                $value = null;
            } elseif (is_array($value) && sizeof($value) == 0) {
                $value = null;
            }
        }

        return $value;
    }

    /**
     * Checks if this is a hidden field.
     * @return boolean
     */
    function isHidden()
    {
        return false;
    }

    /**
     * Creates the HTML code for this input field.
     * @return string The HTML code in a string.
     */
    function createHtml()
    {
        $html = "<input class=\"mroTextField\" type=\"text\" name=\"{$this->name}\" value=\"{$this->value}\"";
        if (!$this->editable) {
            $html .= ' readonly="readonly"';
        }
        if (!$this->enabled) {
            $html .= ' disabled';
        }
        if (!is_null($this->alt)) {
            $html .= " alt=\"{$this->alt}\"";
        }
        if (!is_null($this->maxLength)) {
            $html .= " maxlength=\"{$this->maxLength}\"";
        }
        return $html . '/>';
    }
}
