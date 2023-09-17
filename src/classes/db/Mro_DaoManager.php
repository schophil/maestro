<?php
/**
 * @author Philippe Schottey <phschotte@gmail.com>
 * @package db
 */

namespace Maestro\db;

use Maestro\Mro_Exception;
use Monolog\Registry;

/**
 * The DAO manager responsible loading and persisting DAO's in the database.
 * A DAO configuration containing the DAO's and column information is used to
 * mapping DAO's to the database. The Mro_PreparedSql class is used to construct
 * sql queries.
 * WARNING: column indexes from the dao info start at 1; the columns in the 
 * database result sets start on 0
 */
class Mro_DaoManager {

	private $daoConfig;
	private $connection;
	private $log;
	private $dateFormat;

	/**
	 * Default constructor.
	 *
	 * @param Mro_DaoConfig $daoConfig The DAO configuration.
	 * @param object $connection A connection to the database.
	 * @param string dateFormat The date format to use when persisting and reading.
	 */
	function __construct($daoConfig, $connection, $dateFormat) {
		$this->log = Registry::getInstance('db');
		$this->daoConfig = $daoConfig;
		$this->connection = $connection;
		$this->dateFormat = $dateFormat;
	}

	/**
	 * Returns the dao config of this dao manager.
	 *
	 * @return Mro_DaoConfig The dao configuration
	 */
	function getDaoConfig() {
		return $this->daoConfig;
	}

	/**
	 * Returns the connection used by this dao manager.
	 *
	 * @return mixed
	 */
	function getConnection() {
		return $this->connection;
	}

	/**
	 * Starts a transaction.
	 *
	 * @throws Mro_Exception
	 */
	function startTransaction() {
		$this->log->debug('start transaction');
		return $this->connection->startTransaction();
	}

	/**
	 * Commits the current transaction.
	 * 
	 * @throws Mro_Exception
	 */
	function commitTransaction() {
		$this->log->debug('commit transaction');
		return $this->connection->commitTransaction();
	}

	/**
	 * Rollbacks the current transaction.
	 * 
	 * @throws Mro_Exception
	 */
	function rollbackTransaction() {
		$this->log->debug('rollback transaction');
		return $this->connection->rollbackTransaction();
	}

	/**
	 * Create a new dao of the given type. A new record will be created in the database.
	 * 
	 * @param string $type The type name of the dao to create.
	 * @return string The unique id of the new instance
	 * @throws Mro_Exception
	 */
	function create($type) {
		$this->log->debug("create dao of {$type}");
		$result = null;
		// first get the DAO information
		$daoInfo = $this->getDaoInfo($type);

		if (!is_null($daoInfo->insertQuery)) {
			// fetch the next id for the new DAO
			$idGenerator = $daoInfo->idGenerator;
			$nextId = $idGenerator->generate($this->connection);
			$this->log->debug("unique id: {$nextId}");

			// prepare the insert sql
			$psql = new Mro_PreparedSql($daoInfo->insertQuery, true, $this->dateFormat);
			$values = array();
			$values[ID_FIELD] = $nextId;
			$values[UPDATE_COUNTER_FIELD] = 0;
			$sql = $psql->prepare($values);

			// execute the insert SQL
			$inserted = $this->connection->apply($sql);

			if ($inserted == 0) {
				throw new Mro_Exception("insert failed for {$sql}");
			} else {
				$result = $nextId;
			}
		} else {
			throw new Mro_Exception("no insert sql for {$type}");
		}
		return $result;
	}

	/**
	 * Persists the given dao.
	 *
	 * @param Mro_Dao $dao The dao to persist.
	 * @throws Mro_Exception
	 */
	function save($dao) {
		$this->log->debug("saving {$dao->getType()}:{$dao->getId()}");

		$dao = $dao->getDao();

		if (is_null($dao->getId())) {
			throw new Mro_Exception('dao has no id');
		} else {
			// get the dao info
			$daoInfo = $this->getDaoInfo($dao->getType());

			if (!is_null($daoInfo->updateQuery)) {
				// make a values map
				$values = array();
				
				// set the standard fields
				$values[ID_FIELD] = $dao->getId();
				$values[NEW_UPDATE_COUNTER_FIELD] = $dao->getUc() + 1;
				$values[UPDATE_COUNTER_FIELD] = $dao->getUc();
				
				// set the dao specific fields
				foreach ($daoInfo->getFields() as $fieldInfo) {
					if ($fieldInfo->isDataField()) {
						$values[$fieldInfo->name] = $dao->getValue($fieldInfo->name);
					}
				}

				// create the update SQL with the prepated SQL class
				$psql = new Mro_PreparedSql($daoInfo->updateQuery, true, $this->dateFormat);
				$sql = $psql->prepare($values);

				$updated = $this->connection->apply($sql);
				// check number of updated rows
				if ($updated == 0) {
					throw new Mro_Exception("update failed for {$sql}");
				}
			} else {
				throw new Mro_Exception("no update sql for {$dao->getType()}");
			}
		}
	}

