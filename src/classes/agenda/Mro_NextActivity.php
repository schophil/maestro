<?php

namespace Maestro\agenda;

use Maestro\conductor\Mro_Context;
use Maestro\db\Mro_DaoManager;
use Maestro\logic\Mro_Logic;
use Maestro\util\Mro_DateTime;

/**
 * Business logic that reads the upcoming event.
 */
class Mro_NextActivity implements Mro_Logic
{

    private $daoManager;
    private $types;
    private $variable;

    /**
     * Constructor.
     *
     * @param array $types All the types of event to search
     * @param string $variable The name of the context variable to hold the list of events
     */
    function __construct($types, $variable = 'activity')
    {
        $this->types = $types;
        $this->variable = $variable;
    }

    function needsPersistency(): bool
    {
        return true;
    }

    function setPersistency(Mro_DaoManager $daoManager): void
    {
        $this->daoManager = $daoManager;
    }

    function needsTransaction(): bool
    {
        return false;
    }

    function execute(Mro_Context $context): void
    {
        $values = array();
        $values['types'] = $this->types;
        $values['date'] = new Mro_DateTime();
        $daoList = $this->daoManager->search('event', 'next.activity', $values);

        if (sizeof($daoList) > 0) {
            $context->setPara($this->variable, $daoList[0]);
        }
    }
}
