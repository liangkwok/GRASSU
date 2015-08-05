<?php
namespace Home\Controller;
use Think\Controller;
class FellowController extends Controller {
	private	$userModel =  NULL;
	private	$fellowModel =  NULL;
	
	private $AMQConf = NULL;
	private $serverModel = NULL;
	
	public	function __construct(){
		A("User")->CheckLogin();
		$this->userModel = D('User');
		$this->fellowModel = D('Fellow');
		$this->serverModel = D('Server');
		$this->AMQConf = C('ASYNC_MESSAGE_QUEUE');
	}
	
	/**
	 * 检测用户对某合拍的转发权限
	 * @access mobile
	 * @return void
	 */
	private function ChkAuthority(){
		
		return true;
	}
	/**
	 * 用户转发合拍
	 * @access mobile
	 * @return void
	 */
	public function Fellow(){
		/*$result = $this->ChkLogin();
		if(!$result){
			$this->ajaxReturn('ERR_LOGIN_NOT');
		}
		$result = $this->ChkAuthority();
		if(!$result){
			$this->ajaxReturn('ERR_FELLOW_NO_AUTHORITY');
		}*/
		/*$_POST = array(
				'uid'=>9,
				'tuid'=>22,
				'status'=>1
		);*/
		if(empty($_POST['uid']) || empty($_POST['tuid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$tuids = array_filter(explode(",", strval($_POST['tuid'])));
		$param = array();
		$param['uid'] = $_POST['uid'];		
		$param['status'] = intval($_POST['status']);
		foreach ($tuids as $key => $value){
			$param['tuid'] = intval($value);
			$result	=	$this->fellowModel->Fellow($param);
			if($result == true){
				if ($param['status'] == 1){
					$this->serverModel->Push($this->AMQConf['USER_FELLOW'],$param);
				}else{
					$this->serverModel->Push($this->AMQConf['CANCEL_FELLOW'],$param);
				}
				//$this->ajaxReturn('SUCCESS');
			}else{
				continue;
				//$this->ajaxReturn('ERR_FELLOW_FAIL');
			}
		}
		$this->ajaxReturn('SUCCESS');
	}
	
	
	/**
	 * 获取用户转发合拍的列表
	 * @access mobile
	 * @return void
	 */
	public function Lists(){
		//$_POST = array('uid'=>6,'tuid'=>11,'status'=>0);
		if(empty($_POST['tuid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$total = 0;
		if($_POST['status'] == 1){//tuid的关注列表
			$param = array();
			$param['uid'] = $_POST['tuid'];
			$total	=	$this->fellowModel->Count($param);
		}else{//tuid的被关注列表
			$param = array();
			$param['tuid'] = $_POST['tuid'];
			$total	=	$this->fellowModel->Count($param);
		}
		//var_dump($total);
    	if (is_int($total)){
    		
    	}else{
    		$this->ajaxReturn('ERR_FELLOW_LIST_FAIL');
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
    	$result = array();
    	if($_POST['status'] == 1){//tuid的关注列表    		
    		$param['uid'] = $_POST['tuid'];
    		$result	=	$this->fellowModel->Lists($param);
    	}else{//tuid的被关注列表
    		$param['tuid'] = $_POST['tuid'];
    		$result	=	$this->fellowModel->Lists($param);
    		//var_dump($result);
    	}
		if(is_array($result)){			
			$Uids = array();
			if($_POST['status'] == 1){//tuid的关注列表    		
	    		foreach ($result as $key=>$value){	    			
	    			array_push($Uids, $value['T_Uid']);
	    		}	    		
	    	}else{//tuid的被关注列表
	    		foreach ($result as $key=>$value){
	    			array_push($Uids, $value['F_Uid']);
	    		}
	    	}
	    	$Uids = implode(',', $Uids);
	    	$param = array();
	    	$param['uids'] = $Uids;
	    	$Users = $this->userModel->Users($param);
	    	//var_dump($Users);
	    	if(is_array($Users)){
	    		
	    	}else{
	    		$this->ajaxReturn('ERR_FELLOW_LIST_FAIL');
	    	}
	    	$param = array();
	    	$param['uid'] = $_POST['uid'];
	    	$param['tuids'] = $Uids;
	    	$Relations = $this->fellowModel->Relations($param);
	    	//var_dump($Relations);
	    	/*if(is_array($Relations)){
	    		 
	    	}else{
	    		$this->ajaxReturn('ERR_FELLOW_LIST_FAIL');
	    	}*/
	    	
	    	foreach ($Relations as $key => $value){
	    		foreach ($Users as $k => $v){
	    			if($Relations[$key]['T_Uid'] == $Users[$k]['Uid']){
	    				//if ($Relations[$key]['FStatus'] == 1){
	    					$Users[$k]['status'] = $Relations[$key]['FStatus'];;
	    				//}	    				
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
			$retArray['list'] = $list;
			$this->ajaxReturn($retArray);
		}elseif($result === null){
			$retArray = array();
			$retArray['page'] = $page;
			$retArray['pagenum'] = 0;
			$retArray['total'] = 0;
			$retArray['list'] = array();
			
			$this->ajaxReturn($retArray);
		}else{
			$this->ajaxReturn('ERR_FELLOW_LIST_FAIL');
		}
	}
	
	public function friends(){
		//$_POST = array('uid'=>9);
		
		//获取关注列表
		$param = array();
		$param['uid'] = $_POST['uid'];
		$fellows = $this->fellowModel->Lists($param);
		if(is_array($fellows)){
			foreach ($fellows as $key=>$value){
				if($value['FStatus'] != 1){
					unset($fellows[$key]);
				}
			}
		}elseif($fellows == null){
			$this->ajaxReturn("ERR_FRIENDS_LIST_NULL");
		}else{
			$this->ajaxReturn("ERR_FRIENDS_LIST_FAIL");
		}
		if(count($fellows) == 0){
			$this->ajaxReturn("ERR_FRIENDS_LIST_NULL");
		}
		
		//获取被关注列表
		$param = array();
		$param['tuid'] = $_POST['uid'];
		$fellowed = $this->fellowModel->Lists($param);
		if(is_array($fellowed)){
			foreach ($fellowed as $key=>$value){
				if($value['FStatus'] != 1){
					unset($fellowed[$key]);
				}
			}
		}elseif($fellowed == null){
			$this->ajaxReturn("ERR_FRIENDS_LIST_NULL");
		}else{
			$this->ajaxReturn("ERR_FRIENDS_LIST_FAIL");
		}
		if(count($fellowed) == 0){
			$this->ajaxReturn("ERR_FRIENDS_LIST_NULL");
		}
		
		//合并两表获得好友关系
		$Users = array();
		foreach ($fellows as $key=>$value){
			foreach ($fellowed as $k=>$v){
				if($value['T_Uid'] == $v['F_Uid']){
					array_push($Users, $value['T_Uid']);
				}
			}
		}
		if(count($Users) == 0){
			$this->ajaxReturn("ERR_FRIENDS_LIST_NULL");
		}
		
		
		
		//获取用户信息
		$param = array();
		$param['uids'] = implode(",", $Users);
		$Users = $this->userModel->Users($param);
		if(is_array($Users)){
			
		}elseif($Users == null){
			$this->ajaxReturn("ERR_FRIENDS_LIST_NULL");
		}else{
			$this->ajaxReturn("ERR_FRIENDS_LIST_FAIL");
		}
	
		$list = array();
		foreach ($Users as $key=>$value){
				$tmp = array();
				$tmp['uid'] = $Users[$key]['Uid'];
				$tmp['nick'] = $Users[$key]['Unick'];
				$tmp['avatar'] = $Users[$key]['UAvatar'];
				$tmp['channelid'] = $Users[$key]['U_ChannelID'];				
				array_push($list, $tmp);			
		}		
		//$list = array_values($list);
		$retArray = array();
		$retArray['total'] = count($list);
		$retArray['list'] = $list;
		$this->ajaxReturn($retArray);
	}
	
}