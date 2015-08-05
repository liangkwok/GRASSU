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
class ExpertModel extends Model {  
	public	function __construct(){
		
	}
	/**
	 * 检测
	 * @access mobile
	 * @return void
	 */
	private function ChkAuthority(){
	
		return true;
	}
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function Count($param) {  
    	$Experts = M("experts");
    	$count = '0';
    	$count = $Experts->where("CStatus=1")->count();     	
    	if(is_string($count)){
    		return (int)$count;
    	}else{
    		$error = $Experts->getDbError();
    		return false;
    	}
    }    
    public function Lists($param){    	
    	if(!isset($param['begin']) || !isset($param['pagenum'])){
    		return false;
    	}
    	$Experts = M("experts");
    	$result = $Experts->where("CStatus=1")->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Experts->getDbError();
    		return false;
    	}
    }
}