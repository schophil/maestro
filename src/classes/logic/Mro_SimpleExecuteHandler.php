<?php

namespace Maestro\logic;

use Maestro\conductor\Mro_Context;
use Monolog\Registry;
use Psr\Log\LoggerInterface;

/**
 * Logic executor that simply executes the given logic.
 */
class Mro_SimpleExecuteHandler implements Mro_LogicProcessor
{

    private $next; // next handler
    private LoggerInterface $log; // the log

    /**
     * Default constructor.
     * @param Mro_LogicProcessor The next handler to execute.
     */
    function __construct(?Mro_LogicProcessor $next)
    {
        $this->next = $next;
        $this->log = Registry::getInstance('logic');
    }

    /**
     * Executes the given and logic and passes the logic to the next handler.
     * @param Mro_Logic $logic The logic to handle.
     * @param Mro_Context $context The context of the execution.
     */
    function handle(Mro_Logic $logic, Mro_Context $context): void
    {
        $logic->execute($context);
        if (!is_null($this->next)) {
            $this->next->handle($logic, $context);
        }
    }

    /**
     * Clean up after handling.
     */
    function cleanUp(): void
    {
        if (!is_null($this->next)) {
            $this->next->cleanUp();
        }
    }
}
