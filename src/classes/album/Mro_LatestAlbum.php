<?php

namespace Maestro\album;

use Maestro\db\Mro_DaoManager;
use Maestro\logic\Mro_Logic;
use Monolog\Registry;

/**
 * Seachers the album directory.
 */
class Mro_LatestAlbum implements Mro_Logic
{

    private $source;
    private $variable;
    private $age;
    private $log;

    /**
     * Default constructor.
     * @param string $source The source directory.
     * @return unknown_type
     */
    function __construct($source, $variable = 'album', $age = null)
    {
        $this->log = Registry::getInstance('album');
        $this->source = $source;
        $this->variable = $variable;
        if (is_null($age)) {
            $this->age = 14 * 24 * 60 * 60;
        } else {
            $this->age = $age;
        }
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
     * Finds the latest album.
     * @param $context
     * @return unknown_type
     */
    public function execute($context): void
    {
        $now = time();
        $range = $this->age;
        $latest = null;

        $content = scandir($this->source);
        foreach ($content as $name) {
            if ($name != '.' && $name != '..' && $name != '.DS_Store') {
                $path = $this->source . '/' . $name;
                $this->log->debug("checking timestamp of {$path}");
                $mtime = filemtime($path);

                $diff = $now - $mtime;
                if ($diff < $range) {
                    $latest = $name;
                    break;
                }
            }
        }

        $this->log->debug("latest album {$latest}");

        if (!is_null($latest)) {
            $fileName = str_replace("_", " ", $latest);
            $url = "serve.php?action=viewalbum&album={$latest}";
            $album = new Mro_Album($latest, $fileName, $url);
            $context->setPara($this->variable, $album);
        }
    }
}
