<?php

namespace Maestro\crud;

/**
 * A list operation lists the elements of a DAO.
 * implements Mro_CrudData
 */
class Mro_CrudBlock
{

    private $content;

    /**
     * Constructor.
     * @param string $daoType The type name of the DAO instances add to this table.
     * @param int $numberOfColumns The number of columns this table will have.
     */
    public function __construct()
    {
        $this->content = array();
    }

    /**
     * Adds a row.
     */
    public function add($element)
    {
        $this->content[] = $element;
    }

    /**
     * Creates the HTML/
     */
    public function createHtml()
    {
        $html = '';
        foreach ($this->content as $element) {
            $html .= $element->createHtml();
        }
        return $html;
    }
}
