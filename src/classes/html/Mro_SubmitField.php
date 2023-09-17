<?php

namespace Maestro\html;

/**
 * A Mro_SubmitField is a HTML submit button.
 */
class Mro_SubmitField
{

    private $label;
    private $enabled;
    private $name;

    public function __construct($label, $name = null)
    {
        $this->label = $label;
        $this->enabled = true;
        $this->name = $name;
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

    public function getName()
    {
        return null;
    }

    public function setEditable($editable)
    {
    }

    public function isEditable()
    {
        return false;
    }

    public function writeValue($value)
    {
    }

    public function readValue($context)
    {
        return null;
    }

    public function isHidden()
    {
        return false;
    }

    public function createHtml()
    {
        $html = '<input class="mroSubmitFld" type="submit"';
        if (!is_null($this->name)) {
            $html .= " name=\"{$this->name}\"";
        }
        if (!$this->enabled) {
            $html .= ' disabled';
        }

        $html .= " value=\"{$this->label}\" />";
        return $html;
    }

    public function __toString()
    {
        return $this->createHtml();
    }
}
