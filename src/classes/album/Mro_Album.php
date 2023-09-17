<?php

namespace Maestro\album;

/**
 * Class representing an album.
 */
class Mro_Album
{

    private $name;
    private $id;
    private $url;

    /**
     * Default constructor.
     * @param string $id The id of the album
     * @param string $name The name of the album
     * @param string $url The (http) url to view the album.
     */
    public function __construct($id, $name, $url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * Returns the name of the album.
     * @return string The name of the album.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the ID.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the url to view this album.
     */
    public function getUrl()
    {
        return $this->url;
    }
}
