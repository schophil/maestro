<?php
// vim: set sw=4 ts=4:

/**
 * @author Philippe Schottey <pschotte@mac.com>
 * @package db
 */

namespace Maestro\db;

use Monolog\Registry;

/**
 * Wraps a Mro_Dao instance for better access.
 */
class Mro_DaoWrapper
{

    private Mro_Dao $dao;
    private $log;

    /**
     * Default constructor.
     * @param Mro_Dao $dao A dao instance.
     */
    function __construct(Mro_Dao $dao)
    {
        $this->log = Registry::getInstance('db');
        $id = $dao->getId();
        $this->log->debug("wrap dao $id");
        $this->dao = $dao;
    }

    /**
     * Returns the wrapped DAO.
     * @return Mro_Dao
     */
    function getDao()
    {
        return $this->dao;
    }

    /**
     * Returns the id of the wrapped DAO.
     * @return mixed
     */
    function getId()
    {
        return $this->dao->getId();
    }

    /**
     * Returns the update counter of the wrapped DOA.
     * @return integer
     */
    function getUc()
    {
        return $this->dao->getUc();
    }

    /**
     * Returns the value of a field
     * @return mixed
     */
    function get($field)
    {
        $dao = $this->dao;
        $id = $dao->getId();
        $value = $dao->getValue($field);
        $this->log->debug("dao wrapper get field $field from $id: $value");
        return $value;
    }
}
