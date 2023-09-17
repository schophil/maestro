<?php

namespace Maestro\crud;

use Maestro\html\Mro_Div;
use Maestro\html\Mro_SubmitField;
use Maestro\html\Mro_Table;
use Maestro\html\Mro_TableCell;
use Monolog\Registry;

/**
 * A list operation lists the elements of a DAO.
 */
class Mro_DoQueryOperation extends Mro_CrudOperation
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

    function execute($context): void
    {
        $type = $this->getCrudType($context);
        $daoInfo = $this->getDaoInfo($type);

        $results = $this->query($type, $daoInfo, $context);

        // construct a table to list the searcheable fields
        $master = new Mro_Div();
        $master->addClass('mroCrudQuery');

        // get the crud factory
        $crudFactory = $this->getCrudFactory();

        $table = new Mro_Table();
        $table->addClass('mroCrudData');
        $table->addHeaderRow($this->makeTableHeader($daoInfo, $crudFactory));

        foreach ($results as $dao) {
            $table->addRow($this->makeRow($dao, $daoInfo, $crudFactory));
        }

        $master->add($table);

        // set on the contextxs
        $context->setPara('crudData', $master);
    }

    function query($daoType, $daoInfo, $context)
    {
        $values = array();

        foreach ($daoInfo->getFields() as $fieldInfo) {
            if ($fieldInfo->canQuery) {
                $fieldName = $fieldInfo->name;
                $value = $context->getArg($fieldName);
                if (!is_null($value) and $value != '') {
                    $values[$fieldName] = $value;
                }
            }
        }

        if (empty($values)) {
            return $values;
        } else {
            $daoManager = $this->getDaoManager();
            return $daoManager->query($daoType, $values);
        }
    }

    private function makeTableHeader($daoInfo, $crudFactory)
    {
        $labels = $crudFactory->createLabels($daoInfo);

        $rows = array();
        foreach ($labels as $label) {
            $rows[] = new Mro_TableCell($label, true);
        }

        $rows[] = new Mro_TableCell(new Mro_CrudLabel('&nbsp;'), true);
        $rows[] = new Mro_TableCell(new Mro_CrudLabel('&nbsp;'), true);
        return $rows;
    }

    private function makeRow($dao, $daoInfo, $crudFactory)
    {
        // get the raw dao if necessary
        $dao = $dao->getDao();

        $values = $crudFactory->createTextFields($dao, $daoInfo);
        $row = array();
        foreach ($values as $text) {
            $row[] = new Mro_TableCell($text);
        }

        {
            $crudForm = $crudFactory->createForm('GET');
            $crudForm->addCrudOperationField('edit');
            $crudForm->addCrudTypeField($dao->getType());
            $crudForm->addCrudIdField($dao->getId());
            $crudForm->add(new Mro_SubmitField('edit'));
            $row[] = new Mro_TableCell($crudForm);
        }

        {
            $crudForm = $crudFactory->createForm('POST');
            $crudForm->addCrudOperationField('delete');
            $crudForm->addCrudTypeField($dao->getType());
            $crudForm->addCrudIdField($dao->getId());
            $crudForm->add(new Mro_SubmitField('delete'));
            $row[] = new Mro_TableCell($crudForm);
        }

        return $row;
    }
}
