<?php

namespace Maestro\conductor;

use Maestro\logic\Mro_Logic;

/**
 * Interface of a mro action.
 */
interface Mro_Action
{

    /**
     * Returns the name of this action.
     *
     * @return string
     */
    function getName(): string;

    /**
     * Returns the logic to execute for this action.
     *
     * @return Mro_Logic
     */
    function getLogic(Mro_Context $context): Mro_Logic;

    /**
     * Returns the page id to show for this action.
     *
     * @return string
     */
    function getPageId(Mro_Context $context): string;

    /**
     * Checks if access to this action is restricted.
     *
     * @return boolean
     */
    function isSecured(): bool;
}
