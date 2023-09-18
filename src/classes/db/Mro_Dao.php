<?php
/**
 * @author Philippe Schottey <phschotte@gmail.com>
 * @package db
 */

namespace Maestro\db;

/**
 * DAO object.
 * These are the objects that will be used to exchange information between
 * database and program. DAO instances correspond to one entry in a table or
 * tables. A DAO has at least the following fields: id - the unique id of the
 * DAO; uc - the update counter indicating how many times a DAO was saved All
 * other fields are stored in an internal array named "data".
 */
class Mro_Dao
{

    private $type;
    private $id;
    private $uc;
    private $data;

    /**
     * Default constructor.
     *
     * @param string $id Id of this dao.
     * @param string $uc Update counter of this dao.
     * @param string $type the type of the DAO.
     */
    function __construct($id, $uc, $type)
    {
        $this->data = array();
        $this->type = $type;
        $this->uc = $uc;
        $this->id = $id;
    }

    /**
     * Returns the instance itself. This method is needed to be compatible with
     * dao instances that are wrapped by another class.
     *
     * @return $this
     */
    function getDao()
    {
        return $this;
    }

    /**
     * Returns the id of this dao.
     *
     * @return string
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * Returns the update counter of this dao.
     *
     * @return integer
     */
    function getUc()
    {
        return $this->uc;
    }

    /**
     * Returns the type of this dao.
     *
     * @return string The name of the type.
     */
    function getType()
    {
        return $this->type;
    }

    /**
     * Returns the value of a field.
     *
     * @param string $fieldName
     * @return mixed
     */
    function getValue($fieldName)
    {
        return $this->data[$fieldName];
    }

    /**
     * Sets the value of a field.
     *
     * @param string $fieldName The name of the field.
     * @param mixed $value The value of the field.
     * @return mixed The old value of the field.
     */
    function setValue($fieldName, $value): mixed
    {
        if (!isset($this->data[$fieldName])) {
            $this->data[$fieldName] = $value;
            return null;
        } else {
            $oldValue = $this->data[$fieldName];
            $this->data[$fieldName] = $value;
            return $oldValue;
        }
    }
}
