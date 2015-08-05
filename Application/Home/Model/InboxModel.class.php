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
class InboxModel extends Model {  
	private $vweiboConf = NULL;
	
	public	function __construct(){
		$this->vweiboConf = C('VWEIBO');
	}
	public function Count($param) {
		$Inboxs = M("inboxs");
		$count =  $Inboxs->where("U_Uid=%d and MsgType = %d",$param['uid'],$param['type'])->count();
			
		if(is_string($count)){
			return (int)$count;
		}else{
			$error = $Inboxs->getDbError();			
			return false;
		}
	}
	
	public function UnreadCount($param) {
		$Inboxs = M("inboxs");
		$count =  $Inboxs->where("U_Uid=%d and MsgType = 1 and Status = 1",$param['uid'])->count();
			
		if(is_string($count)){
			return (int)$count;
		}else{
			$error = $Inboxs->getDbError();
			return false;
		}
	}
	
    public   function Lists($param){
    	if(!isset($param['begin']) || !isset($param['pagenum'])){
    		return false;
    	}
    	$Inboxs = M("inboxs");
    	$result = $Inboxs->where("U_Uid=%d and MsgType = %d",$param['uid'],$param['type'])->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    	//file_put_contents("/tmp/debug2.txt",var_export($result,true),FILE_APPEND);
	if(is_array($result)){
    		$ids = array();
    		foreach ($result as $key => $value){
    			array_push($ids, $value['ID']);
    		}
    		$ids = implode(",", $ids);
    		$this->Update($ids);
    	//file_put_contents("/tmp/debug2.txt",var_export($result,true),FILE_APPEND);
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Inboxs->getDbError();
    		return false;
    	}
    }
    
    private function Update($ids){
    	$Inboxs = M("inboxs");
    	$sql = "update inboxs set Status=0 where ID in(".$ids.")";
    	$result = $Inboxs->execute($sql);
    	if(is_numeric($result)){    		
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Inboxs->getDbError();
    		return false;
    	}
    }
    
    
}
