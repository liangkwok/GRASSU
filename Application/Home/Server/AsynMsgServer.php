<?php

require_once 'DbMysqli.php';
require_once 'MsgServer.class.php';
require_once ( "/root/grassu/Server/Baidu-Push-SDK-Php-2.0.4-advanced/Channel.class.php" ) ;

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
$GLOBALS['G_MsgType'] = array(
		//用户纬度
			'USER_FELLOW'=>101,//用户关注
			'CANCEL_FELLOW'=>102,//用户关注
			//合拍维度
			'VWEIBO_PUBLISH'=>111,//发布合拍
			'VWEIBO_ATLIST'=>112,//合拍AT
			'VWEIBO_DELETE'=>113,//合拍AT
			//喜欢纬度
			'PRAISE_VWEIBO'=>121,//喜欢合拍
			//评论纬度
			'COMMENT_VWEIBO'=>131,//评论合拍
			'COMMENT_ATLIST'=>132,//评论at
			//转发纬度
			'FOWARD_VWEIBO'=>141,//转发合拍		
			//合奏维度
			'ENSEMBLE_VWEIBO'=>151,//视频合奏
			//话题维度
			'TOPIC_ADD'=>161,//话题创建
			'TOPIC_RSS'=>162,//话题订阅
);


function main(){	
	date_default_timezone_set('Asia/Shanghai');
	$grassuDB = DbMysqli::getInstance("grassu");
	$msgServer = AsynMsgServer::GetInstance($grassuDB);
	$msgServer->Run();	
}

main();