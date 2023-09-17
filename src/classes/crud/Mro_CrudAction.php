<?php

namespace Maestro\crud;

use Maestro\conductor\Mro_BasicAction;
use Maestro\conductor\Mro_Context;
use Maestro\logic\Mro_Logic;
use Maestro\Mro_Exception;

/**
 * The action class for a CRUD application. This action is responsible for
 * instantiating the corresponding logic class based on the demanded CRUD operation.
 */
class Mro_CrudAction extends Mro_BasicAction
{

    private $crudPageId;
    private $name;

    /**
     * Creates a new CRUD action
     *
     * @param string $crudName The name of the curd action (default: crud)
     * @param string $crudPageId The id of the page to show CRUD data (default: crud).
     */
    function __construct($crudName = 'crud', $crudPageId = 'crud')
    {
        $this->crudPageId = $crudPageId;
        $this->name = $crudName;
    }

    /**
     * Returns the name of this action.
     */
    function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the logic to execute for this action.
     * @return Mro_Logic
     */
    function getLogic(Mro_Context $context): Mro_Logic
    {
        $crudOperation = $context->getArg('operation');
        if ($crudOperation === 'overview') {
            // list the data from a DAO
            return new Mro_OverviewOperation(new Mro_CrudFactory($this->getName()));
        } elseif ($crudOperation === 'list') {
            // list the data from a DAO
            return new Mro_ListOperation(new Mro_CrudFactory($this->getName()));
        } elseif ($crudOperation === 'edit') {
            // edit an existing DAO
            return new Mro_EditOperation(new Mro_CrudFactory($this->getName()));
        } elseif ($crudOperation === 'create') {
            // create a new DAO
            return new Mro_CreateOperation(new Mro_CrudFactory($this->getName()));
        } elseif ($crudOperation === 'update') {
            // update an existing DAO
            return new Mro_UpdateOperation(new Mro_CrudFactory($this->getName()));
        } elseif ($crudOperation === 'delete') {
            // delete an existing DAO
            return new Mro_DeleteOperation(new Mro_CrudFactory($this->getName()));
        } elseif ($crudOperation === 'query') {
            return new Mro_QueryOperation(new Mro_CrudFactory($this->getName()));
        } elseif ($crudOperation === 'doquery') {
            return new Mro_DoQueryOperation(new Mro_CrudFactory($this->getName()));
        } else {
            throw new Mro_Exception("unknown crud operation {$crudOperation}");
        }
    }

    /**
     * Returns the page id to show for this action.
     * @return string
     */
    function getPageId(Mro_Context $context): string
    {
        return $this->crudPageId;
    }

    function setPageId($pageId)
    {
        $this->crudPageId = $pageId;
    }
}
