<?php

namespace Maestro\html;

class Mro_VerticalFormField extends Mro_Div
{

    function __construct($input, $label = null)
    {
        parent::__construct();
        $this->addClass('mroVerticalField');

        if (!is_null($label)) {
            $labelPart = new Mro_Div();
            $labelPart->addClass('mroFieldLabel');
            $labelPart->add($label);
            $this->add($labelPart);
        }

        $inputPart = new Mro_Div();
        $inputPart->addClass('mroFieldInput');
        $inputPart->add($input);
        $this->add($inputPart);
    }
}
