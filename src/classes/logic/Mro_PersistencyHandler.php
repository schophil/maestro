<?php

namespace Maestro\logic;

use Maestro\conductor\Mro_Context;
use Maestro\Mro_Exception;
use Monolog\Registry;
use Psr\Log\LoggerInterface;

/**
 * Executor handle that sets persistency on the logic class to be handled and
 * eventually executed.
 */
class Mro_PersistencyHandler implements Mro_LogicProcessor
{

    private $next;

    private $persistencyProvider;

    private $daoManager;

    private LoggerInterface $log;

    /**
     * Default constructor.
     * @param Mro_PersistencyProvider $persistencyProvider The persistency provider to use.
     * @param Mro_LogicProcessor $next The next handler to use.
     */
    function __construct(Mro_PersistencyProvider $persistencyProvider, Mro_LogicProcessor $next)
    {
        $this->next = $next;
        $this->persistencyProvider = $persistencyProvider;
        $this->log = Registry::getInstance('logic');
    }

    /**
     * Register the persistency and forward to next handler.
     * @param Mro_Logic $logic The logic to execute.
     * @param Mro_Context $context The context to use.
     */
    function handle(Mro_Logic $logic, Mro_Context $context): void
    {
        if ($logic->needsPersistency()) {
            $this->log->debug('setting persistency');
            // execute next
            try {
                $daoManager = $this->getDaoManager();
                $logic->setPersistency($daoManager);
                if (!is_null($this->next)) {
                    $this->next->handle($logic, $context);
                }
            } catch (Mro_Exception $e) {
                throw $e;
            }
        } elseif (!is_null($this->next)) {
            $this->next->handle($logic, $context);
        }
    }

    /**
     * Close the dao manager.
     */
    function cleanUp(): void
    {
        if (!is_null($this->daoManager)) {
            $this->log->debug('close dao manager');
            $this->persistencyProvider->close($this->daoManager);
        }
        if (!is_null($this->next)) {
            $this->next->cleanUp();
        }
    }

    private function getDaoManager()
    {
        if (is_null($this->daoManager)) {
            $this->log->debug('initialize dao manager');
            $daoManager = $this->persistencyProvider->open();
            $this->daoManager = $daoManager;
        }
        return $this->daoManager;
    }
}
