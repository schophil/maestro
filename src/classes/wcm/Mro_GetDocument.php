<?php

namespace Maestro\wcm;

use Maestro\conductor\Mro_Context;
use Maestro\db\Mro_DaoManager;
use Maestro\logic\Mro_Logic;

/**
 *
 */
class Mro_GetDocument implements Mro_Logic
{

    private Mro_DaoManager $daoManager;
    private string $name;

    function __construct(string $name)
    {
        $this->name = $name;
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
        if (!is_null($this->name)) {
            $values = array();
            $values['name'] = $this->name;

            // get from db
            $list = $this->daoManager->search('document', 'byname', $values);

            // get first one
            if (sizeof($list) > 0) {
                $context->setPara('document', $list[0]);
            }
        }
    }
}
