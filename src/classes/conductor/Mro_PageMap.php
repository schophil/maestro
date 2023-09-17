<?php

namespace Maestro\conductor;

/**
 * Page provider implemented as map.
 */
class Mro_PageMap implements Mro_PageProvider
{

    private array $pages = [];

    /**
     * The default constructor.
     */
    function __construct()
    {
    }

    /**
     * Adds an action to the action map.
     * @param Mro_Page $page A page to register.
     */
    function put(Mro_Page $page): void
    {
        $pageName = $page->name;
        $this->pages[$pageName] = $page;
    }

    /**
     * Gets a page by name.
     * @param string $pageId The name of the page.
     * @return Mro_Page
     */
    function get(string $pageId): ?Mro_Page
    {
        return $this->pages[$pageId];
    }

    /**
     * Gets the default page.
     * @return Mro_Page
     */
    function getDefaultPage(): Mro_Page
    {
        return $this->get('default');
    }

    /**
     * Gets the error page.
     * @return Mro_Page
     */
    function getErrorPage(): Mro_Page
    {
        return $this->get('error');
    }

    /**
     * Sets the default page.
     * @param Mro_Page $page The default page.
     */
    function setDefaultPage(Mro_Page $page): void
    {
        $this->pages['default'] = $page;
    }

    /**
     * Sets the error page.
     * @param Mro_Page $page The error page.
     */
    function setErrorPage(Mro_Page $page): void
    {
        $this->pages['error'] = $page;
    }
}
