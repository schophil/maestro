<?php

namespace Maestro\album;

/**
 * Class representing an image from an album.
 */
class Mro_AlbumImage
{

    private string $url;

    private Mro_Album $album;

    /**
     * Default constructor.
     * @param string $source The folders habitating the foto albums.
     */
    public function __construct(Mro_Album $album, string $url)
    {
        $this->album = $album;
        $this->url = $url;
    }

    /**
     * Returns the name of the album.
     * @return Mro_Album The name of the album.
     */
    public function getAlbum(): Mro_Album
    {
        return $this->album;
    }

    /**
     * Returns the url of the image.
     * @return string The url of the image.
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}