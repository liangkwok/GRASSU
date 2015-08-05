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
class TopicRssModel extends Model {  
	public	function __construct(){
		
	}
    
    /**
     * 话题评论
     * @access protected
     * @return void
     */
    public function Rss($param) {
    	if (empty($param['topicid']) || empty($param['uid']) ){
    		return false;
    	}
    	//var_dump($param);
    	$TopicRss = M("topicrss");    	
		$record = $TopicRss->where("TopicID = %d and T_Uid = %d",$param['topicid'],$param['uid'])->select();		
		//var_dump($record);
		if(is_array($record)){
			if($record[0]['CStatus'] == $param['status']){
				return true;
			}else{
				$data = array();
				if($param['status'] == 1){
					$data['CDate'] = date("Y-m-d H:i:s");
					$data['CStatus'] = 1;
				}else{
					$data['DDate'] = date("Y-m-d H:i:s");
					$data['CStatus'] = 0;
				}
				$record = $TopicRss->where("TopicID = %d and T_Uid = %d",$param['topicid'],$param['uid'])->save($data);
				if($record){
					return true;
				}else{
					return false;
				}
			}		
		}elseif($record === null){
			if($param['status'] == 0){
				return false;
			}
			$data = array();
			$data['TopicID'] = $param['topicid'];
			$data['T_Uid'] = $param['uid'];
			$data['CDate'] = date("Y-m-d H:i:s");
			$data['CStatus'] = 1;
			$record = $TopicRss->add($data);
			if(is_numeric($record)){
				return true;
			}else{
				return false;
			}			
		}else{
			$error = $TopicRss->getDbError();
			return false;
		}		
    }
    
    public function Count($param) {
    	if (empty($param['topicname'])){
    		return false;
    	}
    	$TopicRss = M("topicrss");
    	$record = $TopicRss->where("TopicName = %d",$param['topicname'])->count();
    	if(is_int($record)){
    		return $record;
    	}else{
    		$error = $TopicRss->getDbError();
    		return false;
    	}
    }
    /**
     * 话题评论
     * @access protected
     * @return void
     */
    public function Lists($param) {
    	if (empty($param['topicname'])){
    		return false;
    	}
    	$TopicRss = M("topicrss");
    	$record = $TopicRss->where("TopicName = %d",$param['topicname'])->select();
    	if(is_array($record)){
    		return $record;
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $TopicRss->getDbError();
    		return false;
    	}
    }
    
}