	/**
	 * Loads a dao from the database. 
	 *
	 * @param string $daoType The type of the dao, needed to get the SQL.
	 * @param string $id The primary key of the corresponding record.
	 * @return Mro_Dao
	 * @throws Mro_Exception
	 */
	function load($daoType, $id) {
		$this->log->debug("loading {$daoType}:{$id}");

		$dao = null;
		$daoInfo = $this->getDaoInfo($daoType);

		if (!is_null($daoInfo->loadQuery)) {
			$psql = new Mro_PreparedSql($daoInfo->loadQuery, true, $this->dateFormat);
			$values[ID_FIELD] = $id;
			$sql = $psql->prepare($values);

			$result = $this->connection->query($sql, NO_LOCK);

			if ($result->next()) {
				$dao = new Mro_Dao($result->getString(0), $result->getInteger(1), $daoType);

				foreach ($daoInfo->getFields() as $name => $fieldInfo) {
					if ($fieldInfo->isDataField()) {
						// Column indexes start from 0 in the result set, the 
						// indexes in the dao info start on 1. We must therefore 
						// decrement the index from the field info.
						$columnIndex = $fieldInfo->columnIndex - 1;
						$fieldType = $fieldInfo->type;
						
						$value = $this->getFieldValue($columnIndex, $fieldType, $result);
						$dao->setValue($name, $value);
					}
				}

				$dao = $this->wrapDao($dao, $daoInfo->className);
			} else {
				throw new Mro_Exception("unexisting dao type {$daoType}:{$id}");
			}
		} else {
			throw new Mro_Exception("no load sql for {$daoType}");
		}
		
		return $dao;
	}

	/**
	 * Deletes a dao from the database.
	 *
	 * @param Mro_Dao $dao The dao to delete.
	 * @throws Mro_Exception
	 */
	function delete($dao) {
		// make sure we have the raw dao and not the wrapper
		$dao = $dao->getDao();

		$daoType = $dao->getType();
		$this->log->debug("deleting {$dao->getType()}:{$dao->getId()}");

		$daoInfo = $this->getDaoInfo($daoType);

		if (!is_null($daoInfo->deleteQuery)) {
			$psql = new Mro_PreparedSql($daoInfo->deleteQuery, true, $this->dateFormat);
			$values = array();
			$values[ID_FIELD] = $dao->getId();
			$values[UPDATE_COUNTER_FIELD] = $dao->getUc();
			$sql = $psql->prepare($values);

			$deleted = $this->connection->apply($sql);
			if ($deleted == 0) {
				throw new Mro_Exception("unable to delete {$dao->getType()}:{$dao->getId()}");
			}
		} else {
			throw new Mro_Exception("no delete sql for {$dao->getType()}");
		}
	}

	/**
	 * Lists all the DAOs based on the configured list SQL.
	 *
	 * @param string $daoType The type to list.
	 * @param integer $page The number of the page to read
	 * @param integer $pageSize The size of a single page
	 * @return array
	 * @throws Mro_Exception
	 */
	function listAll($daoType, $page = null, $pageSize = null) {
		$this->log->debug("list {$daoType}");

		// get the dao info and its table name
		$daoInfo = $this->getDaoInfo($daoType);
		
		// get the search
		if (!is_null($daoInfo->listQuery)) {
			// prepare sql
			$psql = new Mro_PreparedSql($daoInfo->listQuery, true, $this->dateFormat);
			$sql = $psql->prepare(array());

			return $this->collect($sql, $daoType, $page, $pageSize);
		} else {
			throw new Mro_Exception("no list sql for {$daoType}");
		}
	}

