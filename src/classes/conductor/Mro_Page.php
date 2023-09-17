<?php

namespace Maestro\conductor;

/**
 * Interface to all page objects.
 */
interface Mro_Page {

    /**
     * Display this page with data from the context.
     * @param Mro_Context $context The context containing data.
     */
    function show(Mro_Context $context);
}