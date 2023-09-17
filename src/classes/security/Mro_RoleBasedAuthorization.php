<?php

namespace Maestro\security;

use Maestro\conductor\Mro_Action;
use Maestro\conductor\Mro_ActionProcessor;
use Maestro\conductor\Mro_Context;

/**
 * The role based authorization will check if a user (if logged in) is allowed to execute the requested action.
 * If the user is not allowed to he will be redirected to a configurable page. If an action is public, the user
 * information will not be checked. An action will be considered public if it is not available in the role base
 * confguration.
 */
class Mro_RoleBasedAuthorization implements Mro_ActionProcessor
{

    private $roleBase;

    private $failedPageName;

    private $next;

    /**
     * Default constructor.
     * @param array $roleBase Array containing a action - role mapping
     * @param string $failedPageName The name of the page to send if an error occurs
     * @param Mro_ActionProcessor $next The next action processor
     */
    public function __construct($roleBase, $failedPageName, $next)
    {
        $this->roleBase = $roleBase;
        $this->failedPageName = $failedPageName;
        $this->next = $next;
    }

    /**
     * Handle a given action.
     * @param Mro_Action $action The name of the action to handle.
     * @param Mro_Context $context The context in which to execute the logic.
     */
    public function handle(Mro_Action $action, Mro_Context $context)
    {
        // get user info
        $userInfo = $context->getUserInfo();
        $userRole = $userInfo->getRole();

        // get the roles for the action
        $actionRoles = $this->roleBase[$action->getName()];

        // if the action roles are not null we have to check the authorization
        $redirect = null;
        if (!is_null($actionRoles)) {
            if (!in_array($userRole, $actionRoles)) {
                $redirect = $this->failedPageName;
            }
        }

        // if everything was ok here, execute the following
        if (is_null($redirect)) {
            return $this->next->handle($action, $context);
        } else {
            return $redirect;
        }
    }
}
