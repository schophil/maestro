<?php

namespace Maestro\html;

/**
 * A list operation lists the elements of a DAO.
 */
class Mro_MaestroUri
{

    private $action;
    private $paras;

    /**
     * Constructor.
     * @param string $daoType The type name of the DAO instances add to this table.
     * @param int $numberOfColumns The number of columns this table will have.
     */
    function __construct($action = null)
    {
        $this->action = $action;
        $this->paras = array();
    }

    /**
     * Sets the action.
     */
    function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Adds a parameter.
     */
    function addPara($name, $value)
    {
        $this->paras[$name] = $value;
    }

    /**
     * Creates the HTML/
     */
    function generateUri()
    {
        $uri = 'index.php';
        if (!is_null($this->paras) or sizeof($this->paras) > 0) {
            $uri .= '?';
        }

        $total = 0;

        if (!is_null($this->action)) {
            $total++;
            $uri .= "action={$this->action}";
        }

        if (sizeof($this->paras) > 0) {
            foreach ($this->paras as $name => $value) {
                if ($total > 0) {
                    $uri .= '&';
                }
                $uri .= "{$name}={$value}";
                $total++;
            }
        }

        return $uri;
    }

    /**
     * Creates URI as string.
     */
    function __toString()
    {
        return $this->generateUri();
    }
}
