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
class FellowModel extends Model {  
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
	public function Fellow($param){
		if(empty($param['uid']) || empty($param['tuid'])){
			return false;
		}
		if (!isset($param['status'])){
			$param['status'] = 1;
		}
		$Fellow = M("fellows");		
		$data = array();
		$data['F_Uid'] = $param['uid'];
		$data['T_Uid'] = $param['tuid'];
		if ($param['status'] == 1){
			$data['CDate'] = date("Y-m-d H:i:s");
		}else{
			$data['DDate'] = date("Y-m-d H:i:s");
		}
		$result = $this->Relation($param);		
		if(is_array($result)){
			if($result['FStatus']==$param['status']){
				return true;
			}else{
				$data['FStatus'] = $param['status'];
				$result = $Fellow->where("F_Uid=%d and T_Uid=%d",$param['uid'],$param['tuid'])->save($data);
				if(is_int($result)){
					return true;
				}else{
					$error = $Fellow->getDbError();
					return false;
				}
			}
			
		}elseif($result ===null){
			$data['FStatus'] = $param['status'];
			$result = $Fellow->add($data);
			if(is_int($result)){
				return true;
			}else{
				$error = $Fellow->getDbError();
				return false;
			}
		}else{			
			return false;
		}
	}
	
	
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function Count($param) {
    	$Fellow = M("fellows");
    	$count = '0';
    	if(!empty($param['uid'])){
    		$count = $Fellow->where("F_Uid=%d and FStatus=1",$param['uid'])->count();
    		//var_dump($count);
    	}elseif(!empty($param['tuid'])){
    		$count = $Fellow->where("T_Uid=%d and FStatus=1",$param['tuid'])->count();
    	}else{
    		$count = false;
    	}    	
    	if(is_string($count)){
    		return (int)$count;
    	}else{
    		$error = $Fellow->getDbError();
    		return false;
    	}
    }    
    
    
    public function Lists($param){
    	$Fellow = M("fellows");
    	if(!empty($param['uid'])){
    		$result = $Fellow->where("F_Uid=%d and FStatus=1",$param['uid'])->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    	}elseif(!empty($param['tuid'])){
    		$result = $Fellow->where("T_Uid=%d and FStatus=1",$param['tuid'])->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    	}else{
    		$result = false;
    	}
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Fellow->getDbError();
    		return false;
    	}
    }
    
    public function ListsAll($param){
    	$Fellow = M("fellows");
    	if(!empty($param['uid'])){
    		$result = $Fellow->where("F_Uid=%d and FStatus=1",$param['uid'])->select();
    	}elseif(!empty($param['tuid'])){
    		$result = $Fellow->where("T_Uid=%d and FStatus=1",$param['tuid'])->select();
    	}else{
    		$result = false;
    	}
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Fellow->getDbError();
    		return false;
    	}
    }
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function Relation($param) {
    	if(empty($param['uid']) || empty($param['tuid'])){
    		return false;
    	}
    	$Fellow = M("fellows");
    	$result = $Fellow->where("F_Uid=%d and T_Uid=%d ",$param['uid'],$param['tuid'])->select();
    	if(is_array($result)){
    		return $result[0];
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Fellow->getDbError();
    		return false;
    	}
    }
    
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function Relations($param) {
    	//file_put_contents("/tmp/debug2.txt",var_export($param,true),FILE_APPEND);
	if(empty($param['uid']) || empty($param['tuids'])){
    		return false;
    	}
    	$Fellow = M("fellows");
    	$result = $Fellow->where("F_Uid=%d and T_Uid in (%s)",$param['uid'],$param['tuids'])->select();
    	//file_put_contents("/tmp/debug2.txt",var_export($result,true),FILE_APPEND);
	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Fellow->getDbError();
    		return false;
    	}
    }
    
}
