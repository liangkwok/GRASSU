<?php
namespace Home\Controller;
use Think\Controller;
class EnsembleController extends Controller {
	private	$userModel =  NULL;
	private	$ensembleModel =  NULL;
	private	$fellowModel =  NULL;
	private $serverModel = NULL;
	private $vweiboModel = NULL;
	private $AMQConf = NULL;
	
	public	function __construct(){
		//A("User")->CheckLogin();
		$this->userModel = D('User');
		$this->ensembleModel = D('Ensemble');
		$this->fellowModel = D('Fellow');
		$this->vweiboModel = D('VWeibo');
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
	 * 获取此合拍的合奏用户列表
	 * @access mobile
	 * @return void
	 */
	public function Lists(){
		//$_POST = array('vweiboid'=>915,'uid'=>6,'page'=>1,'pagenum'=>10);
		if(empty($_POST['vweiboid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		
		/*$param = array();
		$param['vweiboid'] = $_POST['vweiboid'];
		$total	=	$this->ensembleModel->Count($param);
		
		//var_dump($total);
    	if (is_int($total)){
    		
    	}else{
    		$this->ajaxReturn('ERR_ENSEMBLE_LIST_FAIL');
    	}
    	$page = empty($_POST['page'])?1:$_POST['page'];
    	$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];    	
    	$begin = ($page-1) * $pagenum;
    	$end = $page * $pagenum;
    	if($end > $total){
    		$end = $total;
    	}*/
    	$param =array();
    	$param['evweiboid'] = $_POST['vweiboid'];
    	/*$param['begin'] =  $begin;
    	$param['end'] = $end;
    	$param['pagenum'] = $pagenum; */
    	$result	=	$this->ensembleModel->Lists($param); 
    	//var_dump($result);
		if(is_array($result)){	
			$Uids = array();
	    	foreach ($result as $key=>$value){	    			
	    		array_push($Uids, $value['E_Uid']);
	    	}
	    	$Uids = implode(',', $Uids);
	    	$param = array();
	    	$param['uids'] = $Uids;
	    	$Users = $this->userModel->Users($param);
	    	//var_dump($Users);
	    	if(is_array($Users)){
	    		
	    	}else{
	    		$this->ajaxReturn('ERR_ENSEMBLE_LIST_FAIL');
	    	}
	    	$param = array();
	    	$param['uid'] = $_POST['uid'];
	    	$param['tuids'] = $Uids;
	    	$Relations = $this->fellowModel->Relations($param);
	    	//var_dump($Relations);
	    	//var_dump($Users);
	    	foreach ($Relations as $key => $value){
	    		foreach ($Users as $k => $v){
	    			if($Relations[$key]['E_Uid'] == $Users[$k]['Uid']){
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
			/*$retArray['page'] = $page;
			$retArray['pagenum'] = count($list);*/
			$retArray['total'] = count($list);
			$retArray['list'] = $list;
			$this->ajaxReturn($retArray);
		}elseif($result === null){
			$retArray = array();
			/*$retArray['page'] = $page;
			$retArray['pagenum'] = 0;*/
			$retArray['total'] = 0;
			$retArray['list'] = array();			
			$this->ajaxReturn($retArray);
		}else{
			$this->ajaxReturn('ERR_ENSEMBLE_LIST_FAIL');
		}
	}
	
	/**
	 * 获取此合拍的原始视频列表
	 * @access mobile
	 * @return void
	 */
	public function VVideoLists(){
		/*$result = $this->ChkLogin();
		 if(!$result){
		 $this->ajaxReturn('ERR_LOGIN_NOT');
		 }
		 $result = $this->ChkAuthority();
		 if(!$result){
		 $this->ajaxReturn('ERR_FELLOW_NO_AUTHORITY');
		 }*/
		/*$_POST = array(
		 'uid'=>15,
		 'vweiboid'=>916
		);*/
		$param =array();
    	$param['vvweiboid'] = $_POST['vweiboid'];
    	$result	=	$this->ensembleModel->Lists($param);     	
    	$VWeiboids= array($_POST['vweiboid']);
    	foreach ($result as $key=>$value){
    		array_push($VWeiboids, $value['E_VWeiboID']);
    	}
    	//rsort($VWeiboids,SORT_NUMERIC);
    	$VWeiboids = implode(',', $VWeiboids);
		//var_dump($VWeiboids);
		/*if(empty($result)){
			$this->ajaxReturn('ERR_LIST_NULL');
		}*/
		$VWeiboController = A("VWeibo");
		if(empty($VWeiboController)){
			$this->ajaxReturn('ERR_LIST_FAIL');
		}
		
		//var_dump($VWeiboids);
		$param = array();
		$param['vweibos'] = $VWeiboids;
		$VWeibos = $this->vweiboModel->VWeibos($param);
		$list = $VWeiboController ->VWeibosListInfo($VWeibos);
		if(is_array($list)){
			$returnArray = array();
			$returnArray['total'] = count($list);			
			$returnArray['list'] = array_values($list);
			$this->ajaxReturn($returnArray);
		}elseif($list === null){
			$this->ajaxReturn('ERR_LIST_NULL');
		}else{
			$this->ajaxReturn('ERR_LIST_FAIL');
		}
	}
	
	/**
	 * 查看某个视频被哪些视频合奏了
	 * @access mobile
	 * @return void
	 */
	public function EVideoLists(){
		/*$result = $this->ChkLogin();
		 if(!$result){
		 $this->ajaxReturn('ERR_LOGIN_NOT');
		 }
		 $result = $this->ChkAuthority();
		 if(!$result){
		 $this->ajaxReturn('ERR_FELLOW_NO_AUTHORITY');
		 }*/
		/*$_POST = array(
		 'uid'=>6,
		 'vweiboid'=>915
		);*/
		$param =array();
		$param['evweiboid'] = $_POST['vweiboid'];
		$result	= $this->ensembleModel->Lists($param);
		//var_dump($result);
		//$VWeiboids= array($_POST['vweiboid']);
		$VWeiboids= array();
		foreach ($result as $key=>$value){
			array_push($VWeiboids, $value['V_VWeiboID']);
		}
		//rsort($VWeiboids,SORT_NUMERIC);
		$VWeiboids = implode(',', $VWeiboids);
		//var_dump($VWeiboids);
		/*if(empty($result)){
		 $this->ajaxReturn('ERR_LIST_NULL');
		}*/
		$VWeiboController = A("VWeibo");
		if(empty($VWeiboController)){
			$this->ajaxReturn('ERR_LIST_FAIL');
		}
	
		//var_dump($VWeiboids);
		$param = array();
		$param['vweibos'] = $VWeiboids;
		$VWeibos = $this->vweiboModel->VWeibos($param);
		$list = $VWeiboController ->VWeibosListInfo($VWeibos);
		if(is_array($list)){
			$returnArray = array();
			$returnArray['total'] = count($list);
			$returnArray['list'] = array_values($list);
			$this->ajaxReturn($returnArray);
		}elseif($list === null){
			$this->ajaxReturn('ERR_LIST_NULL');
		}else{
			$this->ajaxReturn('ERR_LIST_FAIL');
		}
	}
	
	
	public function EnsembleList(){
		/*$result = $this->ChkLogin();
		 if(!$result){
		 $this->ajaxReturn('ERR_LOGIN_NOT');
		 }
		 $result = $this->ChkAuthority();
		 if(!$result){
		 $this->ajaxReturn('ERR_FELLOW_NO_AUTHORITY');
		 }*/
		/*$_POST = array(
		 'token'=>'da35a6e4e63586dfdadb1bc0afc3e000',
		 'uid'=>6,
		 'tuid'=>32,
		 'page'=>1,
		 'pagenum'=>10
		);*/
		if(empty($_POST['tuid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		
		$param = array();
		$param['uid'] = $_POST['tuid'];
		$total	=	$this->ensembleModel->Count($param);
		
		//var_dump($total);
		if (is_int($total)){
		
		}else{
			$this->ajaxReturn('ERR_ENSEMBLE_LIST_FAIL');
		}
		$page = empty($_POST['page'])?1:$_POST['page'];
		$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];
		$begin = ($page-1) * $pagenum;
		$end = $page * $pagenum;
		if($end > $total){
			$end = $total;
		}
		$param =array();
		$param['uid'] = $_POST['tuid'];
		$param['begin'] =  $begin;
		$param['end'] = $end;
		$param['pagenum'] = $pagenum;
		$result	=	$this->ensembleModel->Lists($param);
		//var_dump($result);
		if(empty($result)){
			$this->ajaxReturn('ERR_LIST_NULL');
		}
		$VWeiboController = A("VWeibo");
		if(empty($VWeiboController)){
			$this->ajaxReturn('ERR_LIST_FAIL');
		}
		$VWeiboids= array();
		foreach ($result as $key=>$value){
			array_push($VWeiboids, $value['V_VWeiboID']);
		}
		//rsort($VWeiboids,SORT_NUMERIC);
		$VWeiboids = implode(',', $VWeiboids);
		//var_dump($VWeiboids);
		$param = array();
		$param['vweibos'] = $VWeiboids;
		$VWeibos = $this->vweiboModel->VWeibos($param);
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