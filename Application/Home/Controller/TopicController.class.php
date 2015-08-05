<?php
namespace Home\Controller;
use Think\Controller;
class TopicController extends Controller {
	private	$userModel =  NULL;
	private	$vweiboModel =  NULL;
	private	$topicModel =  NULL;
	private	$ensembleModel =  NULL;
	private $serverModel = NULL;
	private $pConf = NULL;
	private $AMQConf = NULL;
	private $utilModel = NULL;
	
	public	function __construct(){
		parent::__construct();
		$this->userModel = D('User');
		$this->vweiboModel = D('VWeibo');
		$this->topicModel = D('Topic');	
		$this->ensembleModel = D('Ensemble');
		$this->logInstance =  D('Log');
		$this->serverModel = D('Server');
		$this->AMQConf = C('ASYNC_MESSAGE_QUEUE');
		$this->utilModel = D('Util');
	}
	
	/**
	 * 检测用户对某合拍的喜欢权限
	 * @access mobile
	 * @return void
	 */
	private function ChkAuthority(){
		
		return true;
	}
	/**
	 * 创建话题
	 * @access mobile
	 * @return void
	 */
	public function Add(){
		/*$result = $this->ChkLogin();
		if(!$result){
			$this->ajaxReturn('ERR_LOGIN_NOT');
		}*/
		/*$testid = rand(100,200);
		$_POST = array('topicname'=>"test".$testid,'uid'=>6);*/
		if (empty($_POST['topicname']) || empty($_POST['uid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$param = array();
		$param['topicname'] = $_POST['topicname'];	
		if ($this->utilModel->IsDirty($param['topicname'])){
			$this->ajaxReturn('ERR_CONTENT_ILLEGAL');
		}	
		$param['uid'] = $_POST['uid'];
		$param['description'] = empty($_POST['description'])?"":$_POST['description'];
		$result = $this->topicModel->Add($param);
		//var_dump($result);
		if($result){//增加成功
			//增加用户订阅
			$param['topicid'] = $result;
			$param['status'] = 1;
			$this->serverModel->Push($this->AMQConf['TOPIC_ADD'],$param);	
			$result	=	$this->topicModel->Rss($param);
			$this->serverModel->Push($this->AMQConf['TOPIC_RSS'],$param);			
			$this->ajaxReturn($param);			
		}else{//失败
			$this->ajaxReturn('ERR_TOPIC_ADD_FAIL');			
		}
	}
	
	/**
	 * 用户取消喜欢合拍
	 * @access mobile
	 * @return void
	 */
	public function Rss(){
		/*$result = $this->ChkLogin();
		if(!$result){
			$this->ajaxReturn('ERR_LOGIN_NOT');
		}*/
		/*$_POST = array(
				'uid' => 6,
				'topicid' =>77
		);*/
		if (empty($_POST['topicid']) || empty($_POST['uid']) ){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		if(!isset($_POST['status'])){
			$_POST['status'] = 1;
		}
		$param = array();
		$param['topicid'] = $_POST['topicid'];
		$param['uid'] = $_POST['uid'];
		$param['status'] = $_POST['status'];		
		//增加用户订阅
		$result	=	$this->topicModel->Rss($param);
		if($result){
			$this->serverModel->Push($this->AMQConf['TOPIC_RSS'],$param);
			$this->ajaxReturn('SUCCESS');
		}else{
			$this->ajaxReturn('ERR_TOPIC_RSS_FAIL');
		}
	}
	

	/**
	 * 获取合拍喜欢的用户列表
	 * @access mobile
	 * @return void
	 */
	public function Lists(){	
		$param = array();
		$total	=	$this->topicModel->Count($param);
		//var_dump($total);
		if (is_int($total)){
		
		}else{
			$this->ajaxReturn('ERR_TOPIC_LIST_FAIL');
		}
		$page = empty($_POST['page'])?1:$_POST['page'];
		$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];
		$begin = ($page-1) * $pagenum;
		$end = $page * $pagenum;
		if($end > $total){
			$end = $total;
		}
		$param =array();		
		$param['begin'] =  $begin;
		$param['end'] = $end;
		$param['pagenum'] = $pagenum;
		$result	=	$this->topicModel->Lists($param);
		if (is_array($result)){
			$list = array();
			foreach ($result as $key => $value){
				$list[$key]['topicid'] = $value['TopicID'];
				$list[$key]['topicname'] = $value['TopicName'];
				$list[$key]['usecount'] = $value['UseCount'];
				$list[$key]['cdate'] = $value['CDate'];
				$list[$key]['description'] = $value['Description'];
			}
			$returnArray = array();
			$returnArray['total'] = $total;
			$returnArray['page'] = $page;
			$returnArray['pagenum'] = count($list);
			$returnArray['list'] = array_values($list);
			$this->ajaxReturn($returnArray);
		}elseif($result == null){
			$this->ajaxReturn("ERR_TOPIC_LIST_NULL");
		}else{
			$this->ajaxReturn("ERR_TOPIC_LIST_FAIL");
		}
	}
	/**
	 * 获取合拍喜欢的用户列表
	 * @access mobile
	 * @return void
	 */
	public function RssLists(){
		/*$_POST = array(
				'uid'=>'6'
		);*/
		$param = array();
		$param['uid'] = $_POST['uid'];
		$total	=	$this->topicModel->RssCount($param);
		//var_dump($total);
		if (is_int($total)){
	
		}else{
			$this->ajaxReturn('ERR_TOPIC_LIST_FAIL');
		}
		$page = empty($_POST['page'])?1:$_POST['page'];
		$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];
		$begin = ($page-1) * $pagenum;
		$end = $page * $pagenum;
		if($end > $total){
			$end = $total;
		}
		$param =array();
		$param['uid'] = $_POST['uid'];
		$param['begin'] =  $begin;
		$param['end'] = $end;
		$param['pagenum'] = $pagenum;
		$result	=	$this->topicModel->RssLists($param);
		//var_dump($result);
		if (is_array($result)){
			$list = array();
			foreach ($result as $key => $value){
				$list[$key]['topicid'] = $value['TopicID'];
				$list[$key]['topicname'] = $value['TopicName'];
				$list[$key]['usecount'] = $value['UseCount'];
				$list[$key]['cdate'] = $value['CDate'];
				$list[$key]['description'] = $value['Description'];
			}
			$returnArray = array();
			$returnArray['total'] = $total;
			$returnArray['page'] = $page;
			$returnArray['pagenum'] = count($list);
			$returnArray['list'] = array_values($list);
			$this->ajaxReturn($returnArray);
		}elseif($result == null){
			$this->ajaxReturn("ERR_TOPIC_LIST_NULL");
		}else{
			$this->ajaxReturn("ERR_TOPIC_LIST_FAIL");
		}
	}
	/**
	 * 获取合拍喜欢的用户列表
	 * @access mobile
	 * @return void
	 */
	public function Search(){	
		//$_POST['words'] = 'nico';
		if(empty($_POST['words'])){
			return $this->Lists();
		}
		$param = array();
		$param['words'] = $_POST['words'];
		
		$total	=	$this->topicModel->Count($param);
		//var_dump($total);
		if (is_int($total)){
	
		}else{
			$this->ajaxReturn('ERR_TOPIC_LIST_FAIL');
		}
		$page = empty($_POST['page'])?1:$_POST['page'];
		$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];
		$begin = ($page-1) * $pagenum;
		$end = $page * $pagenum;
		if($end > $total){
			$end = $total;
		}
		$param =array();
		$param['words'] = $_POST['words'];		
		$param['begin'] =  $begin;
		$param['end'] = $end;
		$param['pagenum'] = $pagenum;
		$result	=	$this->topicModel->Lists($param);
		if (is_array($result)){
			$list = array();
			foreach ($result as $key => $value){
				$list[$key]['topicid'] = $value['TopicID'];
				$list[$key]['topicname'] = $value['TopicName'];
				$list[$key]['cdate'] = $value['CDate'];
				$list[$key]['usecount'] = $value['UseCount'];
				$list[$key]['description'] = $value['Description'];
			}
			$returnArray = array();
			$returnArray['total'] = $total;
			$returnArray['page'] = $page;
			$returnArray['pagenum'] = count($list);
			$returnArray['list'] = array_values($list);
			//var_dump($returnArray);
			$this->ajaxReturn($returnArray);
		}elseif($result == null){
			$this->ajaxReturn("ERR_TOPIC_LIST_NULL");
		}else{
			$this->ajaxReturn("ERR_TOPIC_LIST_FAIL");
		}
	}
	public function Topics($param){
		//$_POST['vweibos'] = '165,112,134';
		if(empty($param['topics'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$Topics = $this->topicModel->Topics($param);
		if(is_array($Topics)){
			$list = array();
			foreach ($Topics as $key => $value){
				$list[$key]['topicid'] = $value['TopicID'];
				$list[$key]['topicname'] = $value['TopicName'];
				$list[$key]['usecount'] = $value['UseCount'];
			}
			return ($list);
		}elseif($list === null){
			return null;
		}else{
			return false;
		}
	}
	
	public function RssStatus(){
		/*$_POST = array(
		 'uid'=>'6',
		 'topicid'=>'43'
		);*/
		if(empty($_POST['uid']) || empty($_POST['topicid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$param =array();
		$param['uid'] = $_POST['uid'];	
		$param['topicid'] = $_POST['topicid'];
		$result = $this->topicModel->RssStatus($param);
		//var_dump($result);
		if (is_int($result)){
			$returnArray['uid'] = $param['uid'];
			$returnArray['topicid'] = $param['topicid'];
			$returnArray['status'] = $result;
			$this->ajaxReturn($returnArray);
		}else{
			$this->ajaxReturn("ERR_FAILED");
		}
		
	}
}