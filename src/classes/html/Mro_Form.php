<?php

namespace Maestro\html;

/**
 * Interface of every HTML element.
 */
class Mro_Form extends Mro_HtmlElement
{

    private $action;
    private $method;
    private $content;

    function __construct($action, $method = 'POST')
    {
        parent::__construct();
        $this->action = $action;
        $this->method = $method;
        $this->content = array();
        $this->addClass('mroForm');
    }

    /**
     * Adds a inner element.
     *
     * @param object $element
     */
    function add($element)
    {
        $this->content[] = $element;
    }

    /**
     * Add a hidden field to this form.
     *
     * @param string $name The name of the field.
     * @param object $value The value of the field.
     * @return object The new hidden field.
     */
    function addHiddenField($name, $value)
    {
        $field = new Mro_HiddenField($name, $value);
        $this->add($field);
        return $field;
    }

    function createHtml()
    {
        $html = $this->appendDefaultAttributes('<form');

        $html .= " action=\"{$this->action}\" method=\"{$this->method}\">";

        foreach ($this->content as $element) {
            $html .= "{$element}";
        }

        $html .= '</form>';
        return $html;
    }
}
