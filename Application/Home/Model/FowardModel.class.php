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
class FowardModel extends Model {  
	private $cConf = null;
	public	function __construct(){
		$this->cConf = C('FOWARD');
	}
    
    /**
     * 用户评论
     * @access protected
     * @return void
     */
    public function Foward($param) {
		if(empty($param['uid']) || empty($param['vweiboid'])){
			return false;
		}
		if (!isset($param['status'])){
			$param['status'] = 1;
		}
		$Foward = M("fowards");
		$data = array();
		$data['F_Uid'] = $param['uid'];
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
				$result = $Foward->where("F_Uid=%d and VWeiboID=%d",$param['uid'],$param['vweiboid'])->save($data);
				if(is_int($result)){
					return true;
				}else{
					$error = $Foward->getDbError();
					return false;
				}
			}
				
		}elseif($result ===null){
			$data['CStatus'] = $param['status'];
			$result = $Foward->add($data);
			if(is_int($result)){
				return true;
			}else{
				$error = $Foward->getDbError();
				return false;
			}
		}else{
			return false;
		}
    }
    

    public function Count($param) {    	
    	$Foward = M("fowards");
    	$count = '0';
    	if(!empty($param['uid'])){
    		$count = $Foward->where("F_Uid=%d and CStatus=1",$param['uid'])->count();
    		//var_dump($count);
    	}elseif(!empty($param['vweiboid'])){
    		$count = $Foward->where("VWeiboID=%d and CStatus=1",$param['vweiboid'])->count();
    	}else{
    		$count = false;
    	}
    	if(is_string($count)){
    		return (int)$count;
    	}else{
    		$error = $Foward->getDbError();
    		return false;
    	}
    }
    
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function GCount($param) {
    	$Foward = M("fowards");
    	if(!empty($param['uids'])){
    		$result=$Foward->field("count(*) as count,F_Uid")->where("F_Uid in (%s) and CStatus = 1",$param['uids'])->group("F_Uid")->select();
    
    		//var_dump($count);
    	}elseif(!empty($param['vweiboids'])){
    		$result=$Foward->field("count(*) as count,VWeiboID")->where("VWeiboID in (%s) and CStatus = 1",$param['vweiboids'])->group("VWeiboID")->select();
    
    	}else{
    		$result = false;
    	}
    	if(is_array($result)){
    		return $result;
    	}elseif($result == null){
    		return $result;
    	}else{
    		$error = $Foward->getDbError();
    		return false;
    	}
    }
    
    /**
     * 用户赞了哪些合拍
     * @access public
     * @return void
     */
    public function Lists($param) {
    	if(!isset($param['begin']) || !isset($param['pagenum'])){
    		return false;
    	}
    	$Foward = M("fowards");    	
    	if(!empty($param['uid'])){
    		$result = $Foward->where("F_Uid=%d and CStatus=1",$param['uid'])->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    		//var_dump($result);
    	}elseif(!empty($param['vweiboid'])){
    		$result = $Foward->where("VWeiboID=%d and CStatus=1",$param['vweiboid'])->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
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
    		$error = $Foward->getDbError();
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
    	$Foward = M("fowards");
    	$result = $Foward->where("F_Uid=%d and VWeiboID=%d",$param['uid'],$param['vweiboid'])->select();
    	if(is_array($result)){
    		return $result[0];
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Foward->getDbError();
    		return false;
    	}
    }
    
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function Relations($param) {
    	$Foward = M("fowards");
    	if(!empty($param['uid']) &&  !empty($param['vweiboids'])){
    		$result = $Foward->where("F_Uid=%d and VWeiboID in (%s)",$param['uid'],$param['vweiboids'])->select();
    	}elseif(!empty($param['uids']) &&  !empty($param['vweiboid'])){
    		$result = $Foward->where("VWeiboID=%d and F_Uid in (%s)",$param['vweiboid'],$param['uids'])->select();
    	}else{
    		return false;
    	}
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Foward->getDbError();
    		return false;
    	}
    }
}