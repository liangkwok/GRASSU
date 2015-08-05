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
class OPModel extends Model {  
	public	function __construct(){
		
	}
	/**
     * 运营话题
     * @access protected
     * @return void
     */
    public	function Topics() {
    	$Topics = M("operations");
    	$result = $Topics -> where("Type = 100 and Status = 1")->limit(0,9)->select();
    	//var_dump($result);
    	if(is_array($result)){
    		return $result;
    	}else{    		
    		return false;
    	}    	
    }
    /**
     * 运营话题
     * @access protected
     * @return void
     */
    public	function Discovers() {
    	$Topics = M("operations");
    	$result = $Topics -> where("Type = 200 and Status = 1'")->limit(0,9)->select();
    	if(is_array($result)){
    		return $result;
    	}else{
    		return false;
    	}
    }
    
    /**
     * 运营同城话题
     * @access protected
     * @return void
     */
    public	function Citywides() {
    	$Topics = M("operations");
    	$result = $Topics -> where("Type = 300 and Status = 1'")->limit(0,9)->select();
    	if(is_array($result)){
    		return $result;
    	}else{
    		return false;
    	}
    }
}