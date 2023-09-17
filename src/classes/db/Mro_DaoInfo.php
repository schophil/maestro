<?php
/**
 * @author Philippe Schottey <phschotte@gmail.com>
 * @package db
 */

namespace Maestro\db;

/**
 * Information about a DAO.
 * This class describes a specific DAO. Among others it describes the mapping
 * between attributes and database fields, the SQL statements to use...
 */
class Mro_DaoInfo
{

    /**
     * The query to use to list the items of the dao.
     * @var string
     */
    public $listQuery;
    /**
     * The query to use when inserting a new entry.
     * @var string
     */
    public $insertQuery;
    /**
     * The query to use to delete an entry.
     * @var string
     */
    public $deleteQuery;
    /**
     * The query to use when updating (saving) an entry.
     * @var string
     */
    public $updateQuery;
    /**
     * The query to use when loading an entry as a dao instance.
     * @var string
     */
    public $loadQuery;
    /**
     * The query to use with the CRUD query functionality.
     * @var string
     */
    public $queryQuery;
    /**
     * The name of the dao
     * @var string
     */
    public $typeName;
    /**
     * The id generator to use
     * @var object (Mro_IdGenerator)
     */
    public $idGenerator;
    /**
     * The name of the class that should be used to instantiate the dao.
     * @var string
     */
    public $className;

    private $searchQueries = array();
    private $fields = array();

    /**
     * Default constructor.
     *
     * @param string $type The DAO type.
     */
    function __construct($typeName)
    {
        $this->typeName = $typeName;
    }

    function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }

    /**
     * Registers the DQL to create a new dao instance.
     *
     * @param string $insertQuery The DQL to create a new entry/
     */
    function insertWith($insertQuery)
    {
        $this->insertQuery = $insertQuery;
    }

    /**
     * Registers the DQL to delete a dao instance.
     *
     * @param string $deleteQuery A DQL
     */
    function deleteWith($deleteQuery)
    {
        $this->deleteQuery = $deleteQuery;
    }

    /**
     * Register the DQL to load a dao instance.
     *
     * @param string $loadQuery The DQL
     */
    function loadWith($loadQuery)
    {
        $this->loadQuery = $loadQuery;
    }

    /**
     * Registers the DQL to update a dao instance.
     *
     * @param string $updateQuery The DQL.
     */
    function updateWith($updateQuery)
    {
        $this->updateQuery = $updateQuery;
    }

    /**
     * Set the query to list the available dao entries.
     *
     * @param string $listQuery
     */
    function listWith($listQuery)
    {
        $this->listQuery = $listQuery;
    }

    /**
     * Sets the query to use in the CRUD query functionality.
     *
     * @param string $queryQuery A SQL.
     */
    function queryWith($queryQuery)
    {
        $this->queryQuery = $queryQuery;
    }

    /**
     * Adds a new field to the
     *
     * @param string $fieldName The name of the field
     * @param integer $columnIndex The index of the corresponding column.
     * @param object $fieldType The type of the field.
     * @return The newly added field info
     */
    function addField($fieldName, $columnIndex, $fieldType)
    {
        $fieldInfo = new Mro_FieldInfo();
        $fieldInfo->name = $fieldName;
        $fieldInfo->columnIndex = $columnIndex;
        $fieldInfo->type = $fieldType;
        $this->fields[$fieldName] = $fieldInfo;
        return $fieldInfo;
    }

    /**
     * Returns the available fields. This will return a copy of the internal
     * array, not the array itself.
     *
     * @return array of field info.
     */
    function getFields()
    {
        $newArray = array();
        foreach ($this->fields as $key => $field) {
            $newArray[$key] = $field;
        }
        return $newArray;
    }

    /**
     * Adds a search query.
     *
     * @param string $name The (unique) name of the search query
     * @param string $query The actual SQL query
     * @return The newly added SQL query
     */
    function addSearchQuery($name, $query)
    {
        $this->searchQueries[$name] = $query;
        return $query;
    }

    /**
     * Returns the DQL corresponding to a search query name.
     *
     * @param string $name The name of a search query
     * @return The actual SQL query (unset if not found)
     */
    function getSearchQuery($name)
    {
        return $this->searchQueries[$name];
    }
}
