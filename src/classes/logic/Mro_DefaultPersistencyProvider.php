<?php

namespace Maestro\logic;

use Maestro\db\Mro_ConnectionData;
use Maestro\db\Mro_DaoConfig;
use Maestro\db\Mro_DaoManager;
use Maestro\db\Mro_MysqlSession;

/**
 * Class responsible to give access to the dao manager(s) for the logic.
 */
class Mro_DefaultPersistencyProvider implements Mro_PersistencyProvider
{

    private Mro_DaoConfig $daoConfig;

    private Mro_ConnectionData $connectionData;

    /**
     * Default constructor.
     *
     * @param object $daoConfig The dao configuration.
     * @param object $connectionData The connection data.
     */
    function __construct(Mro_DaoConfig $daoConfig, Mro_ConnectionData $connectionData)
    {
        $this->daoConfig = $daoConfig;
        $this->connectionData = $connectionData;
    }

    /**
     * Opens a dao manager.
     *
     * @return Mro_DaoManager
     */
    function open(): Mro_DaoManager
    {
        // open the session
        $session = $this->openSession();

        // create the dao manager
        $daoManager = new Mro_DaoManager($this->daoConfig, $session, 'Y-M-D');
        return $daoManager;
    }

    /**
     * Closes the given dao manager.
     *
     * @param Mro_DaoManager $daoManager The dao manager to close.
     */
    function close(Mro_DaoManager $daoManager): void
    {
    }

    private function openSession()
    {
        $product = $this->connectionData->getProduct();

        $session = null;
        if ($product == 'mysql') {
            $session = new Mro_MysqlSession();
            $session->connectWith($this->connectionData);
        }

        return $session;
    }
}
