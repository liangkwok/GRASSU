<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	private	$userModel =  NULL;
	private	$deviceModel =  NULL;
	private	$logInstance =  NULL;
	private	$vweiboModel =  NULL;
	private	$indexModel =  NULL;
	private	$topicModel =  NULL;
	private	$topicRssModel =  NULL;
	private	$ensembleModel =  NULL;
	private $serverModel = NULL;
	private $vweiboConf = NULL;
	private $AMQConf = NULL;
	
	public	function __construct(){
		parent::__construct();
		$this->userModel = D('User');
		$this->deviceModel = D('Device');
		$this->vweiboModel = D('VWeibo');
		$this->indexModel = D('Index');
		$this->praiseModel = D('Praise');
		$this->fowardModel = D('Foward');
		$this->topicModel = D('Topic');
		$this->topicRssModel = D('TopicRss');
		$this->ensembleModel = D('Ensemble');
		$this->commentModel = D('Comment');
		$this->logInstance =  D('Log');
		$this->vweiboConf = C('VWEIBO');
		$this->AMQConf = C('ASYNC_MESSAGE_QUEUE');
		$this->serverModel = D('Server');
	}
	/**
	 * 获取合拍列表
	 * @access public
	 * @param string mobile
	 * @return void
	 */
    public function Index(){
    	/*$_POST = array (
    	 'page' => '1',
    	 'pagenum' => '10',
    	 'token' => '831c6b96584c581d858c565ed2df71ea',
    	 'uid' => '15',
    	 'tuid' => '9',
    	 'sorttype'=>'0'
    	);*/
    	
    	$param =array();
    	$param['uid'] = $_POST['tuid'];
    	$total = $this->vweiboModel->SelectCount();
    	if(is_int($total)){
    		 
    	}else{
    		$this->ajaxReturn('ERR_LIST_FAIL');
    	}
    	//var_dump($total);
    	$page = empty($_POST['page'])?1:$_POST['page'];
    	$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];
    	$begin = ($page-1) * $pagenum;
    	$end = $page * $pagenum;
    	if($end > $total){
    		$end = $total;
    	}
    	
    	$param['begin'] =  $begin;
    	$param['end'] = $end;
    	$param['pagenum'] = $pagenum;
    	$VWeibos = $this->vweiboModel->SelectLists($param);
    	$VWeiboController = A("VWeibo");
    	if(empty($VWeiboController)){
    		$this->ajaxReturn('ERR_LIST_FAIL');
    	}
    	$list = $VWeiboController ->VWeibosListInfo($VWeibos);
    	if(is_array($list)){
    		$returnArray = array();
    		$returnArray['total'] = $total;
    		$returnArray['page'] = $page;
    		$returnArray['pagenum'] = count($list);
    		$returnArray['list'] = array_values($list);
    		$this->ajaxReturn($returnArray);
    	}elseif($list === null){
    		$this->ajaxReturn('ERR_LIST_NULL');
    	}else{
    		$this->ajaxReturn('ERR_LIST_FAIL');
    	}
    }
    
    /**
     * 获取主界面合拍列表
     * @access public
     * @param string mobile
     * @return void
     */
    public function Mains(){
    	A("User")->CheckLogin();
    	/*$_POST = array (
    	 'token' => 'da35a6e4e63586dfdadb1bc0afc3e000',
    	 'uid' => '6',
    	);*/
    	 
    	$param =array();
    	$param['uid'] = $_POST['uid'];
    	$total = $this->indexModel->Count($param);
    	//var_dump($total);
    	if(is_int($total) && $total > 0 ){
    		 
    	}else{
    		return $this->Index();
    	}
    	//var_dump($total);
    	$page = empty($_POST['page'])?1:$_POST['page'];
    	$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];
    	$begin = ($page-1) * $pagenum;
    	$end = $page * $pagenum;
    	if($end > $total){
    		$end = $total;
    	}
    	 
    	
    	$param['begin'] =  $begin;
    	$param['end'] = $end;
    	$param['pagenum'] = $pagenum;
    	//var_dump($param);
    	$VWeibos = $this->indexModel->Lists($param);
    	//var_dump($VWeibos);
    	$VWeiboController = A("VWeibo");
    	if(empty($VWeiboController)){
    		$this->ajaxReturn('ERR_LIST_FAIL');
    	}
    	$list = $VWeiboController ->VWeibosListInfo($VWeibos);
    	if(is_array($list)){
    		$returnArray = array();
    		$returnArray['total'] = $total;
    		$returnArray['page'] = $page;
    		$returnArray['pagenum'] = count($list);
    		$returnArray['list'] = array_values($list);
    		$this->ajaxReturn($returnArray);
    	}elseif($list === null){
    		$this->ajaxReturn('ERR_LIST_NULL');
    	}else{
    		$this->ajaxReturn('ERR_LIST_FAIL');
    	}
    }

}
