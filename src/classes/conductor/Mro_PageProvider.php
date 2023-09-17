<?php

namespace Maestro\conductor;

/**
 * Interface for page providers.
 * Page providers are used by the conductor to get pages by id.
 */
interface Mro_PageProvider
{

    /**
     * Gets a page on id.
     * @param string $pageId The unique id of the page.
     * @return Mro_Page
     */
    function get(string $pageId): ?Mro_Page;

    /**
     * Gets the error page.
     * @return Mro_Page
     */
    function getErrorPage(): Mro_Page;

    /**
     * Gets the default page.
     * @return Mro_Page
     */
    function getDefaultPage(): Mro_Page;
}
