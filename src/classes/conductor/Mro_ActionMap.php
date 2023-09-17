<?php

namespace Maestro\conductor;

/**
 * Action provider implemented as map.
 */
class Mro_ActionMap implements Mro_ActionProvider
{

    // the actions array
    private $actions;

    /**
     * The default constructor.
     */
    function __construct()
    {
        $this->actions = array();
    }

    /**
     * Adds an action to the action map. The key will be the name of the action.
     * @param Mro_Action $action An action.
     */
    function put(Mro_Action $action): void
    {
        $actionName = $action->getName();
        $this->actions[$actionName] = $action;
    }

    /**
     * Returns an action for a given action name.
     * @param string $actionIdentifier The name of an action.
     * @return Mro_Action
     */
    function get(string $actionIdentifier): ?Mro_Action
    {
        return $this->actions[$actionIdentifier];
    }

    /**
     * Returns the configured default action.
     * @return Mro_Action
     */
    function getDefaultAction(): ?Mro_Action
    {
        return $this->get('default');
    }

    /**
     * Registers the default action.
     * @param Mro_Action $action An action.
     */
    function setDefaultAction(Mro_Action $action): void
    {
        $this->actions['default'] = $action;
    }
}
