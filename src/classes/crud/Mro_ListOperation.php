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
class Mro_ListOperation extends Mro_CrudOperation
{

    function needsTransaction(): bool
    {
        return false;
    }

    /**
     * Creates a browsable table containing entries from the selected dao type.
     * Paging is automatically applied (default values: page 1 and page size 25).
     */
    function execute(Mro_Context $context): void
    {
        $type = $this->getCrudType($context);
        $daoInfo = $this->getDaoInfo($type);

        $pageSize = (int)$context->getArg('pagesz', 25);
        $page = (int)$context->getArg('page', 1);

        $daoManager = $this->getDaoManager();
        $daoList = $daoManager->listAll($type, $page, $pageSize);
        $currentPageSize = sizeof($daoList);

        $crudFactory = $this->getCrudFactory();

        // instantiate table
        // # columns: # fields + edit link + delete link
        $table = new Mro_Table();
        $table->addClass('mroCrudData');
        $table->addHeaderRow($this->makeTableHeader($daoInfo, $crudFactory));
        $table->addFooterRow($this->makeTableFooter($daoInfo));

        $div = new Mro_Div();
        $div->addClass('mroCrudList');
        $div->add($this->makeNavigation($type, $page, $pageSize, $currentPageSize));
        $div->add($table);

        // add daos to table
        foreach ($daoList as $dao) {
            $table->addRow($this->makeRow($dao, $daoInfo, $crudFactory, $page, $pageSize));
        }

        // create navigation table
        $div->add($this->makeNavigation($type, $page, $pageSize, $currentPageSize));

        $context->setPara('crudData', $div);
    }

    private function makeNavigation($type, $page, $pageSize, $currentPageSize)
    {
        $crudFactory = $this->getCrudFactory();

        // the button to go to the first page
        $formTop = $crudFactory->createForm('GET');
        $formTop->addCrudOperationField('list');
        $formTop->addCrudTypeField($type);
        $formTop->add(new Mro_HiddenField('page', 1));
        $formTop->add(new Mro_HiddenField('pagesz', $pageSize));
        $formTopSubmit = new Mro_SubmitField('top');
        if ($page == 1) {
            $formTopSubmit->disable();
        }
        $formTop->add($formTopSubmit);

        // previous page
        $formPrevious = $crudFactory->createForm('GET');
        $formPrevious->addCrudOperationField('list');
        $formPrevious->addCrudTypeField($type);
        $formPrevious->add(new Mro_HiddenField('page', $page - 1));
        $formPrevious->add(new Mro_HiddenField('pagesz', $pageSize));
        $formPreviousSubmit = new Mro_SubmitField('previous');
        if ($page == 1) {
            $formPreviousSubmit->disable();
        }
        $formPrevious->add($formPreviousSubmit);

        // create new
        $formCreate = $crudFactory->createForm('POST');
        $formCreate->addCrudOperationField('create');
        $formCreate->addCrudTypeField($type);
        $formCreate->add(new Mro_HiddenField('page', 1));
        $formCreate->add(new Mro_HiddenField('pagesz', $pageSize));
        $formCreate->add(new Mro_SubmitField('create new'));

        // next page
        $formNext = $crudFactory->createForm('GET');
        $formNext->addCrudOperationField('list');
        $formNext->addCrudTypeField($type);
        $formNext->add(new Mro_HiddenField('page', $page + 1));
        $formNext->add(new Mro_HiddenField('pagesz', $pageSize));
        $formNextSubmit = new Mro_SubmitField('next');
        if ($currentPageSize < $pageSize) {
            $formNextSubmit->disable();
        }
        $formNext->add($formNextSubmit);

        // create navigation table
        $navigation = new Mro_Table();
        $navigation->addClass('mroCrudAction');

        $cell1 = new Mro_TableCell($formTop, false);
        $cell2 = new Mro_TableCell($formPrevious, false);
        $cell3 = new Mro_TableCell($formCreate, false);
        $cell4 = new Mro_TableCell($formNext, false);
        $cell5 = new Mro_TableCell(new Mro_CrudLabel("page {$page}"));

        $navigation->addRow(array($cell1, $cell2, $cell3, $cell4, $cell5));

        return $navigation;
    }

    private function makeTableFooter($daoInfo)
    {
        $rows = array();
        foreach ($daoInfo->getFields() as $field) {
            $rows[] = new Mro_TableCell(new Mro_CrudLabel('&nbsp;'), false);
        }

        $rows[] = new Mro_TableCell(new Mro_CrudLabel('&nbsp;'), false);
        $rows[] = new Mro_TableCell(new Mro_CrudLabel('&nbsp;'), false);
        return $rows;
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

    private function makeRow($dao, $daoInfo, $crudFactory, $page, $pageSize)
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
            $crudForm->addHiddenField('page', $page);
            $crudForm->addHiddenField('pagesz', $pageSize);
            $crudForm->add(new Mro_SubmitField('edit'));
            $row[] = new Mro_TableCell($crudForm);
        }

        {
            $crudForm = $crudFactory->createForm('POST');
            $crudForm->addCrudOperationField('delete');
            $crudForm->addCrudTypeField($dao->getType());
            $crudForm->addCrudIdField($dao->getId());
            $crudForm->addHiddenField('page', $page);
            $crudForm->addHiddenField('pagesz', $pageSize);
            $crudForm->add(new Mro_SubmitField('delete'));
            $row[] = new Mro_TableCell($crudForm);
        }

        return $row;
    }
}
