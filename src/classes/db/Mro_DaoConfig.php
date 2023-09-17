<?php

/**
 * @author Philippe Schottey <phschotte@gmail.com>
 * @package db
 */

namespace Maestro\db;

/**
 * Holds the dao configuration to be used by a dao manager.
 * It contains a list of dao infos.
 */
class Mro_DaoConfig
{

	private $daoInfos = array();

	/**
	 * Adds a new dao info. This will overwrite any existing dao info with the 
	 * same type name. By default the Mro_DefaultGenerator will be used as 
	 * id generator.
	 *
	 * @param Mro_DaoInfo $daoInfo The new dao info to add.
	 * @return Mro_DaoInfo The newly added dao info.
	 */
	function addDao($typeName, $idGenerator = null)
	{
		$daoInfo = new Mro_DaoInfo($typeName);
		$this->daoInfos[$typeName] = $daoInfo;
		if (!isset($idGenerator)) {
			$idGenerator = new Mro_DefaultGenerator();
		}
		$daoInfo->idGenerator = $idGenerator;
		return $daoInfo;
	}

	/**
	 * Returns the dao info for a given type name.
	 * If no dao info is found null will be returned.
	 *
	 * @param string daoTypeName The name of the dao type.
	 * @return Mro_DaoInfo 
	 */
	function getDao($daoTypeName): ?Mro_DaoInfo
	{
		return $this->daoInfos[$daoTypeName];
	}

	/**
	 * Returns an array containing all the dao info.
	 * @return array
	 */
	function asArray(): array
	{
		$copy = array();
		foreach ($this->daoInfos as $name => $info) {
			$copy[$name] = $info;
		}
		return $copy;
	}
}
