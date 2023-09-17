<?php

namespace Maestro\security;

use Maestro\conductor\Mro_Action;
use Maestro\conductor\Mro_ActionProcessor;
use Maestro\conductor\Mro_Context;
use Maestro\conductor\Mro_UserInfo;
use Maestro\html\Mro_Header;
use Monolog\Registry;

/**
 * A simple security handler.
 */
class Mro_SimpleSecurityHandler implements Mro_ActionProcessor
{

    private $users;
    private $realm;
    private $next;
    private $log;

    function __construct(array $users, string $realm, Mro_ActionProcessor $next = null)
    {
        $this->users = $users;
        $this->realm = $realm;
        $this->next = $next;
        $this->log = Registry::getInstance('security');
    }

    /**
     * Handle a given action.
     * @param Mro_Action $action The name of the action to handle.
     * @param Mro_Context $context The context in which to execute the logic.
     */
    public function handle(Mro_Action $action, Mro_Context $context): ?string
    {
        if ($action->isSecured()) {
            $this->log->debug("checking security for action {$action->getName()}");

            // check security
            $userInfo = $context->getUserInfo();
            if (!isset($userInfo)) {
                // try retrieve user
                $userInfo = $this->retrieveUserInfo();
            }

            // if user still not set send header
            if (!isset($userInfo) or !$this->isUserValid($userInfo, $this->users)) {
                $this->setHeader($context, $this->realm, $action->getName());
                return null;
            } else {
                $context->setUserInfo($userInfo);
                if (isset($this->next)) {
                    return $this->next->handle($action, $context);
                }
                return null;
            }
        } else {
            if (isset($this->next)) {
                return $this->next->handle($action, $context);
            }
            return null;
        }
    }

    private function isUserValid($userInfo, $users)
    {
        if (!isset($users[$userInfo->getName()])) {
            return false;
        }

        $password = $userInfo->getPassword();
        return $password == $users[$userInfo->getName()];
    }

    private function retrieveUserInfo()
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            return null;
        }

        $user = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        $this->log->debug("found user {$user}");
        return new Mro_UserInfo($user, $password, 'admin');
    }

    private function setHeader($context, $realm, $actionName)
    {
        $tmpRealm = $realm . '.' . $actionName . '.' . date('Y-m-d');
        $header = new Mro_Header(true);
        $header->add("WWW-Authenticate: Basic realm=\"{$tmpRealm}\"");
        $header->add("HTTP/1.0 401 Unauthorized");
        $context->setHeader($header);
    }
}
