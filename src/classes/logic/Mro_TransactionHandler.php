<?php

namespace Maestro\logic;

use Maestro\conductor\Mro_Context;
use Maestro\Mro_Exception;

/**
 * The persistency handler responsible for adding transactions to logic.
 */
class Mro_TransactionHandler implements Mro_LogicProcessor
{

    private Mro_LogicProcessor $next;

    /**
     * Default constructor.
     * @param Mro_LogicProcessor $next The next handler to execute.
     */
    function __construct(Mro_LogicProcessor $next)
    {
        $this->next = $next;
    }

    /**
     * Open a transaction and forward logic to next handler.
     * @param Mro_Logic $logic The logic to handle.
     * @param Mro_Context $context The context in which to handle the logic.
     */
    function handle(Mro_Logic $logic, Mro_Context $context): void
    {
        if ($logic->needsTransaction()) {
            $daoManager = $logic->getPersistency();
            $daoManager->startTransaction();
            try {
                if (!is_null($this->next)) {
                    $this->next->handle($logic, $context);
                }
            } catch (Mro_Exception $e) {
                $daoManager->rollbackTransaction();
                throw $e;
            }
            $daoManager->commitTransaction();
        } elseif (!is_null($this->next)) {
            $this->next->handle($logic, $context);
        }
    }

    /**
     * Clean up after execution.
     */
    function cleanUp(): void
    {
        if (!is_null($this->next)) {
            $this->next->cleanUp();
        }
    }
}
