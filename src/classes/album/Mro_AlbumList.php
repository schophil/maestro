<?php

namespace Maestro\album;

use Maestro\db\Mro_DaoManager;
use Maestro\logic\Mro_Logic;
use Monolog\Registry;

/**
 * Logic that lists the folders in a specific folder. Each folder corresponds
 * to a fotoalbum.
 */
class Mro_AlbumList implements Mro_Logic
{

    private $log;
    private $source;
    private $viewAction;

    /**
     * Default constructor.
     * @param string $source The folders habitating the foto albums.
     * @param string $viewAction The view action to execute (default: viewalbum).
     */
    function __construct(string $source, $viewAction = 'viewalbum')
    {
        $this->source = $source;
        $this->viewAction = $viewAction;
        $this->log =  Registry::getInstance('album');
    }

    /**
     * Indicates if the logic needs access to a database.
     * @return boolean
     */
    function needsPersistency(): bool
    {
        return false;
    }

    /**
     * Sets the persistency.
     * @param Mro_DaoManager $daoManager The dao manager to use for loading and writing dat
     */
    function setPersistency(Mro_DaoManager $daoManager): void
    {
        // no need for persistency
    }

    /**
     * Indicates if the logic needs a transaction.
     * @return boolean
     */
    function needsTransaction(): bool
    {
        return false;
    }

    /**
     * Executes the logic with the given context.
     * @param Mro_Context $context The context containing all needed parameters.
     */
    function execute($context): void
    {
        $this->log->debug("searching albums in {$this->source}");

        // initiate new files array
        $files = array();

        // scan and iterate dir
        $content = scandir($this->source);
        foreach ($content as $name) {
            if (!($name == '.' or $name == '..')) {
                $fileName = str_replace("_", " ", $name);
                $url = "index.php?action={$this->viewAction}&album={$name}";
                $files[] = new Mro_Album($name, $fileName, $url);
            }
        }

        // set the files on the context
        $context->setPara('albums', $files);
    }
}