	/**
	 * Searches dao's
	 *
	 * @param string $daoType The type of dao.
	 * @param string $search Name of the search function.
	 * @param array $searchdata The parameters for the search.
	 * @param integer $page The number of the page to read
	 * @param integer $pageSize The size of a single page
	 * @return array An array of Mro_Dao instances.
	 * @throws Mro_Exception
	 */
	function search($daoType, $search, $searchData, $page = null, $pageSize = null) {
		$this->log->debug("searching {$daoType}:{$search}");

		// get the dao info and its table name
		$daoInfo = $this->getDaoInfo($daoType);

		// get the search
		$searchSql = $daoInfo->getSearchQuery($search);
		if (!is_null($searchSql)) {
			// prepare sql
			$psql = new Mro_PreparedSql($searchSql, true, $this->dateFormat);
			$sql = $psql->prepare($searchData);

			return $this->collect($sql, $daoType, $page, $pageSize);
		} else {
			throw new Mro_Exception("unexisting search {$daoType}:{$search}");
		}
	}
	
	/**
	 * Executes the standard dao query.
	 *
	 * @param string $daoType The type of dao.
	 * @param array $searchdata The parameters for the search.
	 * @param integer $page The number of the page to read
	 * @param integer $pageSize The size of a single page
	 * @return array An array of Mro_Dao instances.
	 * @throws Mro_Exception
	 */
	function query($daoType, $searchData, $page = null, $pageSize = null) {
		$this->log->debug("query {$daoType}");
		
		// get the dao info and its table name
		$daoInfo = $this->getDaoInfo($daoType);
		
		// get the search
		$sql = $daoInfo->queryQuery;
		if (!is_null($sql)) {
			// prepare sql
			$psql = new Mro_PreparedSql($sql, true, $this->dateFormat);
			$sql = $psql->prepare($searchData);
			
			return $this->collect($sql, $daoType, $page, $pageSize);
		} else {
			throw new Mro_Exception("no query sql for {$daoType}");
		}
	}

	private function collect($sql, $daoType, $page = null, $pageSize = null) {
		// execute sql
		$result = $this->connection->query($sql, NO_LOCK);

		// apply page information
		$collect = true;
		if (isset($page) && isset($pageSize)) {
			if ($page != 1) {
				$collect = $result->forward(($page - 1) * $pageSize);
			}
		}

		$daoList = array();
		// start loading the result
		if ($collect) {
			while ($result->next() && $collect) {
				$id = $result->getString(0);
				$daoList[] = $this->load($daoType, $id);
				if (isset($pageSize)) {
					$collect = sizeof($daoList) < $pageSize;
				}
			}
		}
		return $daoList;
	}

	// looks up the DAO information
	private function getDaoInfo($type) {
		$daoInfo = $this->daoConfig->getDao($type);
		if (is_null($daoInfo)) {
			throw new Mro_Exception("unexisting dao type {$type}");
		}
		return $daoInfo;
	}

	// returns the value for a DAO field from a result set
	private function getFieldValue($index, $type, $resultSet) {
		$dbType = $type->getDatabaseType();

		$value = null;
		if ($dbType == CHAR_TYPE) {
			$value = $resultSet->getString($index);
			$value = stripslashes($value);
		} elseif ($dbType == NUMBER_TYPE) {
			$value = $resultSet->getInteger($index);
		} elseif ($dbType == DATE_TYPE) {
			$value = $resultSet->getDate($index);
		} elseif ($dbType == DATE_TIME_TYPE) {
			$value = $resultSet->getDateTime($index);
		} elseif ($dbType == CLOB_TYPE) {
			// just map it to regular string
			$value = $resultSet->getString($index);
			$value = stripslashes($value);
		}
		return $value;
	}

	// wrap a list of dao's
	private function wrapDaoList($list, $className) {
		if (!is_null($className)) {
			foreach ($list as $key => $dao) {
				$list[$key] = $this->wrapDao($dao, $className);
			}
		}
		return $list;
	}

	// wrap a single dao
	private function wrapDao($dao, $className) {
		if (is_null($className)) {
			return $dao;
		}
		$class = new \ReflectionClass($className);
		// the wrapper class should have at least one constructor
		$object = $class->newInstance($dao);
		return $object;
	}
}
