<?php

namespace Maestro\crud;

/**
 * A list operation lists the elements of a DAO.
 */
interface Mro_CrudData {

    /**
     * Outputs the HTML code for this crud data.
     */
    public function createHtml();
}
