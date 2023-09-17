<?php

namespace Maestro\conductor;

/**
 * A simple page provider implementation using an array.
 */
class Mro_DefaultPageProvider implements Mro_PageProvider
{

    private $pages;

    /**
     * Default constructor.
     * @param array $pages An array of Mro_Page instances.
     */
    public function __construct(&$pages)
    {
        $this->pages = $pages;
    }

    /**
     * Gets a page on id.
     * @param string $pageId The unique id of the page.
     * @return Mro_Page
     */
    function get($pageId): Mro_Page
    {
        return $this->pages[$pageId];
    }

    /**
     * Gets the error page.
     * @return Mro_Page
     */
    public function getErrorPage(): Mro_Page
    {
        return $this->get('error');
    }

    /**
     * Gets the default page.
     * @return Mro_Page
     */
    public function getDefaultPage(): Mro_Page
    {
        return $this->get('default');
    }
}
