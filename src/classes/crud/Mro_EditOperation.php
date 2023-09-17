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
class Mro_EditOperation extends Mro_CrudOperation
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

		// page information
		$pageSize = (int) $context->getArg('pagesz', 25);
		$page = (int) $context->getArg('page', 1);

		// get the dao
		$id = $this->getDaoId($context);
		$daoManager = $this->getDaoManager();
		$dao = $daoManager->load($type, $id);

		// get the raw dao if necessary
		$dao = $dao->getDao();

		// get the crud factory
		$crudFactory = $this->getCrudFactory();

		// the master DIV
		$masterDiv = new Mro_Div();
		$masterDiv->addClass("mroCrudEdit");
		$masterDiv->addClass("mroDao{$type}");

		// the edit form
		$crudForm = $crudFactory->createForm('POST');
		$crudForm->addCrudOperationField('update');
		$crudForm->addCrudTypeField($type);
		$crudForm->addCrudIdField($id);
		// the table containing the fields
		$table = new Mro_Table();
		$table->addClass('mroCrudData');

		foreach ($daoInfo->getFields() as $fieldInfo) {
			$fieldName = $fieldInfo->name;

			$fieldValue = null;
			if ($fieldInfo->isDataField()) {
				$fieldValue = $dao->getValue($fieldName);
			} elseif ($fieldInfo->isIdField()) {
				$fieldValue = $dao->getId();
			} elseif ($fieldInfo->isUcField()) {
				$fieldValue = $dao->getUc();
			}

			$field = $crudFactory->createCrudField($fieldInfo, $fieldValue);
			$label = $crudFactory->createLabel($type, $fieldName);
			$htmlId = "mroCrud_{$type}_{$fieldName}";

			$row = array();
			$row[] = new Mro_TableCell($label);
			$row[0]->addClass('mroLabel');
			$row[] = new Mro_TableCell($field);
			$table->addRow($row);
		}
		$crudForm->add($table);

		// and a save button
		$saveBlock = new Mro_Div();
		$saveBlock->addClass('mroCrudAction');
		$saveBlock->add(new Mro_SubmitField('save', 'usubmit'));
		$crudForm->add($saveBlock);

		// and a save and close button
		$saveBlock = new Mro_Div();
		$saveBlock->addClass('mroCrudAction');
		$saveBlock->add(new Mro_SubmitField('save and close', 'usubmit'));
		$crudForm->add($saveBlock);

		if (isset($page)) {
			$crudForm->addHiddenField('page', $page);
			$crudForm->addHiddenField('pagesz', $pageSize);
		}

		$masterDiv->add($crudForm);

		// the button to cancel the edit operation
		$cancelBlock = new Mro_Div();
		$cancelBlock->addClass('mroCrudAction');
		$cancelForm = $crudFactory->createForm('GET');
		$cancelForm->addClass('mroCrudCancel');
		$cancelForm->addCrudOperationField('list');
		$cancelForm->addCrudTypeField($type);
		if (isset($page)) {
			$cancelForm->addHiddenField('page', $page);
			$cancelForm->addHiddenField('pagesz', $pageSize);
		}
		$cancelForm->add(new Mro_SubmitField('cancel/close'));
		$cancelBlock->add($cancelForm);
		$masterDiv->add($cancelBlock);

		// set on the contextxs
		$context->setPara('crudData', $masterDiv);
	}
}
