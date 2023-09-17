<?php

namespace Maestro\agenda;

use Maestro\conductor\Mro_Context;
use Maestro\db\Mro_DaoManager;
use Maestro\logic\Mro_Logic;

/**
 * Business logic that reads all the available events.
 */
class Mro_ReadEvents implements Mro_Logic
{

    private $daoManager;
    private $types;
    private $variable;
    private $activity;

    /**
     * Constructor.
     * @param array $types All the types of event to search
     * @param string $variable The name of the context variable to hold the list of events
     */
    public function __construct($types, $activity = null, $variable = 'events')
    {
        $this->types = $types;
        $this->variable = $variable;
        $this->activity = $activity;
    }

    public function needsPersistency(): bool
    {
        return true;
    }

    public function setPersistency(Mro_DaoManager $daoManager): void
    {
        $this->daoManager = $daoManager;
    }

    public function needsTransaction(): bool
    {
        return false;
    }

    function execute(Mro_Context $context): void
    {
        $values = array();
        $values['types'] = $this->types;
        // only set activity if it is not null
        if (!is_null($this->activity)) {
            $values['activity'] = $this->activity;
        }
        $daoList = $this->daoManager->search('event', 'types', $values);

        $context->setPara($this->variable, $daoList);
    }
}
