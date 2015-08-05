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
class IndexModel extends Model {  
	public	function __construct(){
		
	}
    
    /**
     * 获取此用户的合拍数量
     * @access protected
     * @return void
     */
    public function Count($param) {
    	$Indexs = M("indexs");   
    	if(empty($param['uid'])){
    		return false;
    	}
		$record = $Indexs->where("M_Uid = %d",$param['uid'])->select();		
		if(is_array($record)){
			$record = array_filter(explode(",", $record[0]['Lists']));
			return count($record);
		}elseif($record === null){
			return 0;
		}else{
			$error = $Indexs->getDbError();
			return false;			
		}		
    }
    
    /**
     * 获取合拍列表
     * @access protected
     * @return void
     */
    public function Lists($param) {
    	$Indexs = M("indexs");
    	$record = $Indexs->where("M_Uid = %d",$param['uid'])->select();	

    	$begin = $param['begin'];
    	$end = $param['end'];
    	//var_dump($record);
		if(is_array($record)){
			$record = explode(",", $record[0]['Lists']);
			$count = count($record);
			if($count > 0){
				foreach ($record as $key => $value){
				 	if($key < $begin || $key >= $end){
				 		unset($record[$key]);
				 	}
				}
				$weibos = implode(",", $record);
				$VWeiboModel = D("VWeibo");
				if(empty($VWeiboModel)){
					return false;
				}
				//var_dump($weibos);
				$param = array();
				$param['vweibos'] = $weibos;
				return $VWeiboModel->VWeibos($param);
			}else{
				return null;
			}
		}elseif ($record == null){
			return null;
		}else{
			$error = $Indexs->getDbError();
			return false;
		}
    }

    
}