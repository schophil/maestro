<?php

namespace Maestro\album;

use Maestro\db\Mro_DaoManager;
use Maestro\logic\Mro_Logic;
use Maestro\Mro_Maestro;
use Monolog\Registry;
use Psr\Log\LoggerInterface;

/**
 * Logic that lists the folders in a specific folder. Each folder corresponds
 * to a fotoalbum.
 */
class Mro_AlbumView implements Mro_Logic
{

    private LoggerInterface $log;
    private string $source;
    private $album;

    /**
     * Default constructor.
     * @param string $source The folders habitating the foto albums.
     */
    public function __construct($source, $album = NULL)
    {
        $this->source = $source;
        $this->album = $album;
        $this->log = Registry::getInstance('album');
    }

    /**
     * Indicates if the logic needs access to a database.
     * @return boolean
     */
    public function needsPersistency(): bool
    {
        return false;
    }

    /**
     * Sets the persistency.
     * @param Mro_DaoManager $daoManager The dao manager to use for loading and writing dat
     */
    public function setPersistency(Mro_DaoManager $daoManager): void
    {
        // no need for persistency
    }

    /**
     * Indicates if the logic needs a transaction.
     * @return boolean
     */
    public function needsTransaction(): bool
    {
        return false;
    }

    /**
     * Executes the logic with the given context.
     * @param Mro_Context $context The context containing all needed parameters.
     */
    public function execute($context): void
    {
        $albumId = $this->album;
        if (!isset($albumId)) {
            $albumId = $context->getArg('album');
            $albumId = preg_replace('/[\.\/\\\\]/', '', $albumId);
        }
        // set the album name
        $albumName = str_replace("_", " ", $albumId);
        $context->setPara('album', $albumName);
        
        $album = new Mro_Album($albumId, $albumName, '');

        // initialized array
        $files = array();
        $base = Mro_Maestro::getPublicBase();

        // read the images
        $dir = $this->source . "/{$albumId}";
        if ($handle = opendir($dir)) {
            $this->log->debug("Reading the directory... $dir");
            while (false !== ($file = readdir($handle))) {
                $fileId = "$file";
                if ($fileId !== "." && $fileId !== "..") {
                    $url = $this->source . "/{$albumId}/{$fileId}";
                    $this->log->debug("Creating url for {$url} with base {$base}");
                    $url = str_replace($base, '', $url); 
                    $files[] = $url;
                    $this->log->debug("found handle to {$url}");
                }
            }
            // sort the array to be sure
            sort($files, SORT_STRING);

            // make album instances
            $images = array();
            foreach ($files as $value) {
                $images[] = new Mro_AlbumImage($album, $value);
            }

            $context->setPara('images', $images);
        } else {
            $this->log->error("could not get handle to directory");
        }
    }
}
