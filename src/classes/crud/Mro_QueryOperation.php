<?php

namespace Maestro\crud;

use Maestro\conductor\Mro_Context;
use Maestro\html\Mro_Div;
use Maestro\html\Mro_SubmitField;
use Maestro\html\Mro_Table;
use Maestro\html\Mro_TableCell;
use Monolog\Registry;

/**
 * A list operation lists the elements of a DAO.
 */
class Mro_QueryOperation extends Mro_CrudOperation
{

    private $log;

    function __construct($crudFactory)
    {
        parent::__construct($crudFactory);
        $this->log = Registry::getInstance('crud');
    }

    function needsTransaction(): bool
    {
        return false;
    }

    function execute(Mro_Context $context): void
    {
        $type = $this->getCrudType($context);
        $daoInfo = $this->getDaoInfo($type);

        // construct a table to list the searcheable fields
        $master = new Mro_Div();
        $master->addClass('mroCrudQuery');

        // get the crud factory
        $crudFactory = $this->getCrudFactory();

        // the query form
        $crudForm = $crudFactory->createForm('POST');
        $crudForm->addCrudOperationField('doquery');
        $crudForm->addCrudTypeField($type);
        // the table containing the fields
        $table = new Mro_Table();
        $table->addClass('mroCrudData');

        foreach ($daoInfo->getFields() as $fieldInfo) {
            if ($fieldInfo->canQuery) {
                $fieldName = $fieldInfo->name;

                if ($fieldInfo->queryCount > 1) {
                    for ($i = 1; $i <= $fieldInfo->queryCount; $i++) {
                        $this->addCrudField($fieldInfo, $fieldName . $i, $table);
                    }
                } else {
                    $this->addCrudField($fieldInfo, $fieldName, $table);
                }
            }
        }

        $crudForm->add($table);

        // and a submit button
        $submit = new Mro_Div();
        $submit->addClass('mroCrudAction');
        $submit->add(new Mro_SubmitField('query', 'qsubmit'));
        $crudForm->add($submit);

        $master->add($crudForm);

        // set on the contextxs
        $context->setPara('crudData', $master);
    }

    private function addCrudField($fieldInfo, $crudFieldName, $table)
    {
        $crudFactory = $this->getCrudFactory();
        $field = $crudFactory->createCrudFieldWithName($fieldInfo, $crudFieldName);
        // fields of a query are always editable
        $field->setEditable(true);
        $field->enable();

        $label = $crudFactory->createLabel($fieldInfo->getType(), $fieldInfo->name);

        $row = array();
        $row[] = new Mro_TableCell($label);
        $row[0]->addClass('mroLabel');
        $row[] = new Mro_TableCell($field);
        $table->addRow($row);
    }
}
