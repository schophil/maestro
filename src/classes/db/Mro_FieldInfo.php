<?php
/**
 * vim: set ts=4 sw=4:
 * @author Philippe Schottey <phschotte@gmail.com>
 * @package db
 */

namespace Maestro\db;

/**
 * Information about a DAO filed. It describe some additional database
 * information (for example: column info). It also desribes the type of
 * the field. This type is a high level type. The DAO manager will map
 * these high level types to concrete database types.
 */
class Mro_FieldInfo
{

    /**
     * @The name of the field.
     */
    public $name;
    /**
     * The type of the fied.
     */
    public $type;
    /**
     * The column index of the field.
     */
    public $columnIndex;
    /**
     * Indicates if it is a field part of the standard crud query.
     * Default: true
     */
    public $canQuery = true;
    /**
     * Indicates how many times a condition can be placed on this field when queried.
     * This will only be taken into account if the field can be queried (see $canQuery).
     * Default: 1
     */
    public $queryCount = 1;
    /**
     * Indicates if the field can be edited in the crud operation.
     * Default: true
     */
    public $canEdit = true;

    /**
     * Disables query mode on this field.
     */
    function disableQuery()
    {
        $this->canQuery = false;
        return $this;
    }

    /**
     * Disables edit mode on this field.
     */
    function disableEdit()
    {
        $this->canEdit = false;
        return $this;
    }

    /**
     * Checks if this a field other than id or uc.
     *
     * @return boolean True if this is a data field.
     */
    function isDataField()
    {
        return $this->name != 'id' && $this->name != 'uc';
    }

    /**
     * Checks if this the id field.
     *
     * @return boolean True if this is the id field.
     */
    function isIdField()
    {
        return $this->name === 'id';
    }

    /**
     * Checks if this the uc field.
     *
     * @return boolean True if this the uc field.
     */
    function isUcField()
    {
        return $this->name === 'uc';
    }

    /**
     * Sets how many times a condition can be placed on the field when queried.
     * This will also enable the query mode on this field.
     *
     * @param integer $queryCount The number of times.
     * @return object This field info
     */
    function queryTill($queryCount)
    {
        $this->queryCount = $queryCount;
        $this->canQuery = true;
        return $this;
    }
}
