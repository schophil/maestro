<?php

namespace Maestro\html;

/**
 */
class Mro_TextAreaField extends Mro_HtmlElement
{

    private $name;

    private $value;

    private $editable;

    private $alt;

    private $maxLength;

    function __construct($name, $editable = false)
    {
        parent::__construct();

        $this->name = $name;
        $this->editable = $editable;
        $this->addClass('mroTextArea');
    }

    function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
    }

    function writeValue($value)
    {
        // for now just force a to string of the value
        $this->value = "{$value}";
    }

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

    function setAlt($text)
    {
        $this->alt = $text;
    }

    function isHidden()
    {
        return $this->editable;
    }

    function getName()
    {
        return $this->name;
    }

    function setEditable($editable)
    {
        $this->editable = $editable;
    }

    function isEditable()
    {
        return $this->editable;
    }

    function createHtml()
    {
        $html = $this->appendDefaultAttributes('<textarea');

        if (!$this->isEditable()) {
            $html .= ' readonly';
        }

        $html .= " name=\"{$this->name}\">";
        return $html . "{$this->value}</textarea>";
    }
}
