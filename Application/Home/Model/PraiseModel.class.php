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
class PraiseModel extends Model {  
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
	public function Praise($param){
		if(empty($param['uid']) || empty($param['vweiboid'])){
			return false;
		}
		if (!isset($param['status'])){
			$param['status'] = 1;
		}
		$Praise = M("praises");		
		$data = array();
		$data['P_Uid'] = $param['uid'];
		$data['VWeiboID'] = $param['vweiboid'];
		if ($param['status'] == 1){
			$data['CDate'] = date("Y-m-d H:i:s");
		}else{
			$data['DDate'] = date("Y-m-d H:i:s");
		}
		$result = $this->Relation($param);		
		if(is_array($result)){
			if($result['CStatus']==$param['status']){
				return true;
			}else{
				$data['CStatus'] = $param['status'];
				$result = $Praise->where("P_Uid=%d and VWeiboID=%d",$param['uid'],$param['vweiboid'])->save($data);
				if(is_int($result)){
					return true;
				}else{
					$error = $Praise->getDbError();
					return false;
				}
			}
			
		}elseif($result ===null){
			$data['CStatus'] = $param['status'];
			$result = $Praise->add($data);
			if(is_int($result)){
				return true;
			}else{
				$error = $Praise->getDbError();
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
    	$Praise = M("praises");
    	$count = '0';
    	if(!empty($param['uid'])){
    		$count = $Praise->where("P_Uid=%d and CStatus=1",$param['uid'])->count();
    		//var_dump($count);
    	}elseif(!empty($param['vweiboid'])){
    		$count = $Praise->where("VWeiboID=%d and CStatus=1",$param['vweiboid'])->count();
    	}else{
    		$count = false;
    	}    	
    	if(is_string($count)){
    		return (int)$count;
    	}else{
    		$error = $Praise->getDbError();
    		return false;
    	}
    }    
    
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function GCount($param) {
    	$Praise = M("praises");
    	if(!empty($param['uids'])){
    		$result=$Praise->field("count(*) as count,P_Uid")->where("P_Uid in (%s) and CStatus = 1",$param['uids'])->group("P_Uid")->select();
    		
    		//var_dump($count);
    	}elseif(!empty($param['vweiboids'])){
    		//var_dump($param['vweiboids']);
    		$result=$Praise->field("count(*) as count,VWeiboID")->where("VWeiboID in (%s) and CStatus = 1",$param['vweiboids'])->group("VWeiboID")->select();
    		//var_dump($result);
    	}else{
    		$result = false;
    	}
    	if(is_array($result)){
    		return $result;
    	}elseif($result == null){
    		return $result;
    	}else{
    		$error = $Praise->getDbError();
    		return false;
    	}
    }
    
    
    public function Lists($param){    	
    	if(!isset($param['begin']) || !isset($param['pagenum'])){
    		return false;
    	}
    	$Praise = M("praises");
    	if(!empty($param['uid'])){
    		$result = $Praise->where("P_Uid=%d and CStatus=1",$param['uid'])->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    		//var_dump($result);
    	}elseif(!empty($param['vweiboid'])){
    		$result = $Praise->where("VWeiboID=%d and CStatus=1",$param['vweiboid'])->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    		//var_dump($result);
    	}else{
    		$result = false;
    		//var_dump($result);
    	}
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Praise->getDbError();
    		return false;
    	}
    }
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function Relation($param) {
    	if(empty($param['uid']) || empty($param['vweiboid'])){
    		return false;
    	}
    	$Praise = M("praises");
    	$result = $Praise->where("P_Uid=%d and VWeiboID=%d",$param['uid'],$param['vweiboid'])->select();
    	if(is_array($result)){
    		return $result[0];
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Praise->getDbError();
    		return false;
    	}
    }
    
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function Relations($param) {
    	$Praise = M("praises");
    	if(!empty($param['uid']) &&  !empty($param['vweiboids'])){
    		$result = $Praise->where("P_Uid=%d and VWeiboID in (%s)",$param['uid'],$param['vweiboids'])->select();
    	}elseif(!empty($param['uids']) &&  !empty($param['vweiboid'])){
    		$result = $Praise->where("VWeiboID=%d and P_Uid in (%s)",$param['vweiboid'],$param['uids'])->select();
    	}else{
    		return false;
    	}    	
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Praise->getDbError();
    		return false;
    	}
    }
   
}