<?php

namespace Maestro\conductor;

use Maestro\html\Mro_Header;
use Monolog\Registry;

/**
 * The context is just a wrapper around the HTTP parameters (get or post).
 * This facilitate access to the values.
 */
class Mro_Context
{

    private $log;
    private $args;
    private $paras;
    private $userInfo;

    /**
     * The default constructor.
     * @param array $args The HTTP arguments.
     */
    function __construct(&$args)
    {
        $this->args = $args;
        $this->paras = array();
        $this->log = Registry::getInstance('conductor');
    }

    /**
     * Returns the value of an argument.
     *
     * @param string $name The name of the argument.
     * @return mixed
     */
    function getArg($name, $default = null): mixed
    {
        if (isset($this->args[$name])) {
            return $this->args[$name];
        } else {
            return $default;
        }
    }

    /**
     * Removes all the parameters.
     */
    function clearParas(): void
    {
        $this->paras = array();
    }

    /**
     * Returns the value of a parameter.
     *
     * @param string $name The name of the parameter.
     * @return mixed
     */
    function getPara(string $name): mixed
    {
        return isset($this->paras[$name]) ? $this->paras[$name] : null;
    }

    /**
     * Sets the value of a parameter.
     * @param string $name The name of the parameter.
     * @param mixed $value The value of the parameter.
     */
    function setPara(string $name, mixed $value): void
    {
        $this->log->debug("setting parameter {$name}");
        $this->paras[$name] = $value;
    }

    /**
     * Returns the current user info.
     * @return Mro_UserInfo
     */
    function getUserInfo(): ?Mro_UserInfo
    {
        return $this->userInfo;
    }

    /**
     * Registers the current user info.
     * @param Mro_UserInfo $userInfo The user info instance.
     */
    function setUserInfo(Mro_UserInfo $userInfo): void
    {
        $this->userInfo = $userInfo;
    }

    /**
     * Adds an error to the context.
     * @param string $msg The error message.
     */
    function addError(string $msg): void
    {
        if (!isset($this->paras['error'])) {
            $this->paras['error'] = array();
        }
        $errors =& $this->paras['error'];
        $errors[] = $msg;
    }

    /**
     * Returns the list of registered errors.
     * @return array
     */
    function getErrors(): array
    {
        if (!isset($this->paras['error'])) {
            $this->paras['error'] = array();
        }
        return $this->paras['error'];
    }

    /**
     * Adds an error to the context.
     * @param string $msg The error message.
     */
    function addMessage(string $msg): void
    {
        if (!isset($this->paras['message'])) {
            $this->paras['message'] = array();
        }
        $messages =& $this->paras['message'];
        $messages[] = $msg;
    }

    /**
     * Returns the list of registered errors.
     * @return array
     */
    function getMessages(): array
    {
        if (!isset($this->paras['message'])) {
            $this->paras['message'] = array();
        }
        return $this->paras['message'];
    }

    /**
     * Sets the header information to send.
     * @param Mro_Header $header
     */
    function setHeader(Mro_Header $header): void
    {
        $this->setPara('header', $header);
    }

    /**
     * Returns the header to be sent.
     * @return Mro_Header
     */
    function getHeader(): ?Mro_Header
    {
        return $this->getPara('header');
    }
}
