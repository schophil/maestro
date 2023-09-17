<?php
// vim: ts=4 sw=4:

/**
 * @author Philippe Schottey <phschotte@gmail.com>
 */

namespace Maestro\db;

/**
 * Wraps database connection information.
 */
class Mro_ConnectionData
{

    private $user = null;
    private $password = null;
    private $host = null;
    private $database = null;
    private $product;

    /**
     * Sets the database product.
     *
     * @param string $product The name of the product.
     */
    function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * Returns the database product (for example mysql).
     *
     * @return string
     */
    function getProduct()
    {
        return $this->product;
    }

    /**
     * Registers the user on the connection data.
     *
     * @param string $user
     */
    function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Returns the user.
     *
     * @return string
     */
    function getUser()
    {
        return $this->user;
    }

    /**
     * Registers the password.
     *
     * @param string $password
     */
    function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the password.
     *
     * @return string
     */
    function getPassword()
    {
        return $this->password;
    }

    /**
     * Registers the host.
     *
     * @param string $host
     */
    function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Returns the host name.
     *
     * @return string
     */
    function getHost()
    {
        return $this->host;
    }

    /**
     * Registers the name of the database
     *
     * @param string $database
     */
    function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * Returns the database.
     *
     * @return string
     */
    function getDatabase()
    {
        return $this->database;
    }
}
