<?php
namespace Home\Controller;
use Think\Controller;
class PraiseController extends Controller {
	private	$userModel =  NULL;
	private	$praiseModel =  NULL;
	private	$fellowModel =  NULL;
	private $serverModel = NULL;
	private $vweiboModel = NULL;
	private $AMQConf = NULL;
	
	public	function __construct(){
		$this->userModel = D('User');
		$this->praiseModel = D('Praise');
		$this->fellowModel = D('Fellow');
		$this->vweiboModel = D('VWeibo');
		$this->serverModel = D('Server');
		$this->AMQConf = C('ASYNC_MESSAGE_QUEUE');
		if (!$this->userModel->CheckLogin()){
			$this->ajaxReturn('ERR_LOGIN_NOT');
		}
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
	public function Praise(){
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
				'vweiboid'=>573,
				'status'=>1
		);*/
		if(empty($_POST['uid']) || empty($_POST['vweiboid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$param = array();
		$param['uid'] = $_POST['uid'];
		$param['vweiboid'] = $_POST['vweiboid'];
		$param['status'] = $_POST['status'];
		$result	=	$this->praiseModel->Praise($param);
		if($result == true){			
			$this->serverModel->Push($this->AMQConf['PRAISE_VWEIBO'],$param);
			$this->ajaxReturn('SUCCESS');
		}else{
			$this->ajaxReturn('ERR_FELLOW_FAIL');
		}
	}
	
	
	/**
	 * 获取用户转发合拍的列表
	 * @access mobile
	 * @return void
	 */
	public function Lists(){
		//$_POST = array('vweiboid'=>48,'uid'=>6,'page'=>1,'pagenum'=>10);
		if(empty($_POST['vweiboid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		
		$param = array();
		$param['vweiboid'] = $_POST['vweiboid'];
		$total	=	$this->praiseModel->Count($param);
		
		//var_dump($total);
    	if (is_int($total)){
    		
    	}else{
    		$this->ajaxReturn('ERR_PRAISE_LIST_FAIL');
    	}
    	$page = empty($_POST['page'])?1:$_POST['page'];
    	$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];    	
    	$begin = ($page-1) * $pagenum;
    	$end = $page * $pagenum;
    	if($end > $total){
    		$end = $total;
    	}
    	$param =array();
    	$param['vweiboid'] = $_POST['vweiboid'];
    	$param['begin'] =  $begin;
    	$param['end'] = $end;
    	$param['pagenum'] = $pagenum;    	
    	$result	=	$this->praiseModel->Lists($param); 
    	//var_dump($result);
		if(is_array($result)){	
			$Uids = array();
	    	foreach ($result as $key=>$value){	    			
	    		array_push($Uids, $value['P_Uid']);
	    	}
	    	$Uids = implode(',', $Uids);
	    	$param = array();
	    	$param['uids'] = $Uids;
	    	$Users = $this->userModel->Users($param);
	    	//var_dump($Users);
	    	if(is_array($Users)){
	    		
	    	}else{
	    		$this->ajaxReturn('ERR_PRAISE_LIST_FAIL');
	    	}
	    	$param = array();
	    	$param['uid'] = $_POST['uid'];
	    	$param['tuids'] = $Uids;
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
			$this->ajaxReturn('ERR_PRAISE_LIST_FAIL');
		}
	}
	
	public function PraiseList(){
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
		 'tuid'=>6,
		 'page'=>1,
		 'pagenum'=>10
		);*/
		if(empty($_POST['tuid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		
		$param = array();
		$param['uid'] = $_POST['tuid'];
		$total	=	$this->praiseModel->Count($param);
		
		//var_dump($total);
		if (is_int($total)){
		
		}else{
			$this->ajaxReturn('ERR_PRAISE_LIST_FAIL');
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
		$result	=	$this->praiseModel->Lists($param);
		if(empty($result)){
			$this->ajaxReturn('ERR_LIST_NULL');
		}
		$VWeiboController = A("VWeibo");
		if(empty($VWeiboController)){
			$this->ajaxReturn('ERR_LIST_FAIL');
		}
		$VWeiboids= array();
		foreach ($result as $key=>$value){
			array_push($VWeiboids, $value['VWeiboID']);
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
		/*
		//var_dump($result);
		if(is_array($result)){
			$VWeiboids= array();
	    	foreach ($result as $key=>$value){
	    		array_push($VWeiboids, $value['VWeiboID']);
	    	}	   
	    	rsort($VWeiboids,SORT_NUMERIC);
	    	$VWeiboids = implode(',', $VWeiboids);
	    	//var_dump($VWeiboids);
	    	$param = array();
	    	$param['vweibos'] = $VWeiboids;			
			$VWeibos = $this->vweiboModel->VWeibos($param);
			//var_dump($VWeibos);
			if(is_array($VWeibos)){
				//var_dump($VWeibos);
				$Uids = array();
				foreach ($VWeibos as $key=>$value){
					array_push($Uids, $value['V_Uid']);
				}
				$Uids = implode(',', $Uids);
				$param = array();
				$param['uids'] = $Uids;
				//var_dump($Uids);
				$Users = $this->userModel->Users($param);
				if(is_array($Users)){
					 
				}else{
					$this->ajaxReturn('ERR_PRAISE_LIST_FAIL');
				}
				$list = array();
				foreach ($VWeibos as $key=>$value){
					foreach ($Users as $k=>$v){
						if($value['V_Uid'] == $v['Uid']){							
							$list[$key]['vweiboid'] = $VWeibos[$key]['VWeiboID'];
							$list[$key]['orgvwpicid'] = $VWeibos[$key]['ORGVWpicID'];
							$list[$key]['orgvwvideoid'] = $VWeibos[$key]['ORGVWVideoID'];
							$list[$key]['orgvwvideoparam'] = $VWeibos[$key]['ORGVWVideoParam'];
							$list[$key]['vwpicid'] = $VWeibos[$key]['VWpicID'];
							$list[$key]['vwvideoid'] = $VWeibos[$key]['VWVideoID'];
							$list[$key]['vwvideoparam'] = $VWeibos[$key]['VWVideoParam'];
							$list[$key]['viewnum'] = $VWeibos[$key]['ViewNum'];
							$list[$key]['pubtime'] = $VWeibos[$key]['PubTime'];
							$list[$key]['location'] = $VWeibos[$key]['Location'];
							$list[$key]['latitude'] = $VWeibos[$key]['Latitude'];
							$list[$key]['longitude'] = $VWeibos[$key]['Longitude'];
							//$list[$key]['channelid'] = $VWeibos[$key]['ChannelID'];
							$list[$key]['authority'] = $VWeibos[$key]['Authority'];
							$list[$key]['content'] = $VWeibos[$key]['Content'];
							$list[$key]['topiclist'] = $VWeibos[$key]['TopicList'];
							$list[$key]['atlist'] = $VWeibos[$key]['AtList'];
							$list[$key]['praisenum'] = $VWeibos[$key]['PraiseNum'];
							$list[$key]['fowardnum'] = $VWeibos[$key]['FowardNum'];
							$list[$key]['commentnum'] = $VWeibos[$key]['CommentNum'];
							$list[$key]['ensemblenum'] = $VWeibos[$key]['EnsembleNum'];
							
							$list[$key]['uid'] = $Users[$k]['Uid'];
							$list[$key]['nick'] = $Users[$k]['Unick'];
							$list[$key]['avatar'] = $Users[$k]['UAvatar'];
							$list[$key]['channelid'] = $Users[$k]['U_ChannelID'];							
						}
					}
				}
				$retArray = array();
				$retArray['page'] = $page;
				$retArray['pagenum'] = count($list);
				$retArray['total'] = $total;
				$retArray['list'] = array_reverse($list);
				$this->ajaxReturn($retArray);				
			}elseif($VWeibos === null){
				$retArray = array();
				$retArray['page'] = $page;
				$retArray['pagenum'] = 0;
				$retArray['total'] = 0;
				$retArray['list'] = array();
				$this->ajaxReturn($retArray);
			}else{
				$this->ajaxReturn('ERR_PRAISE_LIST_FAIL');
			}
		}elseif($result === null){
			$retArray = array();
			$retArray['page'] = $page;
			$retArray['pagenum'] = 0;
			$retArray['total'] = 0;
			$retArray['list'] = array();
			$this->ajaxReturn($retArray);
		}else{
			$this->ajaxReturn('ERR_PRAISE_LIST_FAIL');
		}*/
		
	}
	
	
}