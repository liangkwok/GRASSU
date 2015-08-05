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
class VWeiboModel extends Model {  
	private $vweiboConf = NULL;
	
	public	function __construct(){
		$this->vweiboConf = C('VWEIBO');
	}
	
	/**
	 * 发表合拍
	 * @access public
	 * @return void
	 */
	public function Publish($param) {
		$VWeibo = M("vweibos");
		$data = array();
		$data['V_Uid'] = $param['uid'];
		$data['ORGVWpicID'] = $param['orgvwpicid'];
		$data['ORGVWVideoID'] = $param['orgvwvideoid'];
		$data['ORGVWVideoParam'] = $param['orgvwvideoparam'];		
		$data['VWpicID'] = $param['vwpicid'];
		$data['VWVideoID'] = $param['vwvideoID'];
		$data['VWVideoParam'] = $param['vwvideoparam'];
		$data['PubTime'] = date("Y-m-d H:i:s");		
		$data['Location'] = $param['location'];
		$data['CityID'] = $param['cityid'];		
		$data['Latitude'] = $param['latitude'];
		$data['Longitude'] = $param['longitude'];
		$data['Channels'] = $param['channelid'];
		$data['Authority'] = $param['authority'];
		$data['ViewAuthority'] = $param['viewauthority'];
		$data['Content'] = $param['content'];		
		$data['AtList'] = $param['atlist'];
		$data['TopicList'] = $param['topiclist'];
		$data['EnsembleList'] = $param['ensemblelist'];
		//file_put_contents("/tmp/debug.txt",var_export($data,true),FILE_APPEND);
		$result = $VWeibo->add($data);
		//file_put_contents("/tmp/debug.txt",var_export($result,true),FILE_APPEND);
		if($result){
			return $result;
		}else{
			$error = $VWeibo->getDbError();
			
			return false;
		}		
	}

    /**
     * 精选合拍数量
     * @access public
     * @return void
     */
    public function SelectCount() {
    	/*$VWeibo = M("vweibos");		
    	$count = $VWeibo->where("RptNum < 4")->count();		
    	if(is_string($count)){
    		return (int)$count;
    	}else{
    		$error = $VWeibo->getDbError();
    		return false;
    	}*/
    	//20150515修改为最热
    	$VWeibo = M("vweibos");
    	 $count = $VWeibo->where("Score > 0")->count();
    	 if(is_string($count)){
    	 return (int)$count;
    	 }else{
    	 $error = $VWeibo->getDbError();
    	 return false;
    	 }
    	
    }    
    
    /**
     * 精选合拍列表
     * @access public
     * @return void
     */
    public function SelectLists($param) {
    	/*$begin = $param['begin'];
    	$end = $param['end'];
    	$pagenum = $param['pagenum'];
    	$VWeibo = M("vweibos");
    	$result = $VWeibo->order('Score desc')->limit($begin,$pagenum)->select();
    	if(is_array($result)){
    		$this->_UpdateViewNumber($result);
    		return $result;
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $VWeibo->getDbError();
    		return false;
    	}*/
    	
    	$begin = $param['begin'];
    	$end = $param['end'];
    	$pagenum = $param['pagenum'];
    	$VWeibo = M("vweibos");
    	$result = $VWeibo->where("Score > 0")->order('Score desc')->limit($begin,$pagenum)->select();
    	if(is_array($result)){
    		$this->_UpdateViewNumber($result);
    		return $result;
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $VWeibo->getDbError();
    		return false;
    	}
    }
    
    /**
     * 我的合拍数量
     * @access public
     * @return void
     */
    public function Count($param) {
    	$VWeibo = M("vweibos");
    	$count = $VWeibo->where("V_Uid = %d and EnsembleList = '' ",$param['uid'])->count();
    	if(is_string($count)){
    		return (int)$count;
    	}else{
    		$error = $VWeibo->getDbError();
    		return false;
    	}
    }
    
    /**
     * 删除自己的合拍
     * @access public
     * @return void
     */
    public function Delete($param) {
    	$VWeibo = M("vweibos");
    	$result = $VWeibo->where("V_Uid = %d and VWeiboID = %d",$param['uid'],$param['vweiboid'])->delete();
    	if(($result)){
    		return $result;
    	}else{
    		$error = $VWeibo->getDbError();
    		return false;
    	}
    }
    /**
     * 我的合拍列表
     * @access public
     * @return void
     */
    public function Lists($param) {
    	$begin = $param['begin'];
    	$pagenum = $param['pagenum'];
    	$VWeibo = M("vweibos");
    	if (isset($param['sorttype']) && $param['sorttype'] == 0){
    		$result = $VWeibo->where("V_Uid = %d and EnsembleList = '' ",$param['uid'])->order('PubTime desc')->limit($begin,$pagenum)->select();
    	}else{
    		$result = $VWeibo->where("V_Uid = %d and EnsembleList = '' ",$param['uid'])->order('ViewNum desc')->limit($begin,$pagenum)->select();
    	}    	
    	if(is_array($result)){
    		$this->_UpdateViewNumber($result);
    		return $result;
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $VWeibo->getDbError();
    		return false;
    	}
    }
   
