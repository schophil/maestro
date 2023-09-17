<?php

namespace Maestro\conductor;

/**
 * Action processer will perform some actions regarding an action.
 * Action processor may for example add a layer of security or logging.
 * It stands before the execution of action logic. An action (pre) processor may
 * issue a redirect to another page or generate an error.
 */
interface Mro_ActionProcessor {

    /**
     * Handle a given action.
     * @param Mro_Action $action The name of the action to handle.
     * @param Mro_Context $context The context in which to execute the logic.
     */
    function handle(Mro_Action $action, Mro_Context $context): ?string;
}
