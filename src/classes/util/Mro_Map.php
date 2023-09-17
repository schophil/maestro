<?php
/**
 * @author Philippe Schottey <pschotte@mac.com>
 * @package util
 */

namespace Maestro\util;

/**
 * Class wrapping an array to be handled as a map.
 */
class Mro_Map
{

    private $data;

    /**
     * The default constructor.
     */
    function __construct()
    {
        $this->data = array();
    }

    /**
     * Adds an object to the map.
     * @param string $key The unique identifier of the data in the map.
     * @param undefined $data The data to save.
     */
    function put($key, $data)
    {
        $this->data[$key] = $data;
    }

    /**
     * Returns the data contained in the map for a given key.
     * @param string key Unique identifier of the data.
     * @return undefined The data contained in the map.
     */
    function get($key)
    {
        return $this->data[$key];
    }

    /**
     *
     */
    function getData()
    {
        return $this->data;
    }
}
