<?php

namespace Maestro\crud;

use Maestro\html\Mro_MaestroUri;

class Mro_CrudUri extends Mro_MaestroUri
{

    /**
     * Constructor.
     */
    function __construct($crudActionName, $operation, $type = null, $id = null)
    {
        parent::__construct($crudActionName);

        $this->addPara('operation', $operation);
        if (!is_null($type)) {
            $this->addPara('daotype', $type);
        }
        if (!is_null($id)) {
            $this->addPara('daoid', $id);
        }
    }
}
