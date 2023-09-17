<?php

namespace Maestro\crud;

class Mro_CrudLink
{

    private $operation;

    private $type;

    private $id;

    public function __construct($content, $operation, $type, $id)
    {
        $this->operation = $operation;
        $this->type = $type;
        $this->id = $id;
        $this->content = $content;
    }

    public function createHtml()
    {
        $link = '<a href="';
        $link .= "serve.php?action=crud&operation={$this->operation}";
        if (!is_null($this->type)) {
            $link .= "&daotype={$this->type}";
        }
        if (!is_null($this->id)) {
            $link .= "&daoid={$this->id}";
        }
        $link .= '"';
        $link .= ">{$this->content}</a>";
        return $link;
    }

    public function __toString()
    {
        return $this->createHtml();
    }
}
