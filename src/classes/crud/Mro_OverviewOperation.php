<?php

namespace Maestro\crud;

use Maestro\conductor\Mro_Context;
use Maestro\html\Mro_Div;
use Maestro\html\Mro_HiddenField;
use Maestro\html\Mro_SubmitField;
use Maestro\html\Mro_Table;
use Maestro\html\Mro_TableCell;

/**
 * A list operation lists the elements of a DAO.
 */
class Mro_OverviewOperation extends Mro_CrudOperation
{

    function needsTransaction(): bool
    {
        return false;
    }

    function execute(Mro_Context $context): void
    {
        // get the daos
        $daoConfig = $this->getDaoConfig();

        $master = new Mro_Div();
        $master->addClass('mroCrudOverview');

        $table = new Mro_Table();
        $table->addClass('mroCrudData');
        $master->add($table);

        // header
        $header = array();
        $header[] = new Mro_TableCell(new Mro_CrudLabel('type'), true);
        $header[] = new Mro_TableCell(new Mro_CrudLabel('&nbsp;'), true);
        $header[] = new Mro_TableCell(new Mro_CrudLabel('&nbsp;'), true);
        $table->addHeaderRow($header);

        $crudFactory = $this->getCrudFactory();

        $crudActionName = $crudFactory->getCrudActionName();
        $daoInfoList = $daoConfig->asArray();
        foreach ($daoInfoList as $type => $daoInfo) {
            $row = array();
            $row[] = new Mro_TableCell(new Mro_CrudLabel($type), false);

            $crudForm = $crudFactory->createForm('GET');
            $crudForm->addCrudOperationField('list');
            $crudForm->addCrudTypeField($type);
            $crudForm->add(new Mro_HiddenField('page', 1));
            $crudForm->add(new Mro_HiddenField('pagesz', 20));
            $crudForm->add(new Mro_SubmitField('manage'));
            $row[] = new Mro_TableCell($crudForm, false);

            $queryForm = $crudFactory->createForm('GET');
            $queryForm->addCrudOperationField('query');
            $queryForm->addCrudTypeField($type);
            $queryForm->add(new Mro_SubmitField('query'));
            $row[] = new Mro_TableCell($queryForm, false);

            $table->addRow($row);
        }

        $context->setPara('crudData', $master);
    }
}