    /**
     * 我的合拍列表
     * @access public
     * @return void
     */
    public function VWeibos($param) {
    	if(empty($param['vweibos'])){
    		return false;
    	}
    	$VWeibo = M("vweibos");
    	//var_dump($param['vweibos']);
    	$result = $VWeibo->where("VWeiboID in (%s) ",$param['vweibos'])->order("PubTime desc")->select();
    	//var_dump($result);
    	if(is_array($result)){
    		$this->_UpdateViewNumber($result);
    		return $result;
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $VWeibo->getDbError();
    		return false;
    	}
    }
    
    public function Report($param){
    	if(empty($param['vweiboid'])){
    		return false;
    	}
    	$VWeibo = M("vweibos");
    	$data = array();
    	$data['RptNum'] = $param['rptnum'];
    	$result = $VWeibo->where("VWeiboID = %d ",$param['vweiboid'])->save($data);    	
    	if(is_int($result)){
    		return $result;
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $VWeibo->getDbError();
    		return false;
    	}
    }
    
    private  function _UpdateViewNumber($VWeibos){
    	if(!is_array($VWeibos)){
    		return false;
    	}
    	$VWeibo = M("vweibos");
    	foreach ($VWeibos as $key => $value){
    		$data = array();
    		$data['VWeiboID'] = $value['VWeiboID'];
    		$data['ViewNum'] = $value['ViewNum'] + rand(1,3);
    		$VWeibo->save($data);
    	}    	
    }
    
 
    
    public function Topics_Lists($param){
    	if(!isset($param['topicid']) || !isset($param['type'])){
    		return false;
    	}
    	
    	$VWeibo = M("vweibos");
    	$result = false;
    	if($param['type'] == 1){
    		$result = $VWeibo->where("TopicList != '' ")->order("Score Desc")->select();
    	}elseif($param['type'] == 0){
    		$result = $VWeibo->where("TopicList != '' ")->order("PubTime Desc")->select();
    	}else{
    		 
    	}    		
    	if(is_array($result)){
    		foreach ($result as $key=>$value){
    			$topicids = array_filter(explode(",", $value['TopicList']));
    			$flag = false;
    			foreach ($topicids as $k => $v){
    				if($v == $param['topicid']){
    					$flag = true;
    				}
    			}
    			if ($flag == false){
    				unset($result[$key]);
    			}
    		}
    		//$this->_UpdateViewNumber($result);
    		return array('total'=>count($result),'list'=>$result);
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $VWeibo->getDbError();
    		return false;
    	}
    	 
    }
    
    public function Discovers_Lists($param){
    	if(!isset($param['type'])){
    		return false;
    	}
    	
    	$VWeibo = M("vweibos");
    	$result = false;
    	$time_scope = date("Y-m-d H:i:s",strtotime("-30 day"));
    	if($param['type'] == 1){
    		$result = $VWeibo->where("Score > 0")->order("Score Desc")->select();
    	}elseif($param['type'] == 0){
    		$result = $VWeibo->where("PubTime >= '%s'",$time_scope)->order("PubTime Desc")->select();
    	}else{
    		 
    	}    		
    	if(is_array($result)){    		
    		//$this->_UpdateViewNumber($result);
    		return array('total'=>count($result),'list'=>$result);
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $VWeibo->getDbError();
    		return false;
    	}
    	 
    }
    
    public function Citywides_Lists($param){
    	if(!isset($param['cityid']) || !isset($param['type'])){
    		return false;
    	}
    	 
    	$VWeibo = M("vweibos");
    	$result = false;
    	$time_scope = date("Y-m-d H:i:s",strtotime("-1 day"));
    	if($param['type'] == 1){//最近    		
    		$result = $VWeibo->where("CityID = %d and PubTime >= '%s' and Latitude > 0 and Longitude > 0 ",$param['cityid'],$time_scope)->select();
    	}elseif($param['type'] == 0){//最新
    		$result = $VWeibo->where("CityID = %d and PubTime >= '%s'",$param['cityid'],$time_scope)->order("PubTime Desc")->select();
    	}else{
    		$result = false;
    	}
    	if(is_array($result)){    		
    		//$this->_UpdateViewNumber($result);
    		return array('total'=>count($result),'list'=>$result);
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $VWeibo->getDbError();
    		return false;
    	}
    }
}