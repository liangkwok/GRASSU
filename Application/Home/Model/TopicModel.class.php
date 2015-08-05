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
class TopicModel extends Model {  
	public	function __construct(){
		
	}
    
  
    /**
     * 创建新话题
     * @access protected
     * @return void
     */
    public function Add($param) {
    	if (empty($param['topicname']) || empty($param['uid']) ){
    		return false;
    	}
    	$data = array();
    	$data['TopicName'] = $param['topicname'];
    	$data['T_Uid'] = $param['uid'];
    	$data['Description'] = $param['description'];
    	$data['CDate'] =  date("Y-m-d H:i:s");    
    	$Topics = M("topics");
    	$result = $Topics -> where("TopicName = '%s'",$data['TopicName'])->select();
    	if(is_array($result)){
    		return $result[0]['TopicID'];
    	}elseif($result == null){
    		$result = $Topics->add($data);
    		if(is_numeric($result)){
    			return $result;
    		}else{
    			$error = $Topics->getDbError();
    			return false;
    		}
    	}else{
    		$error = $Topics->getDbError();
    		return false;
    	}    	
    }
    
    
    public function Count($param){
    	$Topics = M("topics");
    	$count = "0";
    	if(!empty($param['words'])){
    		$map = array();
    		$map['TopicName'] = array("like","%".$param['words']."%");
    		$count = $Topics ->where($map)->count();
    		//var_dump($count);
    	}else{
    		$count = $Topics -> count();
    	}
    	
    	if(is_string($count)){
    		return (int)$count;
    	}else{
    		$error = $Topics->getDbError();
    		return false;
    	}
    }
    public function Lists($param){
    	$Topics = M("topics");
    	if(!empty($param['words'])){
    		$map = array();
    		$map['TopicName'] = array("like","%".$param['words']."%");
    		$result = $Topics->where($map)->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    	}else{
    		$result = $Topics->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    	}
    	 
    	 
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Topics->getDbError();
    		return false;
    	}
    }
    
    public function RssCount($param){
    	$TopicRss = M("topicrss");    	
    	if(empty($param['uid'])){
    		return false;
    	}
    	$count = "0";
    	$count = $TopicRss ->where("T_Uid = %d and CStatus = 1",$param['uid'])->count();
    	
    	if(is_string($count)){
    		return (int)$count;
    	}else{
    		$error = $TopicRss->getDbError();
    		return false;
    	}
    }
    public function RssLists($param){
    	$TopicRss = M("topicrss");    	
    	if(empty($param['uid'])){
    		return false;
    	}
    	//var_dump($param);
    	$result = $TopicRss->where("T_Uid = %d and CStatus = 1",$param['uid'])->order('CDate desc')->limit($param['begin'],$param['pagenum'])->select();
    	//var_dump($result);
    	if(is_array($result)){
    		$topicids = array();
    		foreach ($result as $key=>$value){
    			array_push($topicids, $value['TopicID']);
    		}    	
    		$topicids = implode(",", $topicids);
    		$Topics = M("topics");
    		$result = $Topics->where("TopicID in (%s)",$topicids)->order('CDate desc')->select();
    		if (is_array($result)){
    			return $result;
    		}elseif($result == null){
    			return null;
    		}else{
    			$error = $Topics->getDbError();
    			return false;
    		}
    	}elseif($result == null){
    		return null;
    	}else{
    		$error = $TopicRss->getDbError();
    		return false;
    	}    	
    }
    
    
    public function ListsAll($param){
    	$Topics = M("topics");
    	$result = $Topics->order('CDate desc')->select();    	 
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Topics->getDbError();
    		return false;
    	}
    }
    public  function Topics($param){
    	if (empty($param['topics']) ){
    		return false;
    	}
    	$Topics = M("topics");
    	$result = $Topics -> where("TopicID in (%s)",$param['topics'])->select();
    	if(is_array($result)){
    		return $result;
    	}elseif($result == null){
    		return null;
    	}else{
    		$error = $Topics->getDbError();
    		return false;
    	}
    }
    /**
     * 创建新话题
     * @access protected
     * @return void
     */
    public function HotList($param) {
    	if (empty($param['topicname']) || empty($param['begin']) || empty($param['end'])){
    		return false;
    	}    	 	
    	$Topics = M("topics");
    	$result = $Topics->where("TopicName = '%s'",$param['topicname'])->order('CDate desc')->limit($param['begin'],$param['end'])->select();
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Topics->getDbError();
    		return false;
    	}
    }
    
    public function Rss($param){
    if(empty($param['uid']) || empty($param['topicid'])){
			return false;
		}
		if (!isset($param['status'])){
			$param['status'] = 1;
		}
		$TopicRss = M("topicrss");		
		$data = array();
		$data['T_Uid'] = $param['uid'];
		$data['TopicID'] = $param['topicid'];
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
				$result = $TopicRss->where("TopicID=%d and T_Uid=%d",$param['topicid'],$param['uid'])->save($data);
				if(is_int($result)){
					return true;
				}else{
					$error = $TopicRss->getDbError();
					return false;
				}
			}
			
		}elseif($result ===null){
			$data['CStatus'] = $param['status'];
			$result = $TopicRss->add($data);
			if(is_int($result)){
				return true;
			}else{
				$error = $TopicRss->getDbError();
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
    public function Relation($param) {
    	if(empty($param['uid']) || empty($param['topicid'])){
    		return false;
    	}
    	$TopicRss = M("topicrss");
    	$result = $TopicRss->where("T_Uid=%d and TopicID=%d ",$param['uid'],$param['topicid'])->select();
    	if(is_array($result)){
    		return $result[0];
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $TopicRss->getDbError();
    		return false;
    	}
    }
    /**
     * 创建新话题
     * @access protected
     * @return void
     */
    public function NewList($param) {
    	if (empty($param['topicname']) || empty($param['begin']) || empty($param['end'])){
    		return false;
    	}    	 	
    	$Topics = M("topics");
    	$result = $Topics->where("TopicName = '%s'",$param['topicname'])->order('CDate desc')->limit($param['begin'],$param['end'])->select();
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $Topics->getDbError();
    		return false;
    	}
    }
    
   public function RssStatus($param){
		if(empty($param['uid']) || empty($param['topicid'])){
			return false;
		}
		$TopicRss = M("topicrss");
		$record = $TopicRss->where("T_Uid = %d and TopicID = %d",$param['uid'],$param['topicid'])->select();
		if (is_array($record)){
			return intval($record[0]['CStatus']);
		}else{
			
    		return 0;
		}
   }
    
}