<?php

namespace Maestro\html;

/**
 */
class Mro_HiddenField
{

    private $name;
    private $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
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
        $this->value = "{$value}";
    }

    public function readValue($context)
    {
        return $context->getArg($this->name);
    }

    public function isHidden()
    {
        return true;
    }

    public function createHtml()
    {
        return "<input type=\"hidden\" name=\"{$this->name}\" value=\"{$this->value}\" />";
    }

    public function __toString()
    {
        return $this->createHtml();
    }
}
