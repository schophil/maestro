<?php

namespace Maestro\crud;

use Maestro\conductor\Mro_Context;
use Maestro\html\Mro_Header;
use Monolog\Registry;

/**
 * A list operation lists the elements of a DAO.
 */
class Mro_UpdateOperation extends Mro_CrudOperation
{

    private $log;

    function __construct($crudFactory)
    {
        parent::__construct($crudFactory);
        $this->log = Registry::getInstance('crud');
    }

    function needsTransaction(): bool
    {
        return true;
    }

    function execute(Mro_Context $context): void
    {
        $this->update($context);

        $pageSize = (int)$context->getArg('pagesz');
        $page = (int)$context->getArg('page');

        $value = $context->getArg("usubmit");
        $this->log->debug("submit button: {$value}");

        $crudFactory = $this->getCrudFactory();

        $type = $this->getCrudType($context);
        $uri = null;
        if ($value == 'save and close') {
            $uri = $crudFactory->createUri('list', $type);
        } else {
            // construct a uri to edit the same dao
            $uri = $crudFactory->createUri('edit', $type);
            $uri->addPara('daoid', $this->getDaoId($context));
        }

        if (isset($page)) {
            $uri->addPara('page', $page);
            $uri->addPara('pagesz', $pageSize);
        }

        $header = new Mro_Header(true);
        $header->add("Location: {$uri}");

        $context->setHeader($header);
    }

    private function update($context)
    {
        $type = $this->getCrudType($context);
        $daoInfo = $this->getDaoInfo($type);

        // get the dao
        $id = $this->getDaoId($context);
        $daoManager = $this->getDaoManager();
        $dao = $daoManager->load($type, $id);
        // get the raw dao if necessary
        $dao = $dao->getDao();

        $crudFactory = $this->getCrudFactory();

        // get the fields
        foreach ($daoInfo->getFields() as $fieldInfo) {
            $fieldName = $fieldInfo->name;
            $field = $crudFactory->createCrudField($fieldInfo);

            if ($fieldInfo->isDataField()) {
                $value = $field->readValue($context);
                $dao->setValue($fieldName, $value);
            }
        }

        // save
        $daoManager->save($dao);
    }
}
