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
class CommentModel extends Model {  
	private $cConf = null;
	public	function __construct(){
		$this->cConf = C('COMMENT');
	}
    
    /**
     * 用户评论
     * @access protected
     * @return void
     */
    public function Comment($param) {
    	$Comment = M("comments");
		$data = array();
		$data['VWeiboID'] = $param['vweiboid'];
		$data['C_Uid'] = $param['uid'];
		$data['Cotent'] = $param['content'];
		$data['CDate'] = date("Y-m-d H:i:s");
		$result = $Comment->add($data);
		if(is_int($result)){
			return $result;
		}else{
			$error = $Comment->getDbError();
			return false;
		}		
    }
    

    public function Count($param) {
    	$Comment = M("comments");
    	$count = '0';
    	if(!empty($param['uid'])){
    		$count = $Comment->where("C_Uid=%d and CStatus=1",$param['uid'])->count();
    		//var_dump($count);
    	}elseif(!empty($param['vweiboid'])){
    		$count = $Comment->where("VWeiboID=%d and CStatus=1",$param['vweiboid'])->count();
    	}else{
    		$count = false;
    	}
    	if(is_string($count)){
    		return (int)$count;
    	}else{
    		$error = $Comment->getDbError();
    		return false;
    	}
    	
    }
    
    
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function GCount($param) {
    	$Comment = M("comments");
    	if(!empty($param['uids'])){
    		$result=$Comment->field("count(*) as count,C_Uid")->where("C_Uid in (%s) and CStatus = 1",$param['uids'])->group("C_Uid")->select();
    
    		//var_dump($count);
    	}elseif(!empty($param['vweiboids'])){
    		$result=$Comment->field("count(*) as count,VWeiboID")->where("VWeiboID in (%s) and CStatus = 1",$param['vweiboids'])->group("VWeiboID")->select();
    
    	}else{
    		$result = false;
    	}
    	if(is_array($result)){
    		return $result;
    	}elseif($result == null){
    		return $result;
    	}else{
    		$error = $Comment->getDbError();
    		return false;
    	}
    }
    
    
    /**
     * 用户赞了哪些合拍
     * @access public
     * @return void
     */
    public function Lists($param) {
    	$Comment = M("comments");
    	if(empty($param['vweiboid'])){
    		return false;
    	}    	
		$result = $Comment->where("VWeiboID=%d and CStatus=1",$param['vweiboid'])->order("CDate DESC")->limit($param['begin'],$param['pagenum'])->select();	
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Comment->getDbError();
    		return false;
    	}
    }
    
    
    /**
     * 用户赞了哪些合拍
     * @access public
     * @return void
     */
    public function Comments($param) {
    	$Comment = M("comments");
    	if(empty($param['commentids'])){
    		return false;
    	}
    	$result = $Comment->where("CommentID in (%s) and CStatus=1",$param['commentids'])->order("CDate DESC")->select();
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Comment->getDbError();
    		return false;
    	}
    }
    /**
     * 合拍被哪些用户赞了
     * @access public
     * @return void
     */
    public function DelComment($param) {
    	$Comment = M("comments");
    	$data = array();
    	$data['CStatus'] = 0;
    	$data['DDate'] = date("Y-m-d H:i:s");
		$result = $Comment->where("CommentID =%d",$param['commentid'])->save($data);
		if(is_int($result)){
			return true;
		}else{
			$error = $Devices->getDbError();
			return false;
		}
    }
    
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function Relations($param) {
    	$Comment = M("comments");
    	if(!empty($param['uid']) &&  !empty($param['vweiboids'])){
    		$result = $Comment->where("C_Uid=%d and VWeiboID in (%s)",$param['uid'],$param['vweiboids'])->select();
    	}elseif(!empty($param['uids']) &&  !empty($param['vweiboid'])){
    		$result = $Comment->where("VWeiboID=%d and C_Uid in (%s)",$param['vweiboid'],$param['uids'])->select();
    	}else{
    		return false;
    	}
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Comment->getDbError();
    		return false;
    	}
    }

    
}