<?php

namespace Maestro\crud;

use Maestro\html\Mro_Form;

class Mro_CrudForm extends Mro_Form {
	
	function __construct($crudActionName, $method = 'POST') {
		parent::__construct('index.php', $method);
		
		$this->addClass('mroCrudForm');
		$this->addHiddenField('action', $crudActionName);
	}
	
	function addCrudOperationField($operation) {
		return $this->addHiddenField('operation', $operation);
	}
	
	function addCrudTypeField($type) {
		return $this->addHiddenField('daotype', $type);
	}
	
	function addCrudIdField($id) {
		return $this->addHiddenField('daoid', $id);
	}
}
