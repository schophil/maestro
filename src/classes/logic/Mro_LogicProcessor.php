<?php

namespace Maestro\logic;

use Maestro\conductor\Mro_Context;

interface Mro_LogicProcessor {

    /**
     * Handle the given logic.
     * @param Mro_Logic $logic The logic class to handle.
     * @param Mro_Context $context The context in which to execute the logic.
     */
    function handle(Mro_Logic $logic, Mro_Context $context): void;

    /**
     * Clean up after handling the logic.
     */
    function cleanUp(): void;
}
