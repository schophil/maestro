<?php

namespace Maestro\logic;

/**
 * Utility to registered logic for names.
 * The complete logic can be retrieved under the form of a composite logic.
 */
class Mro_LogicComposer
{

    private $map;

    function __construct()
    {
        $this->map = array();
    }

    function registerCommon(Mro_Logic $logic): void
    {
        $this->register('*', $logic);
    }

    /**
     * Registers a logic for a name.
     * @param string $name
     * @param Mro_Logic $logic
     */
    function register(string $name, Mro_Logic $logic): void
    {
        if (!isset($this->map[$name])) {
            $this->map[$name] = array($logic);
        } else {
            // array's are not passed by reference!xs
            $composite =& $this->map[$name];
            $composite[] = $logic;
        }
    }

    /**
     * Composes and returns the composite logic for the given name. If a name was not registered,
     * the common logic (if any) will be used to construct a composite logic.
     * @param string $name The name that was used to register the wanted logic.
     * @return Mro_CompositeLogic
     */
    function compose(string $name): Mro_CompositeLogic
    {
        // create composite logic
        $composite = new Mro_CompositeLogic();

        // add registered if any
        if (isset($this->map[$name])) {
            $registered =& $this->map[$name];
            foreach ($registered as $logic) {
                $composite->add($logic);
            }
        }

        // add common logic if necessary
        if ($name != '*' and isset($this->map['*'])) {
            $registered =& $this->map['*'];
            foreach ($registered as $logic) {
                $composite->add($logic);
            }
        }

        return $composite;
    }
}
