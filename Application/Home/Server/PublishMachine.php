<?php

require_once 'DbMysqli.php';
require_once 'PublishServer.class.php';

$GLOBALS['g_db'] = array(
		'grassu'=>array(
				"host"=>'127.0.0.1',
				"name"=>'grassu',
				"user"=>'grassu',
				"pass"=>'grassu',
				"port"=>'3306',
				'charset'=>"utf8"
		)
);

function main(){	
	date_default_timezone_set('Asia/Shanghai');
	$grassuDB = DbMysqli::getInstance("grassu");
	$pubServer = PublishServer::GetInstance($grassuDB);
    $pubServer->Run();
}

main();