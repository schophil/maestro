<?php

namespace Maestro\html;

use Monolog\Registry;

/**
 * class
 */
class Mro_SelectField
{

    private $values;

    private $selected;

    private $name;

    private $editable;

    private $enabled;

    private $hidden;

    private $alt;

    private $log;

    /**
     * Constructor.
     *
     * @param string $name The name of the select field.
     * @param boolean $editable Indicates if the field is editable.
     */
    function __construct($name, $editable = true)
    {
        $this->name = $name;
        $this->editable = $editable;
        $this->log = Registry::getInstance('html');
    }

    /**
     * Sets the possible values for the select.
     *
     * @param array $values Key value pairs.
     */
    function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * Sets the selected value for the input field.
     * The value is converted to string.
     *
     * @param any $value The selected value.
     */
    function writeValue($value)
    {
        $this->selected = "{$value}";
    }

    /**
     * Reads the value of this select field from the context.
     *
     * @param Mro_Context $context The context.
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
     * Indicates if this field is hidden.
     *
     * @return boolean
     */
    function isHidden()
    {
        return $this->hidden;
    }

    /**
     * Returns the name of this field. This is the unique name used in the form.
     *
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Configures if this users can select a new value.
     *
     * @param boolean $editable
     */
    function setEditable($editable)
    {
    }

    /**
     * Enables this field.
     */
    function enable()
    {
        $this->enabled = true;
    }

    /**
     * Disables this field.
     */
    function disable()
    {
        $this->enabled = false;
    }

    /**
     * Checks if this select field is editable.
     *
     * @return boolean
     */
    function isEditable()
    {
        return false;
    }

    /**
     * Sets the alt information.
     *
     * @param string alt
     */
    function setAlt($alt)
    {
        $this->alt = $alt;
    }

    function __toString()
    {
        return $this->createHtml();
    }

    /**
     * Creates the select tag.
     */
    function createHtml()
    {
        $html = "<select class=\"mroSelectField\" name=\"{$this->name}\">";
        if (!$this->enabled) {
            $html .= ' disabled';
        }

        if (!is_null($this->alt)) {
            $html .= " alt=\"{$this->alt}\"";
        }

        $this->log->debug("selected value = {$this->selected}");
        foreach ($this->values as $value => $label) {
            $html .= "<option value=\"{$value}\"";

            $this->log->debug("option {$value} = {$label}");

            if ($this->selected == $value) {
                $html .= ' selected="yes"';
            }
            $html .= ">{$label}</option>";
        }

        return $html . '</select>';
    }
}
