<?php

namespace Maestro\conductor;

use Maestro\logic\Mro_Logic;

/**
 * The mro page model.
 * A page corresponds to a physical pahe (html, php...) and optionally some logic
 * that should be executed before rendering the page.
 */
class Mro_IncludedPage implements Mro_Page
{

    /**
     * @var string $url The source/location of the page.
     */
    public string $url;

    /**
     * @var Mro_Logic $logic Some pre-load page logic.
     */
    public ?Mro_Logic $logic;

    /**
     * @var string $name Unique name of the page.
     */
    public string $name;

    /**
     * Default constructor.
     * @param string $name Name of the page.
     * @param string $url Source of the page.
     * @param Mro_Logic $logic Pre-load page logic.
     */
    function __construct($name, $url, $logic)
    {
        $this->url = $url;
        $this->logic = $logic;
        $this->name = $name;
    }

    /**
     * Show the current page by including it like any other PHP file.
     * @param the context.
     */
    function show(Mro_Context $context)
    {
        $file = $this->url;
        include $file;
    }
}