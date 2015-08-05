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
class EnsembleModel extends Model {  
	private $eConf = null;
	public	function __construct(){
		$this->eConf = C('ENSEMBLE');
	}
    
	Public function Ensemble($param){
		$Ensemble = M("ensembles");
    	if(empty($param['uid']) ||empty($param['vweiboid']) || empty($param['ensemble'])){
			return false;
		}
		$data = array();
		$eids = array_filter(explode(",",$param['ensemble']));
		foreach ($eids as $key =>$value){
			$data[$key]['E_Uid'] = $param['uid'];
			$data[$key]['V_VWeiboID'] = $param['vweiboid'];
			$data[$key]['E_VWeiboID'] = $value;
			$data[$key]['CDate'] = date("Y-m-d H:i:s");
			$data[$key]['CStatus'] = 1;
		}
		
		$result = $Ensemble->addAll($data);
		if($result){
			return $result;
		}else{
			$error = $Ensemble->getDbError();
			return false;
		}
		
	}
    
    public function Count($param){
    	$Ensemble = M("ensembles");
    	$count = '0';
    	if(!empty($param['uid'])){
    		$count = $Ensemble->where("E_Uid=%d and CStatus=1",$param['uid'])->count('DISTINCT V_VWeiboID');
    		//var_dump($count);
    	}elseif(!empty($param['vweiboid'])){
    		$count = $Ensemble->where("V_VWeiboID=%d and CStatus=1",$param['vweiboid'])->count('DISTINCT V_VWeiboID');
    	}elseif(!empty($param['eweiboid'])){
    		$count = $Ensemble->where("E_VWeiboID=%d and CStatus=1",$param['vweiboid'])->count('DISTINCT E_VWeiboID');
    	}else{
    		$count = false;
    	}
    	if(is_string($count)){
    		return (int)$count;
    	}else{
    		$error = $Ensemble->getDbError();
    		return false;
    	}
    }
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function GCount($param) {
    	$Ensemble = M("ensembles");
    	if(!empty($param['uids'])){
    		$result=$Ensemble->field("count(*) as count,E_Uid")->where("E_Uid in (%s) and CStatus = 1",$param['uids'])->group("E_Uid")->select();
    
    		//var_dump($count);
    	}elseif(!empty($param['vweiboids'])){
    		$result=$Ensemble->field("count(*) as count,E_VWeiboID")->where("E_VWeiboID in (%s) and CStatus = 1",$param['vweiboids'])->group("E_VWeiboID")->select();
    		
    	}else{
    		$result = false;
    	}
    	if(is_array($result)){
    		return $result;
    	}elseif($result == null){
    		return $result;
    	}else{
    		$error = $Ensemble->getDbError();
    		return false;
    	}
    }
  
    
    /**
     * 用户赞了哪些合拍
     * @access public
     * @return void
     */
    public function Lists($param) {    	
    	$Ensemble = M("ensembles");    	
    	if(!empty($param['uid'])){
    		$result = $Ensemble->where("E_Uid=%d and CStatus=1",$param['uid'])->distinct(true)->field('V_VWeiboID')->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    		//var_dump($result);
    	}elseif(!empty($param['vvweiboid'])){
    		//$result = $Ensemble->where("E_VWeiboID=%d and CStatus=1",$param['vweiboid'])->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    		$result = $Ensemble->where("V_VWeiboID=%d and CStatus=1",$param['vvweiboid'])->order('CDate desc')->select();
    		//var_dump($result);
    	}elseif(!empty($param['evweiboid'])){
    		//$result = $Ensemble->where("E_VWeiboID=%d and CStatus=1",$param['vweiboid'])->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    		$result = $Ensemble->where("E_VWeiboID=%d and CStatus=1",$param['evweiboid'])->order('CDate desc')->select();
    		//var_dump($result);
    	}else{
    		$result = false;
    		//var_dump($result);
    	}
    	//var_dump($result);
    	
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Ensemble->getDbError();
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
    	$Ensemble = M("ensembles");
    	$result = $Ensemble->where("E_Uid=%d and E_VWeiboID=%d",$param['uid'],$param['vweiboid'])->select();
    	if(is_array($result)){
    		return $result[0];
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Ensemble->getDbError();
    		return false;
    	}
    }
    
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function Relations($param) {
    	$Ensemble = M("ensembles");
    	if(!empty($param['uid']) &&  !empty($param['vweiboids'])){
    		$result = $Ensemble->where("E_Uid=%d and E_VWeiboID in (%s)",$param['uid'],$param['vweiboids'])->select();
    	}elseif(!empty($param['uids']) &&  !empty($param['vweiboid'])){
    		$result = $Ensemble->where("E_VWeiboID=%d and E_Uid in (%s)",$param['vweiboid'],$param['uids'])->select();
    	}else{
    		return false;
    	}
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Ensemble->getDbError();
    		return false;
    	}
    }
   
}