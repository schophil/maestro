<?php

namespace Maestro\conductor;

use Maestro\logic\Mro_Logic;

/**
 * This implements a simple action.
 * A simple action is composed of a logic instance and a page instance.
 */
class Mro_SimpleAction extends Mro_BasicAction
{
    private string $name;
    private Mro_Logic $logic;
    private string $pageId;

    /**
     * Default constructor.
     * @param string $name Name of the action.
     * @param Mro_Logic $logic Business logic.
     * @param string $page Name of the target page.
     */
    function __construct(string $name, Mro_Logic $logic, string $pageId)
    {
        $this->name = $name;
        $this->logic = $logic;
        $this->pageId = $pageId;
    }

    /**
     * Returns the logic to execute for this action.
     * @return Mro_Logic
     */
    function getLogic(Mro_Context $context): Mro_Logic
    {
        return $this->logic;
    }

    /**
     * Returns the page id to show for this action.
     * @return string
     */
    function getPageId($context): string
    {
        return $this->pageId;
    }

    /**
     * Returns the name of this action.
     * @return string
     */
    function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the page id.
     */
    function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }
}
