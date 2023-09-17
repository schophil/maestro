<?php

namespace Maestro\conductor;

/**
 * Default class to encapsulate uer information in the context.
 */
class Mro_UserInfo
{

    private string $name;
    private string $password;
    private string $role;

    /**
     * The default constructor.
     */
    function __construct(string $name, string $password, string $role)
    {
        $this->name = $name;
        $this->role = $role;
        $this->password = $password;
    }

    /**
     * Returns the current user name.
     * @return string userName
     */
    function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the role of this user.
     * @return string userRole
     */
    function getRole(): string
    {
        return $this->role;
    }

    /**
     * Returns the password of the user if any.
     * @return string password
     */
    function getPassword(): string
    {
        return $this->password;
    }
}
