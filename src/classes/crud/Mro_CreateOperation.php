<?php

namespace Maestro\crud;

use Maestro\conductor\Mro_Context;
use Maestro\html\Mro_Header;

/**
 * A list operation lists the elements of a DAO.
 */
class Mro_CreateOperation extends Mro_CrudOperation
{

    function needsTransaction(): bool
    {
        return true;
    }

    function execute(Mro_Context $context): void
    {
        $type = $this->getCrudType($context);

        // get the daos
        $daoManager = $this->getDaoManager();
        $id = $daoManager->create($type);

        $crudActionName = $this->getCrudFactory()->getCrudActionName();
        $uri = new Mro_CrudUri($crudActionName, 'edit', $type, $id);

        $header = new Mro_Header(true);
        $header->addLocation($uri);

        $context->setHeader($header);
    }
}
