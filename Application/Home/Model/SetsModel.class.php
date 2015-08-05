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
class SetsModel extends Model {
	private $setConf;  
	public	function __construct(){
		$this->setConf = array(
			'notice_method_voice'=>array('100','101'),
			'notice_method_quake'=>array('100','101'),
			'notice_type_praise'=>array('100','101'),
			'notice_type_comment'=>array('100','101'),
			'notice_type_foward'=>array('100','101'),
			'notice_type_ensamble'=>array('100','101'),
			'notice_type_newtopic'=>array('100','101'),
			'notice_type_newfriend'=>array('100','101'),
			'notice_type_newfellow'=>array('100','101'),
			'notice_type_publishat'=>array('100','101'),
			'notice_type_commentat'=>array('100','101'),
			'cotent_viewauthority'=>array('101','100','102','103'),
			'cotent_preload'=>array('100','102','101'),
			'cotent_ensambleauthority'=>array('100','101','102'),							
		);
	}    
	public function InitSets($param){
		if(empty($param['uid'])){
			return false;
		}		
		
		$sets = array();
		foreach ($this->setConf as $key=>$value){			
			$sets[$key]	= $value[1];
		}
		
		/*
		//新消息通知方式
		$sets['notice']['method']['voice'] = 101;
		$sets['notice']['method']['quake'] = 101;
		//新消息通知类型
		$sets['notice']['type']['praise'] = 101;
		$sets['notice']['type']['comment'] = 101;
		$sets['notice']['type']['foward'] = 101;
		$sets['notice']['type']['ensamble'] = 101;
		$sets['notice']['type']['newtopic'] = 101;
		$sets['notice']['type']['newfriend'] = 101;
		//$sets['notice']['type']['firstpublish'] = 101;
		$sets['notice']['type']['newfellow'] = 101;
		$sets['notice']['type']['publishat'] = 101;
		$sets['notice']['type']['commentat'] = 101;
		//$sets['notice']['type']['usersuggest'] = 101;
		//$sets['notice']['type']['offlineact'] = 101;
			
		//此功能放在话题模块即可
		//$sets['rss'] = 101;
		//达人推荐
		//$sets['proficient'] = 101;
			
		//内容权限
		$sets['cotent']['viewauthority'] = 101;
		$sets['cotent']['preload'] = 101;
		$sets['cotent']['ensambleauthority'] = 101;
			
		//社交网络
		//$sets['social']['weixin'] = 101;
		//$sets['social']['weibo'] = 101;
		//$sets['social']['qq'] = 101;
		//$sets['social']['douban'] = 101;
		//$sets['social']['instagram'] = 101;
		//$sets['social']['facebook'] = 101;
		 */
		 
		$data = array();
		$data['Sets'] = json_encode($sets,true);
		$data['UDate'] = date("Y-m-d H:i:s");
		$data['Uid'] = $param['uid'];
		$Sets = M("sets");
		$result = $Sets->add($data);
		if ($result){
			return $sets;
		}else{
			return false;
		}		
	}
	
    public function Lists($param) {    	  
    	if(empty($param['uid'])){
    		return false;
    	}	
    	$Sets = M("sets");
		$record = $Sets->where("Uid = %d",$param['uid'])->select();		
		if(is_array($record)){
			//var_dump($record);
			return json_decode($record[0]['Sets'],true);			
		}elseif($record === null){
			$record = $this->InitSets($param);
			if(is_array($record)){
				return $record;
			}else{
				return false;
			}			
		}else{
			$error = $Sets->getDbError();
			return false;			
		}		
    }
    
    public function Update($param){
    	if(empty($param['uid'])){
    		return false;
    	}
    	
    	$record = $this->Lists($param);
    	//var_dump($record);
    	if(is_array($record)){
    		foreach ($this->setConf as $key=>$value){
    			foreach ($param as $newkey => $newvalue){
    				if($key == $newkey){    					
    					if(in_array($newvalue,$value,true)){    						
    						$record[$key] = $newvalue;
    					}
    				}
    			}
    				
    		}
    		$data = array();
    		$data['Sets'] = json_encode($record,true);
    		$data['UDate'] = date("Y-m-d H:i:s");
    		
    		$Sets = M("sets");
    		//var_dump($data);
    		$result = $Sets->where("Uid = %d",$param['uid'])->save($data);
    		if ($result){
    			return true;
    		}else{
    			return false;
    		}
    	}else{
    		return false;
    	}
    	
    }
    
}