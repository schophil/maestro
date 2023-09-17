<?php

namespace Maestro\conductor;

/**
 * A simple implementation of an action provider.
 * The actions are instantiated and collected in an array. This is not
 * necessarely the best implementation. However if the PHP apache module does
 * caching this might actually be a good implementation. Still it is better to
 * use the Mro_ActionMap implementation.
 */
class Mro_DefaultActionProvider implements Mro_ActionProvider
{

    private $actions;

    /**
     * The constructor.
     * @param array $actions An array of Mro_Action instances.
     */
    public function __construct(&$actions)
    {
        $this->actions = $actions;
    }

    /**
     * Returns an action for a given name.
     * @param string $actionIdentifier The action name.
     * @return Mro_Action
     */
    public function get($actionIdentifier): ?Mro_Action
    {
        return $this->actions[$actionIdentifier];
    }

    /**
     * Returns the default action.
     * @return Mro_Action
     */
    public function getDefaultAction(): ?Mro_Action
    {
        return $this->get('default');
    }
}
