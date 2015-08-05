<?php
namespace Home\Controller;
use Think\Controller;
class SetsController extends Controller {
	private	$userModel =  NULL;
	private	$vweiboModel =  NULL;
	private	$topicModel =  NULL;
	private	$ensembleModel =  NULL;
	private $serverModel = NULL;
	private $pConf = NULL;
	private $AMQConf = NULL;
	
	public	function __construct(){
		parent::__construct();
		A("User")->CheckLogin();
		$this->setsModel = D('sets');		
		$this->AMQConf = C('ASYNC_MESSAGE_QUEUE');
	}
	
	public function Lists(){
		/*$result = $this->ChkLogin();
		 if(!$result){
		 $this->ajaxReturn('ERR_LOGIN_NOT');
		 }*/
		/*$_POST = array(
				'uid'=>6,
		);*/
		//file_put_contents("/tmp/debug.txt", var_export($_POST,true),FILE_APPEND);
		$param= array();
		$param['uid'] = $_POST['uid'];
		$result = $this->setsModel->Lists($param);
		//file_put_contents("/tmp/debug.txt", var_export($result,true),FILE_APPEND);
		if (is_array($result)){			
			$this->ajaxReturn($result);
		}else{
			$this->ajaxReturn('ERR_SET_LIST_FAIL');
		}
		
	}
	
	public function Update(){
		/*$result = $this->ChkLogin();
		 if(!$result){
		 $this->ajaxReturn('ERR_LOGIN_NOT');
		 }*/
		/*$_POST = array(
				'uid'=>'6',
				'cotent_viewauthority'=>'101',
				
		);*/
		file_put_contents("/tmp/debug.txt", var_export($_POST,true),FILE_APPEND);

		$param = $_POST;
		$param['uid'] = intval($param['uid']);
		$result = $this->setsModel->Update($param);
		if (($result)){
			$this->ajaxReturn("SUCCESS");
		}else{
			$this->ajaxReturn('ERR_SET_UPDATE_FAIL');
		}
	}
}