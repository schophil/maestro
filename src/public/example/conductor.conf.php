<?php
// Conductor configuration. (actions, pages and security)
//

use Maestro\agenda\Mro_NextActivity;
use Maestro\agenda\Mro_ReadEvents;
use Maestro\album\Mro_AlbumList;
use Maestro\album\Mro_AlbumView;
use Maestro\logic\Mro_LogicComposer;
use Maestro\Mro_Maestro;

global $actionConfig, $pageConfig;

$logicMatrix = new Mro_LogicComposer();
$logicMatrix->register('home', new Mro_NextActivity(array('evenement', 'optreden'), 'activiteit'));
$logicMatrix->register('agenda', new Mro_ReadEvents(array('evenement'), 'yes', 'evenementen'));
$logicMatrix->register('agenda', new Mro_ReadEvents(array('optreden'), 'yes', 'optredens'));

$actionConfig->setDefaultAction($actionConfig->addWcmAction($logicMatrix, 'home'));
$actionConfig->addSimpleAction('fotos', new Mro_AlbumList(Mro_Maestro::getSitePath('images/albums')));
$actionConfig->addSimpleAction('viewalbum', new Mro_AlbumView(Mro_Maestro::getSitePath('images/albums')));
$actionConfig->addCrudAction('crud')->secure();

// Configuring the pages.
// Variable: $pageConfig
$pageConfig->addIncludedPage('wcm.home', Mro_Maestro::getSitePath('/home.php'));
$pageConfig->addIncludedPage('wcm.agenda', Mro_Maestro::getSitePath('/agenda.php'));
$pageConfig->addIncludedPage('fotos', Mro_Maestro::getSitePath('/fotos.php'));
$pageConfig->addIncludedPage('viewalbum', Mro_Maestro::getSitePath('/fotos.php'));
$pageConfig->addIncludedPage('wcm', Mro_Maestro::getSitePath('/wcm.php'));
$pageConfig->addIncludedPage('crud', Mro_Maestro::getSitePath('/crud.php'));
$pageConfig->setErrorPage($pageConfig->addIncludedPage('error', Mro_Maestro::getSitePath('/error.php')));

// security
$users['root'] = $_SERVER['root_pwd'];
