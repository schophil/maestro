<?php

namespace Maestro\conductor;

use Maestro\crud\Mro_CrudAction;
use Maestro\logic\Mro_Logic;
use Maestro\wcm\Mro_WcmAction;

/**
 * Action provider implemented as map.
 */
class Mro_ActionConfig extends Mro_ActionMap
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Creates and adds a new simple action with logic. By defaut the page id
     * is the same as the action name.
     *
     * @param string $actionName The name of the action
     * @param string $actionLogic The logic to execute (optional; default null)
     */
    function addSimpleAction(string $actionName, Mro_Logic $actionLogic = null)
    {
        $action = new Mro_SimpleAction($actionName, $actionLogic, $actionName);
        $this->put($action);
        return $action;
    }

    /**
     * Creates and adds a new crud action. By default the page id is 'crud'.
     *
     * @param string $actionName The name of the crud action.
     */
    function addCrudAction($actionName)
    {
        $action = new Mro_CrudAction($actionName, $actionName);
        $this->put($action);
        return $action;
    }

    /**
     * Creates a wcm action.
     */
    function addWcmAction($logicMatrix, $defaultDocument)
    {
        $action = new Mro_WcmAction($logicMatrix, $defaultDocument);
        $this->put($action);
        return $action;
    }
}
