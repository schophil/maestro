<?php

namespace Maestro\crud;

use Maestro\db\Mro_Dao;
use Maestro\db\Mro_DaoInfo;
use Maestro\db\Mro_DateTimeType;
use Maestro\db\Mro_DateType;
use Maestro\db\Mro_FieldInfo;
use Maestro\db\Mro_ListType;
use Maestro\db\Mro_StringType;
use Maestro\db\Mro_TextType;
use Maestro\db\Mro_XhtmlType;
use Maestro\html\Mro_DateField;
use Maestro\html\Mro_SelectField;
use Maestro\html\Mro_TextAreaField;
use Maestro\html\Mro_TextField;
use Maestro\html\Mro_XhtmlField;
use Monolog\Registry;

/**
 * A factory to create CRUD elements like fields and links.
 */
class Mro_CrudFactory
{

    private $log;

    private $crudActionName;

    function __construct($crudActionName)
    {
        $this->log = Registry::getInstance('crud');
        $this->crudActionName = $crudActionName;
    }

    function getCrudActionName()
    {
        return $this->crudActionName;
    }

    function createUri($operation, $type = null, $id = null)
    {
        return new Mro_CrudUri($this->getCrudActionName(), $operation, $type, $id);
    }

    function createForm($action)
    {
        return new Mro_CrudForm($this->getCrudActionName(), $action);
    }

    /**
     * Creates an array with textual values for the dao fields.
     *
     * @param Mro_Dao $dao The dao from which the fields should be used
     * @param Mro_DaoInfo $daoInfo Meta information about the dao.
     * @return array of string instances
     */
    function createTextFields(Mro_Dao $dao, Mro_DaoInfo $daoInfo)
    {
        $text = array();
        $text[] = "{$dao->getId()}";
        $text[] = "{$dao->getUc()}";
        // we iterate over the data to have the same order
        foreach ($daoInfo->getFields() as $fieldName => $fieldInfo) {
            if ($fieldInfo->isDataField()) {
                $this->log->debug("creating text field for {$fieldName}");
                $name = $fieldInfo->name;
                $type = $fieldInfo->type;
                $value = $dao->getValue($name);

                if ($type instanceof Mro_DateType or $type instanceof Mro_DateTimeType) {

                    // format the date
                    $formatted = null;
                    if (!is_null($value)) {
                        $formatted = $value->format($type->getExternalFormat());
                    }
                    $text[] = $formatted;

                } elseif ($type instanceof Mro_TextType or $type instanceof Mro_XhtmlType) {

                    // we do not show the complete content
                    $text[] = '...';

                } else {

                    // by default, show the to string of the value
                    $text[] = "{$value}";

                }
            }
        }
        return $text;
    }

    /**
     * Creates an array with the labels for the fields.
     *
     * @param Mro_DaoInfo $daoInfo Meta information about the dao.
     * @return array of string instances
     */
    function createLabels($daoInfo)
    {
        $labels = array();
        // $labels[] = $daoInfo->type . '.id';
        // we iterate over the data to have the same order
        foreach ($daoInfo->getFields() as $fieldInfo) {
            $labels[] = $this->createLabel($daoInfo->typeName, $fieldInfo->name, true);
        }
        return $labels;
    }

    /**
     * Creates a label based on file name and a type.
     *
     * @param string $fieldName
     * @param boolean $translate
     */
    function createLabel($type, $fieldName, $translate = true)
    {
        $label = null;
        if ($fieldName === 'id') {
            $label = new Mro_CrudLabel('id', $translate);
        } elseif ($fieldName === 'uc') {
            $label = new Mro_CrudLabel($fieldName, $translate);
        } else {
            $label = new Mro_CrudLabel($type . '.' . $fieldName, $translate);
        }
        return $label;
    }

    /**
     * Creates a crud field based on a dao field.
     * The name of the field will be used as name for the field.
     *
     * @param Mro_FieldInfo $fieldIndo Information about the dao field
     * @param $value The value of the dao field.
     * @return object A html input field
     */
    function createCrudField(Mro_FieldInfo $fieldInfo, $value = null)
    {
        $field = $this->createCrudInputField($fieldInfo, $fieldInfo->name);

        if (!is_null($value)) {
            $field->writeValue($value);
        }

        return $field;
    }

    /**
     * Creates a crud field with a custom name.
     *
     * @param Mro_FieldInfo $fieldIndo Information about the dao field
     * @param string $nale The name of the new field
     * @param mixed $value The value of the dao field.
     * @return object a html input field
     */
    function createCrudFieldWithName($fieldInfo, $name, $value = null)
    {
        $field = $this->createCrudInputField($fieldInfo, $name);

        if (!is_null($value)) {
            $field->writeValue($value);
        }

        return $field;
    }

    private function createCrudInputField(Mro_FieldInfo $fieldInfo, string $name)
    {
        $type = $fieldInfo->type;
        $typeName = get_class($type);

        $this->log->debug("create crud field of type {$typeName} with name {$name}");

        if ($type instanceof Mro_StringType) {

            // create a text field
            $field = new Mro_TextField($name, true);
            $field->setMaxLength($type->getMaxSize());

        } elseif ($type instanceof Mro_TextType) {

            // create a text area
            $field = new Mro_TextAreaField($name, true);

        } elseif ($type instanceof Mro_XhtmlType) {

            // create a text area
            $field = new Mro_XhtmlField($name, true);

        } elseif ($type instanceof Mro_DateType) {

            // create a date field
            $field = new Mro_DateField($name, true);
            $field->setFormat($type->getExternalFormat());

        } elseif ($type instanceof Mro_DateTimeType) {

            // create a date time field
            $field = new Mro_DateField($name, true);
            $field->setFormat($type->getExternalFormat());

        } elseif ($type instanceof Mro_ListType) {

            // create a select field
            $field = new Mro_SelectField($name, true);
            $selectValues = array();
            // add the default null value
            $selectValues[''] = '-none-';
            foreach ($type->getValues() as $selectable) {
                $selectValues[$selectable] = $selectable;
            }
            $field->setValues($selectValues);

        } else {

            // create a text field by default
            $field = new Mro_TextField($name);

        }

        $field->setAlt("{$name}.alt");

        if (!$fieldInfo->canEdit) {
            $field->setEditable(false);
            $field->disable();
        }

        return $field;
    }
}
