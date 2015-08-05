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
class CityModel extends Model {  
	public	function __construct(){
		
	}
    
    /**
     * 获取城市列表
     * @access protected
     * @return void
     */
    public function CitysList() {
    	$Citys = M("citys");    	
		$record = $Citys->select();		
		if(is_array($record)){
			return $record;			
		}elseif($record === null){
			return null;
		}else{
			$error = $Citys->getDbError();
			return false;			
		}		
    }
    
   
    
}