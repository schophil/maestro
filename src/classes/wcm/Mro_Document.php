<?php

namespace Maestro\wcm;

use Maestro\db\Mro_DaoWrapper;

/**
 * A document in the WCM module of maestro consists of 4 paragraphs and a title.
 */
class Mro_Document extends Mro_DaoWrapper
{

    /**
     * Returns the classification of this document.
     * Classifications are used to make a distinction between documents;
     * for example there purpose.
     *
     * @return string
     */
    function getClassification()
    {
        return $this->getDao()->getValue('classification');
    }

    /**
     * Returns the title of this document.
     * return string
     *
     * @return string
     */
    function getTitle()
    {
        return $this->getDao()->getValue('title');
    }

    /**
     * Returns the unique name of the document.
     *
     * @return string
     */
    function getName()
    {
        return $this->getDao()->getValue('name');
    }

    /**
     * Returns the abstact of the document (text).
     * The abstract is a short description of the document.
     *
     * @return string
     */
    function getAbstract()
    {
        return $this->getDao()->getValue('abstract');
    }

    /**
     * Returns the content (a XHTML fragment).
     *
     * @return string
     */
    function getContent()
    {
        return $this->getDao()->getValue('content');
    }

    /**
     * Returns the last time the document was edited.
     *
     * @return Mro_Date
     */
    function getEditDate(): string
    {
        $date = $this->getDao()->getValue('ts');
        if (!is_null($date)) {
            return $date->format('D/M/Y');
        }
        return '';
    }
}

?>