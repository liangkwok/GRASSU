<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Home\Model;
use Think\Model;
/**
 * ThinkPHP视图模型扩展 
 */
class ChannelModel extends Model {  
	public	function __construct(){
		
	}
    
    /**
     * 获取列表
     * @access protected
     * @return void
     */
    public function Lists() {
    	$Channels = M("channels");    	
		$record = $Channels->select();		
		if(is_array($record)){
			return $record;			
		}elseif($record === null){
			return null;
		}else{
			$error = $Channels->getDbError();
			return false;
		}		
    }
    
    public function Channels($param){
    	if (empty($param['channels'])){
    		return false;
    	}
    	$Channels = M("channels");
    	$record = $Channels->where("ChannelID in (%s)",$param['channels'])->select();
    	if(is_array($record)){
    		return $record;
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $Channels->getDbError();
    		return false;
    	}
    }

    
}