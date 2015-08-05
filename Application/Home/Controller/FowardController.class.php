<?php
namespace Home\Controller;
use Think\Controller;
class FowardController extends Controller {
	private	$userModel =  NULL;
	private	$fowardModel =  NULL;
	private	$logInstance =  NULL;
	private	$fellowModel =  NULL;
	private $AMQConf = NULL;
	private $serverModel = NULL;
	private $vweiboModel = NULL;
	
	public	function __construct(){
		A("User")->CheckLogin();
		$this->userModel = D('User');
		$this->fowardModel = D('Foward');
		$this->fellowModel = D('Fellow');
		$this->vweiboModel = D('VWeibo');
		$this->AMQConf = C('ASYNC_MESSAGE_QUEUE');
		$this->logInstance =  D('Log');
		$this->serverModel = D('Server');
	}
	
	/**
	 * 检测用户对某合拍的评论权限
	 * @access mobile
	 * @return void
	 */
	private function ChkAuthority(){
		
		return true;
	}
	/**
	 * 用户评论合拍
	 * @access mobile
	 * @return void
	 */
	public function Foward(){
		/*$result = $this->ChkLogin();
		if(!$result){
			$this->ajaxReturn('ERR_LOGIN_NOT');
		}
		$result = $this->ChkAuthority();
		if(!$result){
			$this->ajaxReturn('ERR_FOWARD_NO_AUTHORITY');
		}*/    	
    	/*$_POST = array(
    	 'uid'=>9,
    	 'vweiboid'=>48,
    	 'status'=>1
    	);*/
    	if(empty($_POST['uid']) || empty($_POST['vweiboid'])){
    		$this->ajaxReturn('ERR_PARAM_ILLEGAL');
    	}
    	$param = array();
    	$param['uid'] = $_POST['uid'];
    	$param['vweiboid'] = $_POST['vweiboid'];
    	$param['status'] = $_POST['status'];
    	$result	=	$this->fowardModel->Foward($param);
    	
    	if($result == true){
    		$this->serverModel->Push($this->AMQConf['VWEIBO_FOWARD'],$param);
    		$this->ajaxReturn('SUCCESS');
    	}else{
    		$this->ajaxReturn('ERR_FOWARD_FAIL');
    	}
    	
    	
	}
	
	/**
	 * 获取用户评论合拍的列表
	 * @access mobile
	 * @return void
	 */
	public function Lists(){
		/*$result = $this->ChkLogin();
		 if(!$result){
		 $this->ajaxReturn('ERR_LOGIN_NOT');
		 }
		 $result = $this->ChkAuthority();
		 if(!$result){
		 $this->ajaxReturn('ERR_FOWARD_NO_AUTHORITY');
		 }*/
		/*$_POST = array(
				'uid' => 6,
				'vweiboid' => 151
				
		);*/
		$param = array();
		//$param['uid'] = $_POST['uid'];
		$param['vweiboid'] = $_POST['vweiboid'];
		$total	=	$this->fowardModel->Count($param);
		//var_dump($total);
		if (is_int($total)){
		
		}else{
			$this->ajaxReturn('ERR_FOWARD_LIST_FAIL');
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
		$result	=	$this->fowardModel->Lists($param);
		//var_dump($result);
		if(is_array($result)){			
			$Uids = array();
	    	foreach ($result as $key=>$value){
	    		array_push($Uids, $value['F_Uid']);
	    	}	    	
	    	$Uids = implode(',', $Uids);
	    	$param = array();
	    	$param['uids'] = $Uids;
	    	//var_dump($Uids);
	    	$Users = $this->userModel->Users($param);
	    	//var_dump($Users);
	    	if(is_array($Users)){
	    		
	    	}else{
	    		$this->ajaxReturn('ERR_FOWARD_LIST_FAIL');
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
			foreach ($result as $key=>$value){
				foreach ($Users as $k=>$v){
					if($value['F_Uid'] == $v['Uid']){
						$list[$key]['fowardid'] = $result[$key]['FowardID'];
						$list[$key]['vweiboid'] = $result[$key]['VWeiboID'];
						$list[$key]['cdate'] = $result[$key]['CDate'];						
						$list[$key]['uid'] = $Users[$k]['Uid'];
						$list[$key]['nick'] = $Users[$k]['Unick'];
						$list[$key]['avatar'] = $Users[$k]['UAvatar'];
						$list[$key]['channelid'] = $Users[$k]['U_ChannelID'];	
						$list[$key]['status'] = $Users[$k]['status'];
					}
				}
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
			$this->ajaxReturn('ERR_FELLOW_LIST_FAIL');
		}
	}
	
	public function FowardList(){
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
		 'tuid'=>8,
		 'page'=>1,
		 'pagenum'=>10
		);*/
		if(empty($_POST['tuid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		
		$param = array();
		$param['uid'] = $_POST['tuid'];
		$total	=	$this->fowardModel->Count($param);
		
		//var_dump($total);
		if (is_int($total)){
		
		}else{
			$this->ajaxReturn('ERR_FOWARD_LIST_FAIL');
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
		$result	= $this->fowardModel->Lists($param);
		if(empty($result)){
			$this->ajaxReturn('ERR_LIST_NULL');
		}
		$VWeiboids= array();
		foreach ($result as $key=>$value){
			array_push($VWeiboids, $value['VWeiboID']);
		}
		//rsort($VWeiboids,SORT_NUMERIC);
		$VWeiboids = implode(',', $VWeiboids);
		$param = array();
		$param['vweibos'] = $VWeiboids;
		$VWeibos = $this->vweiboModel->VWeibos($param);
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
		//var_dump($result);
		/*if(is_array($result)){
			$VWeiboids= array();
			foreach ($result as $key=>$value){
				array_push($VWeiboids, $value['VWeiboID']);
			}
			$VWeiboids = implode(',', $VWeiboids);
			$param = array();
			$param['vweibos'] = $VWeiboids;
			$VWeibos = $this->vweiboModel->VWeibos($param);
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
					$this->ajaxReturn('ERR_FOWARD_LIST_FAIL');
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
				$this->ajaxReturn('ERR_FOWARD_LIST_FAIL');
			}
		}elseif($result === null){
			$retArray = array();
			$retArray['page'] = $page;
			$retArray['pagenum'] = 0;
			$retArray['total'] = 0;
			$retArray['list'] = array();
			$this->ajaxReturn($retArray);
		}else{
			$this->ajaxReturn('ERR_FOWARD_LIST_FAIL');
		}*/
	}
}