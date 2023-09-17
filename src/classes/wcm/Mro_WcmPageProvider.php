<?php

namespace Maestro\wcm;

use Maestro\conductor\Mro_Page;
use Maestro\conductor\Mro_PageProvider;

/**
 * A WCM aware page provider. If a page is requested for a WCM document is not found, the default WCM page will be returned.
 */
class Mro_WcmPageProvider implements Mro_PageProvider
{

    private $provider;

    function __construct($provider)
    {
        $this->provider = $provider;
    }

    function get(string $pageId): Mro_Page
    {
        if (str_contains($pageId, 'wcm.')) {
            return $this->getWcm($pageId, $this->provider);
        }
        return $this->provider->get($pageId);
    }

    function getErrorPage(): Mro_Page
    {
        return $this->provider->getErrorPage();
    }

    function getDefaultPage(): Mro_Page
    {
        return $this->provider->getDefaultPage();
    }

    private function getWcm($pageName, $provider)
    {
        $page = $provider->get($pageName);
        if (is_null($page)) {
            // return the default page
            return $provider->get('wcm');
        }
        return $page;
    }
}