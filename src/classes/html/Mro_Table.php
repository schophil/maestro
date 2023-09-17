<?php

namespace Maestro\html;

/**
 * A list operation lists the elements of a DAO.
 */
class Mro_Table extends Mro_HtmlElement
{

    private $rows;
    private $header;
    private $footer;

    /**
     * Constructor.
     * @param string $daoType The type name of the DAO instances add to this table.
     * @param int $numberOfColumns The number of columns this table will have.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Adds a row.
     * @param array $row An array of Mro_TableCell instances.
     */
    function addRow($row)
    {
        $this->rows[] = $row;
    }

    /**
     * Registers the table header.
     * @param array $header An array of Mro_TableCell instances.
     */
    function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * Adds a header row.
     */
    function addHeaderRow($row)
    {
        $this->header[] = $row;
    }

    /**
     * Adds a footer row.
     *
     * @param object $row A row.
     */
    function addFooterRow($row)
    {
        $this->footer[] = $row;
    }

    /**
     * Creates the HTML/
     */
    function createHtml()
    {
        $html = "\n<table";

        $html = $this->appendDefaultAttributes($html);
        $html .= '>';

        // add header
        if (isset($this->header)) {
            $html .= "\n<thead>";
            foreach ($this->header as $row) {
                $html .= "\n<tr>";
                foreach ($row as $column) {
                    $html .= "{$column}";
                }
                $html .= "\n</tr>";
            }
            $html .= "\n</thead>";
        }

        // add the body
        $html .= "\n<tbody>";

        if (isset($this->rows)) {
            foreach ($this->rows as $i => $row) {
                if (($i + 1) % 2 == 0) {
                    $html .= "\n<tr class=\"even\">";
                } else {
                    $html .= "\n<tr>";
                }

                foreach ($row as $column) {
                    $html .= "{$column}";
                }
                $html .= "\n</tr>";
            }
        }

        $html .= "\n</tbody>";

        if (isset($this->footer)) {
            $html .= "\n<tfoot>";
            foreach ($this->footer as $row) {
                $html .= "\n<tr>";
                foreach ($row as $column) {
                    $html .= "{$column}";
                }
                $html .= "\n</tr>";
            }
            $html .= "\n</tfoot>";
        }

        $html .= "\n</table>";
        return $html;
    }
}
