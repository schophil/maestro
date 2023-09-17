<?php

namespace Maestro\conductor;


/**
 * Interface of action providers.
 * Action providers are used by the conductor to retrieve actions by name.
 */
interface Mro_ActionProvider {

    /**
     * Returns an action for a given action name.
     * @param string $actionIdentifier An action id, i.e. its name.
     * @return Mro_Action
     */
    function get(string $actionIdentifier): ?Mro_Action;

    /**
     * Returns the configured default action.
     * @return Mro_Action
     */
    function getDefaultAction(): ?Mro_Action;
}
