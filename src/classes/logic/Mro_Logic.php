<?php

namespace Maestro\logic;

use Maestro\conductor\Mro_Context;
use Maestro\db\Mro_DaoManager;

/**
 * Interface to all logic implementations.
 */
interface Mro_Logic {

    /**
     * Indicates if the logic needs access to a database.
     * @return boolean
     */
    function needsPersistency(): bool;

    /**
     * Sets the persistency.
     * @param Mro_DaoManager $daoManager The dao manager to use for loading and writing data.
     */
    function setPersistency(Mro_DaoManager $daoManager): void;

    /**
     * Indicates if the logic needs a transaction.
     * @return boolean
     */
    function needsTransaction(): bool;

    /**
     * Executes the logic with the given context.
     * @param Mro_Context $context The context containing all needed parameters.
     */
    function execute(Mro_Context $context): void;
}
