<?php
return array(
	//'配置项'=>'配置值'
		/* 数据库设置 */
		'DB_TYPE'               =>  'mysql',     // 数据库类型
		'DB_HOST'               =>  '127.0.0.1', // 服务器地址
		'DB_NAME'               =>  'grassu',          // 数据库名
		'DB_USER'               =>  'grassu',      // 用户名
		'DB_PWD'                =>  'grassu',          // 密码
		'DB_PORT'               =>  '3306',        // 端口
		'DB_PREFIX'             =>  '',    // 数据库表前缀
		'DB_FIELDTYPE_CHECK'    =>  false,       // 是否进行字段类型检查
		'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
		'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
		'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
		'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
		'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
		'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
		'DB_SQL_BUILD_CACHE'    =>  false, // 数据库查询的SQL创建缓存
		'DB_SQL_BUILD_QUEUE'    =>  'file',   // SQL缓存队列的缓存方式 支持 file xcache和apc
		'DB_SQL_BUILD_LENGTH'   =>  20, // SQL缓存的队列长度
		'DB_SQL_LOG'            =>  false, // SQL执行日志记录
		'DB_BIND_PARAM'         =>  false, // 数据库写入数据自动参数绑定
		
		/* 数据缓存设置 */
		'DATA_CACHE_TIME'       =>  1,      // 数据缓存有效期 0表示永久缓存
		'DATA_CACHE_COMPRESS'   =>  false,   // 数据缓存是否压缩缓存
		'DATA_CACHE_CHECK'      =>  false,   // 数据缓存是否校验缓存
		'DATA_CACHE_PREFIX'     =>  '',     // 缓存前缀
		'DATA_CACHE_TYPE'       =>  'File',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
		'DATA_CACHE_PATH'       =>  TEMP_PATH,// 缓存路径设置 (仅对File方式缓存有效)
		'DATA_CACHE_SUBDIR'     =>  false,    // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
		'DATA_PATH_LEVEL'       =>  1,        // 子目录缓存级别
		
		/*草地音乐配置*/
		'GRASSUA_PRIVITE_KEY'=>'!@#$%^&*()@grassu',
		'PLATFORM_TYPE_IOS' => '1001',
		'PLATFORM_TYPE_ANDROID' => '1002',
		'PLATFORM_TYPE_WINPHONE' => '1003',
		'PLATFORM_TYPE_WEB' => '1004',
		'PLATFORM_TYPE_WAP' => '1005',
		'PLATFORM_TYPE_WEB' => '1006',
		
		
		/*注册完毕，默认被关注列表*/
		'GRASSUA_USERS_FELLOWED'=>'10087,10088,10100',
		
		/*注册完毕，默认关注别人列表*/
		'GRASSUA_USERS_FELLOWS'=>'10089',
		
		/* 错误编码设置 */		
		'ERROR_CODE'=>array(
			/*公共错误*/
			'SUCCESS'=>array(0,"操作成功"),
			'ERR_UNKNOWN'=>array(101,"系统未知错误"),
			'ERR_UNDEFINED'=>array(102,"系统未定义错误"),
			'ERR_FAILED'=>array(103,"操作失败"),
			'ERR_CONTENT_ILLEGAL'=>array(104,"有不适合发布的内容哦"),
				
			/*参数错误*/
			'ERR_PARAM'=>array(1000,"参数错误"),
			'ERR_PARAM_ILLEGAL'=>array(1001,"请求参数不合法"),				
			'ERR_PARAM_LOST'=>array(1002,"请求参数丢失"),
			'ERR_LOAD_FAIL'=>array(1003,"数据加载失败"),
			'ERR_LIST_NULL'=>array(1004,"列表数据为空"),
			'ERR_LIST_FAIL'=>array(1005,"列表数据加载失败"),
			
				
			/*首页模块错误码定义*/
			'ERR_INDEX_FAIL'=>array(10100,"首页加载失败"),
			'ERR_INDEX_NULL'=>array(10101,"首页列表数据为空"),
			'ERR_INDEX_FAIL'=>array(10102,"首页列表数据加载失败"),
				
				
			/*频道*/
			'ERR_CHANNELS_NULL' => array(10200,"频道列表为空"),
			'ERR_CHANNELS_FAIL' => array(10201,"加载频道列表失败"),
				
			/*城市*/
			'ERR_CITYS_NULL' => array(10300,"城市列表为空"),
			'ERR_CITYS_FAIL' => array(10301,"加载城市列表失败"),
			/*注册模块错误码定义*/
			'ERR_REG_FAIL'=>array(10400,"用户注册失败"),
			'ERR_REG_MOBILE_EXIST'=>array(10401,"该手机号已经注册"),
			'ERR_REG_EMAIL_EXIST'=>array(10402,"该邮箱已经存在"),
			'ERR_REG_UNICK_EXIST'=>array(10403,"该昵称已经存在"),
			
			/*用户模块错误码定义*/
			'ERR_LOGIN_FAIL'=>array(10500,"用户登录失败"),
			'ERR_LOGIN_NOT'=>array(10501,"用户未登录"),
			'ERR_LOGIN_USER_UNEXIST'=>array(10502,"该用户不存在"),
			'ERR_USER_UNEXIST'=>array(10505,"该用户不存在"),
			'ERR_USER_QUERY_FAIL'=>array(10506,"查询用户信息失败"),
			'ERR_REG_UNICK_EXIST'=>array(10507,"用户名已经存在"),
			'ERR_UPDATE_FAIL'=>array(10508,"更新资料失败"),
			'ERR_UPDATE_PWD_FAIL'=>array(10509,"更新密码失败"),
			'ERR_UPDATE_PWD_WRONG'=>array(10510,"原始密码错误"),
			'ERR_LOGOUT_FAIL'=>array(10511,"用户退出失败"),
			'ERR_LOGOUT_USER_UNEXIST'=>array(10512,"用户不存在"),
			'ERR_LOGIN_PASSWORD_FAIL'=>array(10513,"用户密码错误"),
			
			
				
			/*发布合拍*/
			'ERR_VWEIBO_PUBLISH_FAIL'=>array(10600,"发布合拍失败"),
			'ERR_VWEIBO_NO_AUTHORITY'=>array(10601,"无权限删除合拍"),
			'ERR_VWEIBO_DELETE_FAIL'=>array(10601,"删除合拍失败"),
				
			/*喜欢合拍*/
			'ERR_PRAISE_SUCCESS'=>array(10700,"用户喜欢成功"),
			'ERR_PRAISE_FAIL'=>array(10701,"用户喜欢失败"),
			'ERR_PRAISE_NO_AUTHORITY'=>array(10702,"用户无权限喜欢"),			
			'ERR_PRAISE_LIST_FAIL'=>array(10703,"获取用户喜欢的合拍列表失败"),
			'ERR_PRAISED_LIST_FAIL'=>array(10704,"获取合拍喜欢的用户列表失败"),
				
				
			/*转发合拍*/
			'ERR_FOWARD_SUCCESS'=>array(10800,"用户转发成功"),
			'ERR_FOWARD_FAIL'=>array(10801,"用户转发失败"),
			'ERR_FOWARD_NO_AUTHORITY'=>array(10802,"用户无权限转发"),
			'ERR_FOWARD_LIST_FAIL'=>array(10803,"获取用户转发的合拍列表失败"),
			'ERR_FOWARDED_LIST_FAIL'=>array(10804,"获取合拍转发的用户列表失败"),
				
			/*评论合拍*/
			'ERR_COMMENT_SUCCESS'=>array(10900,"用户评论功"),
			'ERR_COMMENT_FAIL'=>array(10901,"用户评论失败"),
			'ERR_COMMENT_NO_AUTHORITY'=>array(10902,"用户无权限评论"),
			'ERR_COMMENT_LIST_FAIL'=>array(10903,"获取用户评论的合拍列表失败"),
			'ERR_COMMENTED_LIST_FAIL'=>array(10904,"获取合拍评论的用户列表失败"),
				
			/*获取合拍裂表失败*/
			'ERR_LIST_FAIL'=>array(10905,"获取合拍评论的用户列表失败"),
				
			/*合奏合拍*/
			'ERR_ENSEMBLE_SUCCESS'=>array(11000,"用户合奏成功"),
			'ERR_ENSEMBLE_FAIL'=>array(11001,"用户合奏失败"),
			'ERR_ENSEMBLE_NO_AUTHORITY'=>array(11002,"用户无权限合奏"),
			'ERR_ENSEMBLE_LIST_FAIL'=>array(11003,"获取用户合奏的合拍列表失败"),
			'ERR_ENSEMBLE_LIST_FAIL'=>array(11004,"获取合拍合奏的用户列表失败"),
				
			/*话题*/
			'ERR_TOPIC_ADD_FAIL'=>array(11101,"创建话题失败"),
			'ERR_TOPIC_RSS_FAIL'=>array(11102,"订阅话题失败"),
			'ERR_TOPIC_LIST_FAIL'=>array(11103,"获取话题列表失败"),
			'ERR_TOPIC_LIST_NULL'=>array(11104,"话题列表为空"),
			'ERR_TOPIC_HOT_FAIL'=>array(11105,"最热话题失败"),
			'ERR_TOPIC_NEW_FAIL'=>array(11106,"最热话题失败"),
				
			/*关注*/
			'ERR_FELLOW_LIST_FAIL'=>array(11201,"获取关注列表失败"),
			'ERR_FELLOW_LIST_NULL'=>array(11202,"获取关注列表为空"),
			'ERR_FRIENDS_LIST_FAIL'=>array(11203,"获取好友列表失败"),
			'ERR_FRIENDS_LIST_NULL'=>array(11204,"获取好友列表为空"),
				
			/*消息*/
			'ERR_MSG_LIST_FAIL'=>array(11301,"获取消息列表失败"),
			'ERR_MSG_LIST_NULL'=>array(11302,"获取消息列表为空"),
				
			/*关注*/
			'ERR_EXPLORE_LIST_FAIL'=>array(11301,"获取探索列表失败"),
			'ERR_EXPLORE_LIST_NULL'=>array(11302,"获取探索列表为空"),
			
				
			/*设置*/
			'ERR_SET_LIST_FAIL' =>array(11401,"获取设置失败"),
			'ERR_SET_UPDATE_FAIL' =>array(11402,"更新设置失败"),

				
		),
		
		//合拍相关宏定义
		'VWEIBO'=>array(
			'VWEIBO_VW_DEFAULT_PICTURE'=>0,
			'VWEIBO_VW_DEFAULT_PAGENUM'=>5,
			'VWEIBO_VW_DEFAULT_RECOMMEND'=>1,
			'VWEIBO_VW_DEFAULT_LOCATION'=>'',
			'VWEIBO_VW_DEFAULT_LATITUDE'=>0,
			'VWEIBO_VW_DEFAULT_LONGITUDE'=>0,
			'VWEIBO_VW_DEFAULT_CHANNELID'=>0,
			'VWEIBO_VW_DEFAULT_AUTHORITY'=>0,
			'VWEIBO_VW_DEFAULT_CONTENT'=>'',			
		),
		//喜欢相关宏定义
		'PRAISE'=>array(
				'PRAISE_STATUS_ON'=>1,
				'PRAISE_STATUS_OFF'=>0,
				'PRAISE_DEFAULT_LOCATION'=>'',				
		),
		//转发相关宏定义
		'FOWARD'=>array(
				'FOWARD_STATUS_ON'=>1,
				'FOWARD_STATUS_OFF'=>0,
				'FOWARD_DEFAULT_LOCATION'=>'',
		),
		//转发相关宏定义
		'COMMENT'=>array(
				'COMMENT_STATUS_ON'=>1,
				'COMMENT_STATUS_OFF'=>0,
				'COMMENT_DEFAULT_LOCATION'=>'',
				'COMMENT_DEFAULT_LATITUDE'=>0,
				'COMMENT_DEFAULT_LONGITUDE'=>0,
		),
		//合奏相关宏定义
		'ENSEMBLE'=>array(
				'ENSEMBLE_STATUS_ON'=>1,
				'ENSEMBLE_STATUS_OFF'=>0,
				'ENSEMBLE_DEFAULT_LOCATION'=>'',
				'ENSEMBLE_DEFAULT_LATITUDE'=>0,
				'ENSEMBLE_DEFAULT_LONGITUDE'=>0,
		),
		//异步处理消息类型
		'ASYNC_MESSAGE_QUEUE'=>array(
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
			
		)
);
