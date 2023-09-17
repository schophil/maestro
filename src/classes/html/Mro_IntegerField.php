<?php

namespace Maestro\html;

/**
 * A Mro_IntegerField is a text field that only accepts integer values.
 */
class Mro_IntegerField extends Mro_TextField
{

    /**
     * Constructor.
     * @param string $name The name of this input field.
     * @param boolean $editable Indicates if the field is editable.
     */
    public function __construct($name, $editable)
    {
        parent::__construct($name, $editable);
    }

    /**
     * The value read from the context is interpreted as integer.
     */
    public function readValue($context)
    {
        return (int)$context->getArg($this->getName());
    }
}
