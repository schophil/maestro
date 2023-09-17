<?php

namespace Maestro\html;

/**
 * Interface of every HTML element.
 */
abstract class Mro_HtmlElement
{

    private $style;
    private $id;
    private $classes;

    function __construct()
    {
        $this->classes = array();
    }

    function addClass($aClass, $remove = false)
    {
        if (!isset($this->classes)) {
            $this->classes[] = $aClass;
        }

        $index = array_search($aClass, $this->classes);
        if ($index and $remove) {
            unset($this->classes[$index]);
            $index = false;
        }
        if (!$index) {
            $this->classes[] = $aClass;
        }
    }

    function setFirstClass($aClass)
    {
        $this->removeClass($aClass);
        $this->classes = array_merge(array($aClass), $this->classes);
    }

    function removeClass($aClass)
    {
        if ($index = array_search($aClass, $this->classes)) {
            unset($this->classes[$index]);
        }
    }

    function removeAllClasses()
    {
        // just create new array
        $this->classes = array();
    }

    function setStyle($style)
    {
        $this->style = $style;
    }

    function appendToStyle($styleFragment)
    {
        $this->style .= " {$styleFragment}";
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getId()
    {
        return $this->id;
    }

    function getStyle()
    {
        return $this->style;
    }

    function hasId()
    {
        return !is_null($this->id);
    }

    function hasClass()
    {
        return sizeof($this->classes) > 0;
    }

    function hasStyle()
    {
        return !is_null($this->style);
    }

    function appendIdAttribute($html)
    {
        if ($this->hasId()) {
            $html .= " id=\"{$this->getId()}\"";
        }
        return $html;
    }

    function appendClassAttribute($html)
    {
        if ($this->hasClass()) {
            $classValue = '';
            foreach ($this->classes as $aClass) {
                if ($classValue != '') {
                    $classValue .= ' ';
                }
                $classValue .= $aClass;
            }

            $html .= " class=\"{$classValue}\"";
        }
        return $html;
    }

    function appendStyleAttribute($html)
    {
        if ($this->hasStyle()) {
            $html .= " style=\"{$this->getStyle()}\"";
        }
        return $html;
    }

    function appendDefaultAttributes($html)
    {
        $html = $this->appendIdAttribute($html);
        $html = $this->appendClassAttribute($html);
        $html = $this->appendStyleAttribute($html);
        return $html;
    }

    function __toString()
    {
        return $this->createHtml();
    }

    /**
     * Generates the HTML code.
     */
    abstract function createHtml();
}
