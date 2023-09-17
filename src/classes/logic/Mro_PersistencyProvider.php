<?php

namespace Maestro\logic;

use Maestro\db\Mro_DaoManager;

/**
 * Class responsible to give access to the dao manager(s) for the logic.
 */
interface Mro_PersistencyProvider
{

    /**
     * Opens a dao manager.
     *
     * @return Mro_DaoManager
     */
    function open(): Mro_DaoManager;

    /**
     * Closes the given dao manager.
     *
     * @param Mro_DaoManager $daoManager The dao manager to close.
     */
    function close(Mro_DaoManager $daoManager): void;
}
