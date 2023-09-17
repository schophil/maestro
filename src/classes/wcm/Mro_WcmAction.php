<?php

namespace Maestro\wcm;

use Maestro\conductor\Mro_BasicAction;
use Maestro\logic\Mro_CompositeLogic;
use Maestro\logic\Mro_Logic;
use Maestro\logic\Mro_LogicComposer;

/**
 * A Mro_Action implementation for accessing documents in the wcm plugin.
 */
class Mro_WcmAction extends Mro_BasicAction {
	
	private Mro_LogicComposer $logicComposer;

	private string $defaultName;

	/**
	 * Default constructor.
	 * @param Mro_LogicComposer $logicComposer
	 * @param string $defaultName The name of the default document to fetch 
	 */
	function __construct(Mro_LogicComposer $logicComposer, string $defaultName = null) {
		$this->logicComposer = $logicComposer;
		$this->defaultName = $defaultName;
	}

	function getName(): string
	{
		return 'wcm';
	}

	function getLogic($context): Mro_Logic
	{
		$docName = $context->getArg('name');
		if (!isset($docName)) {
			$docName = $this->defaultName;
		}

		// add default wcm logic
		$logic = new Mro_GetDocument($docName);

		// add the extra logic
		$extraLogic = $this->logicComposer->compose($docName);
		if (!is_null($extraLogic)) {
			// create a logic composition
			$composite = new Mro_CompositeLogic();
			$composite->add($logic);
			$composite->add($extraLogic);

			$logic = $composite;
		}

		return $logic;
	}

	function getPageId($context): string
	{
		$docName = $context->getArg('name');
		if (!isset($docName)) {
			$docName = $this->defaultName;
		}

		return 'wcm.' . $docName;
	}
}
