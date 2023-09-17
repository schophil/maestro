<?php

namespace Maestro\crud;

/**
 */
interface Mro_CrudField
{

    public function writeValue($value);

    public function readValue($context);

    public function isHidden();

    public function getName();

    public function setEditable($editable);

    public function isEditable();
}
