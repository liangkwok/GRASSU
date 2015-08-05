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
class ServerModel extends Model {  
	private $Conf = null;
	public	function __construct(){
		$this->Conf = C('SERVER');
	}
    
    /**
     * 用户合奏
     * @access protected
     * @return void
     */
    public function Push($otype,$param) { 
    	$param['cdate'] = date("Y-m-d H:i:s");
    	
    	$Message = M('messages');  
    	$data = array();
    	$data['OType'] = $otype;    	
    	$data['Param'] = json_encode($param);
    	$data['Status'] = 1;
    	$data['CDate'] = $param['CDate'];
    	$result = $Message->add($data);
    	//file_put_contents("/tmp/debug.txt", var_export($result,true),FILE_APPEND);
    	//file_put_contents("/tmp/debug.txt", var_export($param,true),FILE_APPEND);
    	return true;
    }
    
}