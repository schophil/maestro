<?php

namespace Maestro\agenda;

use Maestro\db\Mro_DaoWrapper;

/**
 * Represents a single agenda item.
 */
class Mro_Event extends Mro_DaoWrapper
{

    /**
     * Returns the start date of this event.
     */
    function getFromDate()
    {
        return $this->get('fdate');
    }

    /**
     * Returns the end date of this event.
     */
    function getToDate()
    {
        return $this->get('tdate');
    }

    /**
     * Returns the XHTML description of this event.
     */
    function getContent()
    {
        return $this->get('content');
    }

    /**
     * Returns the title of this event.
     */
    function getTitle()
    {
        return $this->get('title');
    }

    /**
     * Returns true if this event is an activity.
     */
    function isActivity()
    {
        return $this->get('activity') === 'yes';
    }
}
