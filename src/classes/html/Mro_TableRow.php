<?php

namespace Maestro\html;

/**
 * A table row.
 */
class Mro_TableRow extends Mro_HtmlElement
{

    private $cells;

    function __construct()
    {
        parent::__construct();
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
     * Adds a cell to this cell.
     * @param Mro_TableCell $cell
     */
    function add($cell)
    {
        $this->cells[] = $cell;
    }

    /**
     * Creates the HTML.
     */
    function createHtml()
    {
        $html = '<tr';

        // add the default attributes if any
        $html = $this->appendDefaultAttributes($html);
        $html .= '>';

        // add content
        if (isset($this->cells)) {
            foreach ($this->cells as $cell) {
                $html .= $cell->createHtml();
            }
        }

        return $html . '</tr>';
    }
}
