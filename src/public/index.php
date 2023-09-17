<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../private/globals.php';

use Maestro\conductor\Mro_ActionConfig;
use Maestro\conductor\Mro_ActionLogger;
use Maestro\conductor\Mro_Conductor;
use Maestro\conductor\Mro_PageConfig;
use Maestro\db\Mro_ConnectionData;
use Maestro\db\Mro_DaoConfig;
use Maestro\logic\Mro_DefaultPersistencyProvider;
use Maestro\logic\Mro_PersistencyHandler;
use Maestro\logic\Mro_SimpleExecuteHandler;
use Maestro\security\Mro_SimpleSecurityHandler;
use Maestro\wcm\Mro_WcmPageProvider;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Registry;
use Dotenv\Dotenv;
use Maestro\Mro_Maestro;
use Monolog\Handler\RotatingFileHandler;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

//
// Set up logging.
//
$handlers = [
    new RotatingFileHandler(__DIR__ . '/../../logs/maestro.log', 10, Level::Debug)
];
Registry::addLogger(new Logger('conductor', $handlers));
Registry::addLogger(new Logger('db', $handlers));
Registry::addLogger(new Logger('security', $handlers));
Registry::addLogger(new Logger('album', $handlers));
Registry::addLogger(new Logger('logic', $handlers));
Registry::addLogger(new Logger('crud', $handlers));
Registry::addLogger(new Logger('html', $handlers));

// 
// Build the action provider and page provider.
// 
// configure actions and pages configuration
$actionConfig = new Mro_ActionConfig();
$pageConfig = new Mro_PageConfig();
$users = array();
require(Mro_Maestro::getSitePath('conductor.conf.php'));

$pageProvider = new Mro_WcmPageProvider($pageConfig);

//
// Create action processor.
//
$actionProcessor = new Mro_SimpleSecurityHandler($users, 'base', new Mro_ActionLogger());

//
// Create logic processor.
// 
// configure persistency
$daoConfig = new Mro_DaoConfig();
$connectionData = new Mro_ConnectionData();
require(Mro_Maestro::getSitePath('persistency.conf.php'));
$persistencyProvider = new Mro_DefaultPersistencyProvider($daoConfig, $connectionData);

$logicProcessor = new Mro_PersistencyHandler($persistencyProvider, new Mro_SimpleExecuteHandler(null));

// 
// Create and start conductor.
//
$conductor = new Mro_Conductor($actionConfig, $pageProvider, $logicProcessor, $actionProcessor);
$conductor->start();
