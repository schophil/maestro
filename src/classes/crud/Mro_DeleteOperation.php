<?php

namespace Maestro\crud;

use Maestro\html\Mro_Header;

/**
 * A list operation lists the elements of a DAO.
 */
class Mro_DeleteOperation extends Mro_CrudOperation
{

    function needsTransaction(): bool
    {
        return true;
    }

    function execute($context): void
    {
        $type = $this->getCrudType($context);

        // get the dao
        $id = $this->getDaoId($context);
        $daoManager = $this->getDaoManager();
        $dao = $daoManager->load($type, $id);
        $daoManager->delete($dao);

        $crudFactory = $this->getCrudFactory();

        $uri = $crudFactory->createUri('list', $type);
        $uri->addPara('page', 1);
        $uri->addPara('pagesz', 25);

        $header = new Mro_Header(true);
        $header->add("Location: {$uri}");

        $context->setHeader($header);
    }
}
