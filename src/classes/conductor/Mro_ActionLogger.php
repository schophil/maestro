<?php

namespace Maestro\conductor;

use Monolog\Registry;
use Psr\Log\LoggerInterface;

/**
 * The action logger is a action processor that logs any incmoding action.
 * @package conductor
 */
class Mro_ActionLogger implements Mro_ActionProcessor
{
    private ?Mro_ActionProcessor $next;
    private LoggerInterface $log;

    /**
     * Default constructor.
     * @param array $roleBase Array containing a action - role mapping
     */
    public function __construct(?Mro_ActionProcessor $next = null)
    {
        $this->next = $next;
        $this->log = Registry::getInstance('conductor');
    }

    /**
     * Handle a given action.
     * @param Mro_Action $action The name of the action to handle.
     * @param Mro_Context $context The context in which to execute the logic.
     */
    public function handle(Mro_Action $action, Mro_Context $context): ?string
    {
        $actionName = $action->getName();
        $this->log->info('=> in action:', ['actionName' => $actionName]);
        if (isset($this->next)) {
            return $this->next->handle($action, $context);
        } else {
            return null;
        }
    }
}
