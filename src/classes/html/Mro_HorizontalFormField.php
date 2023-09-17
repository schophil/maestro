<?php

namespace Maestro\html;

/**
 * Creates a simpel hirozontal form field.
 */
class Mro_HorizontalFormField extends Mro_HtmlElement
{

    private $input;
    private $label;

    function __construct($input, $label = null)
    {
        $this->input = $input;
        $this->label = $label;
    }

    function createHtml()
    {
        $html = null;
        if (!is_null($this->label)) {
            $html = "<span>{$this->label}</span>";
        } else {
            $html = '';
        }

        return $html .= "{$this->input}";
    }
}