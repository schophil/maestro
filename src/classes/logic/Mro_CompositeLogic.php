<?php

namespace Maestro\logic;

use Maestro\conductor\Mro_Context;
use Maestro\db\Mro_DaoManager;

/**
 *
 */
class Mro_CompositeLogic implements Mro_Logic
{

    private $logicObjects;

    private Mro_DaoManager $daoManager;

    function __construct(array $logicObjects = null)
    {
        if (is_null($logicObjects)) {
            $this->logicObjects = $logicObjects;
        } else {
            $this->logicObjects = array();
        }
    }

    function add(Mro_Logic $logic)
    {
        if (is_array($logic)) {
            // add all
            $this->logicObjects[] = array_merge($this->logicObjects, $logic);
        } else {
            // just add one
            $this->logicObjects[] = $logic;
        }
    }

    function needsPersistency(): bool
    {
        if (isset($this->logicObjects)) {
            foreach ($this->logicObjects as $logic) {
                if ($logic->needsPersistency()) {
                    return true;
                }
            }
        }
        return false;
    }

    function setPersistency(Mro_DaoManager $daoManager): void
    {
        if (isset($this->logicObjects)) {
            foreach ($this->logicObjects as $logic) {
                if ($logic->needsPersistency()) {
                    $logic->setPersistency($daoManager);
                }
            }
        }
        $this->daoManager = $daoManager;
    }

    function needsTransaction(): bool
    {
        foreach ($this->logicObjects as $logic) {
            if ($logic->needsTransaction()) {
                return true;
            }
        }
        return false;
    }

    function execute(Mro_Context $context): void
    {
        if (isset($this->logicObjects)) {
            foreach ($this->logicObjects as $logic) {
                $logic->execute($context);
            }
        }
    }
}
