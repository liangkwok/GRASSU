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
class DeviceModel extends Model {  
	public	function __construct(){
		
	}
    
    /**
     * 更新用户最近登录的设备信息
     * @access public
     * @return void
     */
    public function Update($param) {
    	$Devices = M("devices");    	
    	$data = array();
    	$data['U_Uid'] = $param['uid'];
    	$data['AppID'] = $param['appid'];
    	$data['UserID'] = $param['user_id'];    	
    	$data['Utime'] = date("Y-m-d H:i:s");
    	$data['Source'] = $param['source'];   
    	$result = $Devices->where("U_Uid = %d",$param['uid'])->select();
    	if (is_array($result)){
    		//var_dump($result);
    		$result = $Devices->where("ID=%d",$result[0]['ID'])->save($data);
    		if($result){
    			return true;
    		}else{
    			$error = $Devices->getDbError();
    			return false;
    		}
    	}elseif($result == null){
    		$result = $Devices->add($data);
    		if($result){
    			return true;
    		}else{
    			$error = $Devices->getDbError();
    			return false;
    		}
    	}else{
    		$error = $Devices->getDbError();
    		return false;
    	}
			
    }

    
}