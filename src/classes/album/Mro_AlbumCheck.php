<?php

namespace Maestro\album;

use Maestro\conductor\Mro_Context;
use Maestro\db\Mro_DaoManager;
use Maestro\logic\Mro_Logic;
use Monolog\Registry;
use Psr\Log\LoggerInterface;

/**
 * Logic that lists the folders in a specific folder. Each folder corresponds
 * to a fotoalbum.
 */
class Mro_AlbumCheck implements Mro_Logic
{

    private LoggerInterface $log;

    private $source;

    private $server;

    /**
     * Default constructor.
     * @param string $source The folders habitating the foto albums.
     * @param string $server The name of the maestro server.
     */
    function __construct($source, $server)
    {
        $this->source = $source;
        $this->log = Registry::getInstance('album');
        $this->server = $server;
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
    function execute(Mro_Context $context): void
    {
        $this->log->debug('Searching albums' , ['source' => $this->source]);
        $files = scandir($this->source);
        foreach ($files as $fileName) {
            if (!($fileName == '.' or $fileName == '..')) {
                $filePath = $this->source . '/' . $fileName;
                $fileCreationTime = filectime($filePath);
            }
        }
    }
}

