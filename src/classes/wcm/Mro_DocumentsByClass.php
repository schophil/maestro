<?php

namespace Maestro\wcm;

use Maestro\conductor\Mro_Context;
use Maestro\db\Mro_DaoManager;
use Maestro\logic\Mro_Logic;

/**
 *
 */
class Mro_DocumentsByClass implements Mro_Logic
{

    private Mro_DaoManager $daoManager;
    private string $class;
    private string $variable;

    function __construct($class, $variable = 'documents')
    {
        $this->class = $class;
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
        // a class passed as argument overrides the init value
        $class = $context->getArg('class');
        if (is_null($class)) {
            $class = $this->class;
        }

        if (!is_null($class)) {
            $values = array();
            $values['classification'] = $class;

            // get from db
            $list = $this->daoManager->search('document', 'byclass', $values);

            // get first one
            if (sizeof($list) > 0) {
                $context->setPara($this->variable, $list);
            }
        }
    }
}
