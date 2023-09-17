<?php
// Persistency configuration.
//

use Maestro\db\Mro_DateTimeType;
use Maestro\db\Mro_IntegerType;
use Maestro\db\Mro_ListType;
use Maestro\db\Mro_StringType;
use Maestro\db\Mro_XhtmlType;

global $connectionData, $daoConfig;

// The database connection info. 
// The variable is named $connectionInfo
$connectionData->setUser($_SERVER['db_user']);	
$connectionData->setPassword($_SERVER['db_pwd']);
$connectionData->setDatabase($_SERVER['db_name']);
$connectionData->setHost($_SERVER['db_host']);
$connectionData->setProduct('mysql');

// The dao configuration.
// The variable is named $daoConfig
// documents
$dao = $daoConfig->addDao('document')->setClassName('Maestro\wcm\Mro_Document');
$dao->listWith('select id from document order by id desc');
$dao->insertWith('insert into document values ({id},{uc},now(),null,null,null,null,null)');
$dao->deleteWith('delete from document where id = {id} and uc = {uc}');
$dao->updateWith('update document set title = {title}, content = {content}, uc = {new_uc}, ts = now(), abstract = {abstract}, class = {classification}, name = {name} where id = {id} and uc = {uc}');
$dao->loadWith('select * from document where id = {id}');
$dao->addSearchQuery('byname', 'select id from document where name = {name}');
$dao->addSearchQuery('byclass', 'select id from document where class = {classification}');
$dao->addField('id', 1, new Mro_StringType())->disableQuery()->disableEdit();
$dao->addField('uc', 2, new Mro_IntegerType())->disableQuery()->disableEdit();
$dao->addField('ts', 3, new Mro_DateTimeType())->disableQuery()->disableEdit();
$dao->addField('name', 4, new Mro_StringType(10));
$dao->addField('classification', 5, new Mro_ListType(array('default', 'agenda')));
$dao->addField('title', 6, new Mro_StringType(200));
$dao->addField('abstract', 7, new Mro_XhtmlType());
$dao->addField('content', 8, new Mro_XhtmlType());
// events
$dao = $daoConfig->addDao('event')->setClassName('Maestro\wcm\Mro_Event');
$dao->listWith('select id from event order by id desc');
$dao->insertWith('insert into event values ({id},{uc},now(),null,null,null,null,null)');
$dao->deleteWith('delete from event where id = {id} and uc = {uc}');
$dao->updateWith('update event set title = {title}, content = {content}, uc = {new_uc}, tdate = {tdate}, fdate = {fdate}, type = {type}, activity = {activity} where id = {id} and uc = {uc}');
$dao->loadWith('select * from event where id = {id}');
$dao->addSearchQuery('types', 'select id, fdate from event where type in {types} [and activity = {activity}] order by fdate asc');
$dao->addSearchQuery('next.activity', 'select id, fdate from event where (fdate >= {date} or (fdate <= {date} and {date} <= tdate)) and (type in {types}) and activity = \'yes\' order by fdate asc');
$dao->addField('id', 1, new Mro_StringType())->disableQuery()->disableEdit();
$dao->addField('uc', 2, new Mro_IntegerType())->disableQuery()->disableEdit();
$dao->addField('fdate', 3, new Mro_DateTimeType())->disableQuery();
$dao->addField('tdate', 4, new Mro_DateTimeType())->disableQuery();
$dao->addField('title', 5, new Mro_StringType(200))->disableQuery();
$dao->addField('content', 6, new Mro_XhtmlType())->disableQuery();
$dao->addField('type', 7, new Mro_ListType(array('evenement', 'optreden')))->disableQuery();
$dao->addField('activity', 8, new Mro_ListType(array('yes', 'no')))->disableQuery();
