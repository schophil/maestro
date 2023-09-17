<?php

namespace Maestro\html;

/**
 * A list operation lists the elements of a DAO.
 */
class Mro_Div extends Mro_HtmlElement {

    private $content;

    /**
     * Constructor.
     * @param string $daoType The type name of the DAO instances add to this table.
     * @param int $numberOfColumns The number of columns this table will have.
     */
    function __construct() {
        parent::__construct();
        $this->content = array();
    }

    /**
     * Adds a row.
     */
    function add($element) {
        $this->content[] = $element;
    }

    /**
     * Creates the HTML/
     */
    function createHtml() {
        $html = "\n<div";

        // add the default attributes if any
        $html = $this->appendDefaultAttributes($html);
        $html .= '>';

        // add content
        foreach($this->content as $element) {
            $html .= $element->createHtml();
        }

        return $html . "\n</div>";
    }
}
