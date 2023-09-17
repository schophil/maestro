<?php

namespace Maestro\crud;

use Maestro\db\Mro_DaoConfig;
use Maestro\db\Mro_DaoManager;
use Maestro\logic\Mro_Logic;
use Maestro\Mro_Exception;

/**
 *
 */
abstract class Mro_CrudOperation implements Mro_Logic
{

    private $daoManager;

    private $crudFactory;

    function __construct($crudFactory)
    {
        $this->crudFactory = $crudFactory;
    }

    function needsPersistency(): bool
    {
        return true;
    }

    function setPersistency($daoManager): void
    {
        $this->daoManager = $daoManager;
    }

    function getPersistency()
    {
        return $this->daoManager;
    }

    function getDaoConfig(): Mro_DaoConfig
    {
        return $this->daoManager->getDaoConfig();
    }

    function getDaoManager(): Mro_DaoManager
    {
        return $this->daoManager;
    }

    function getDaoInfo($type)
    {
        $daoConfig = $this->getDaoConfig();
        $daoInfo = $daoConfig->getDao($type);
        if (is_null($daoInfo)) {
            throw new Mro_Exception("unexisting dao type {$type}");
        }
        return $daoInfo;
    }

    function getCrudType($context)
    {
        return $context->getArg('daotype');
    }

    function getDaoId($context)
    {
        return $context->getArg('daoid');
    }

    function getCrudFactory()
    {
        return $this->crudFactory;
    }
}
