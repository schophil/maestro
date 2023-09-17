<?php

namespace Maestro\conductor;

/**
 * This implements a simple action.
 * A simple action is composed of a logic instance and a page instance.
 */
abstract class Mro_BasicAction implements Mro_Action
{

    private $secured = false;

    /**
     * Secure access to this action.
     */
    function secure()
    {
        $this->secured = true;
        return $this;
    }

    /**
     * Checks if it is secured.
     */
    function isSecured(): bool
    {
        return $this->secured;
    }
}
