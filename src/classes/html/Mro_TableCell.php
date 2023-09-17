<?php

namespace Maestro\html;

/**
 * A table cell.
 */
class Mro_TableCell extends Mro_HtmlElement
{

    private $content;
    private $colspan;
    private $isHeader;

    function __construct($element = null, $isHeader = false)
    {
        parent::__construct();
        $this->isHeader = $isHeader;
        if (!is_null($element)) {
            $this->content[] = $element;
        }
    }

    /**
     * Sets the colspan of this cell.
     * @param integer $number The number of cells to span.
     */
    function setColspan($number)
    {
        $this->colspan = $number;
    }

    /**
     * Adds an element to this cell.
     */
    function add($element)
    {
        $this->content[] = $element;
    }

    /**
     * Creates the HTML.
     */
    function createHtml()
    {
        $tag = $this->isHeader ? 'th' : 'td';
        $html = "\n<{$tag}";

        // add the default attributes if any
        $html = $this->appendDefaultAttributes($html);
        // add colspan
        if (isset($this->colspan)) {
            $html .= " colspan=\"{$this->colspan}\"";
        }
        $html .= '>';

        // add content
        if (!is_null($this->content)) {
            foreach ($this->content as $i => $element) {
                $html .= "{$element}";
            }
        }

        return $html . "</{$tag}>";
    }
}
