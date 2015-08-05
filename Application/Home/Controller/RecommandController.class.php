<?php
namespace Home\Controller;
use Think\Controller;
class RecommandController extends Controller {
	private	$userModel =  NULL;
	private	$fellowModel =  NULL;
	private	$praiseModel =  NULL;
	private	$expertModel =  NULL;
	private	$fowardModel =  NULL;
	private	$commentModel =  NULL;
	private	$ensembleModel =  NULL;
	private $AMQConf = NULL;
	private $serverModel = NULL;
	
	public	function __construct(){
		$this->userModel = D('User');
		$this->expertModel = D('Expert');
		$this->fellowModel = D('Fellow');
		$this->praiseModel = D('Praise');
		$this->fowardModel =  D('Foward');
		$this->commentModel =  D('Comment');	
		$this->ensembleModel =  D('Ensemble');
		$this->AMQConf = C('ASYNC_MESSAGE_QUEUE');		
		$this->serverModel = D('Server');
	}
	
	/**
	 * 获取用户信息
	 * @access public
	 * @param uid
	 * @return void
	 */
	public function Experts(){
		/*$_POST = array(
			"uid" => '6',	
		);*/
		$param = array();
		$total	=	$this->expertModel->Count($param);
		//var_dump($total);
		if (is_int($total)){
		
		}else{
			$this->ajaxReturn('ERR_LIST_FAIL');
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
		$result	=	$this->expertModel->Lists($param);
		//var_dump($result);
		if(is_array($result)){			
			$Uids = array();
	    	foreach ($result as $key=>$value){
	    		array_push($Uids, $value['E_Uid']);
	    	}	    	
	    	$Uids = implode(',', $Uids);
	    	$param = array();
	    	$param['uids'] = $Uids;
	    	//var_dump($Uids);
	    	$Users = $this->userModel->Users($param);
	    	//var_dump($Users);
	    	if(is_array($Users)){
	    		
	    	}else{
	    		$this->ajaxReturn('ERR_LIST_FAIL');
	    	}	    	
	    	foreach ($Users as $key => $value){
	    		$Users[$key]['status'] = 0;
	    	}
	    	$param = array();
	    	$param['uid'] = $_POST['uid'];
	    	$param['tuids'] = $Uids;
	    	//var_dump($param);
	    	$Relations = $this->fellowModel->Relations($param);
	    	
	    	//var_dump($Relations);
	    	//var_dump($Users);
			foreach ($Relations as $key => $value){
	    		foreach ($Users as $k => $v){
	    			if($Relations[$key]['T_Uid'] == $Users[$k]['Uid']){
	    				$Users[$k]['status'] = $Relations[$key]['FStatus'];
	    			}
	    		}
	    	}
	    	$list = array();
			foreach ($Users as $key=>$value){
				$list[$key]['uid'] = $Users[$key]['Uid'];
				$list[$key]['nick'] = $Users[$key]['Unick'];
				$list[$key]['avatar'] = $Users[$key]['UAvatar'];
				$list[$key]['channelid'] = $Users[$key]['U_ChannelID'];
				$list[$key]['status'] = isset($Users[$key]['status'])?$Users[$key]['status']:0;
			}	
			$retArray = array();
			$retArray['page'] = $page;
			$retArray['pagenum'] = count($list);
			$retArray['total'] = $total;
			$retArray['list'] = ($list);
			$this->ajaxReturn($retArray);
		}elseif($result === null){
			$retArray = array();
			$retArray['page'] = $page;
			$retArray['pagenum'] = 0;
			$retArray['total'] = 0;
			$retArray['list'] = array();
			
			$this->ajaxReturn($retArray);
		}else{
			$this->ajaxReturn('ERR_LIST_FAIL');
		}
	}
	
}
