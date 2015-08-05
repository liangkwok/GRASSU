<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	private	$userModel =  NULL;
	private	$deviceModel =  NULL;
	private	$logInstance =  NULL;
	private	$vweiboModel =  NULL;
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
	 * 获取微博列表
	 * @access public
	 * @param string mobile
	 * @return void
	 */
    public function Index(){
    	/*$_POST = array (
    	 'page' => '1',
    	 'pagenum' => '10',
    	 'token' => '831c6b96584c581d858c565ed2df71ea',
    	 'uid' => '6',
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
    	//var_dump($VWeibos);
    	if(is_array($VWeibos)){
    		//获取用户信息
    		$Uids = array();
    		$VWeiboids = array();
    		foreach ($VWeibos as $key => $value){
    			array_push($Uids, $value['V_Uid']);
    			array_push($VWeiboids, $value['VWeiboID']);
    			if(!empty($value['AtList'])){
    				$atArray = explode(",", $value['AtList']);
    				$Uids = array_merge($Uids,$atArray);
    			}
    		}
    		$Uids = array_unique($Uids);
    		$Uids = implode(",", $Uids);
    		//var_dump($VWeiboids);
    		$param = array();
    		$param['uids'] = $Uids;
    		$users = $this->userModel->Users($param);
    		if(is_array($users)){
    			foreach ($VWeibos as $key => $value){
    				foreach ($users as $k => $v){
    					if($value['V_Uid'] == $v['Uid']){
    						$VWeibos[$key]['uid'] = $v['Uid'];
    						$VWeibos[$key]['nick'] = $v['Unick'];
    						$VWeibos[$key]['gender'] = $v['UGender'];
    						$VWeibos[$key]['avatar'] = $v['UAvatar'];//头像
    						$VWeibos[$key]['uchannelid'] = $v['U_ChannelID'];
    						$VWeibos[$key]['remarks'] = $v['URemarks'];
    					}
    				}
    				if(!empty($value['AtList'])){
    					$atArray = explode(",", $value['AtList']);
    					$tmpArray = array();
    					foreach ($atArray as $ke => $val){
    						foreach ($users as $k => $v){
    							if($val == $v['Uid']){
    								$tmpArray[$key]['uid'] = $v['Uid'];
    								$tmpArray[$key]['nick'] = $v['Unick'];
    								$tmpArray[$key]['gender'] = $v['UGender'];
    								$tmpArray[$key]['avatar'] = $v['UAvatar'];//头像
    								$tmpArray[$key]['uchannelid'] = $v['U_ChannelID'];
    								$tmpArray[$key]['remarks'] = $v['URemarks'];
    							}
    						}
    					}
    					$VWeibos[$key]['atlist'] = $tmpArray;
    				}else{
    					$VWeibos[$key]['atlist'] = array();
    				}
    	
    			}
    		}else{
    			$this->ajaxReturn('ERR_INDEX_FAIL');
    		}
    	
    		//话题信息
    		foreach ($VWeibos as $key => $value){
    			if(!empty($value['TopicList'])){
    				$VWeibos[$key]['tpclist'] = explode(",",$value['TopicList']);
    			}
    		}
    	
    		//合奏信息
    	
    		//各种数量信息
    		$VWeiboids = array_unique($VWeiboids);
    		$VWeiboids = implode(",", $VWeiboids);
    		$param = array();
    		$param['vweiboids'] = $VWeiboids;
    		$PCount = $this->praiseModel->GCount($param);
    		//var_dump($PCount);
    		$FCount = $this->fowardModel->GCount($param);
    		$CCount = $this->commentModel->GCount($param);
    		$ECount = $this->ensembleModel->GCount($param);
    		foreach ($VWeibos as $key => $value){
    			$VWeibos[$key]['praisenum'] = 0;
    			$VWeibos[$key]['fowardnum'] = 0;
    			$VWeibos[$key]['commentnum'] = 0;
    			$VWeibos[$key]['ensemblenum'] = 0;
    			foreach ($PCount as $k => $v){
    				if($value['VWeiboID'] == $v['VWeiboID']){
    					$VWeibos[$key]['praisenum'] = $v['count'];
    				}
    			}
    			foreach ($FCount as $k => $v){
    				if($value['VWeiboID'] == $v['VWeiboID']){
    					$VWeibos[$key]['fowardnum'] = $v['count'];
    				}
    			}
    			foreach ($CCount as $k => $v){
    				if($value['VWeiboID'] == $v['VWeiboID']){
    					$VWeibos[$key]['commentnum'] = $v['count'];
    				}
    			}
    			foreach ($ECount as $k => $v){
    				if($value['VWeiboID'] == $v['VWeiboID']){
    					$VWeibos[$key]['ensemblenum'] = $v['count'];
    				}
    			}
    		}
    		//微博与本人的关系
    		if(!empty($_POST['uid'])){
	    		$param = array();
	    		$param['vweiboids'] = $VWeiboids;
	    		$param['uid'] = $_POST['uid'];
	    		$PRelations = $this->praiseModel->Relations($param);
	    		$FRelations = $this->fowardModel->Relations($param);
	    		$CRelations = $this->commentModel->Relations($param);
	    		$ERelations = $this->ensembleModel->Relations($param);
	    		foreach ($VWeibos as $key => $value){
	    			$VWeibos[$key]['pstatus'] = 0;
	    			$VWeibos[$key]['fstatus'] = 0;
	    			$VWeibos[$key]['cstatus'] = 0;
	    			$VWeibos[$key]['estatus'] = 0;
	    			foreach ($PRelations as $k => $v){
	    				if($value['VWeiboID'] == $v['VWeiboID']){
	    					$VWeibos[$key]['pstatus'] = 1;
	    				}
	    			}
	    			foreach ($FRelations as $k => $v){
	    				if($value['VWeiboID'] == $v['VWeiboID']){
	    					$VWeibos[$key]['fstatus'] = 1;
	    				}
	    			}
	    			foreach ($CRelations as $k => $v){
	    				if($value['VWeiboID'] == $v['VWeiboID']){
	    					$VWeibos[$key]['cstatus'] = 1;
	    				}
	    			}
	    			foreach ($ERelations as $k => $v){
	    				if($value['VWeiboID'] == $v['VWeiboID']){
	    					$VWeibos[$key]['estatus'] = 1;
	    				}
	    			}
	    		}
    		}else{
    			foreach ($VWeibos as $key => $value){
    				$VWeibos[$key]['pstatus'] = 0;
    				$VWeibos[$key]['fstatus'] = 0;
    				$VWeibos[$key]['cstatus'] = 0;
    				$VWeibos[$key]['estatus'] = 0;
    			}
    		}
    		$list = array();
    		foreach ($VWeibos as $key =>$value){
    			$list[$key]['vweiboid'] = $value['VWeiboID'];
    			$list[$key]['uid'] = $value['uid'];
    			$list[$key]['nick'] = $value['nick'];
    			$list[$key]['avatar'] = $value['avatar'];
    			$list[$key]['orgvwpicid'] = $value['ORGVWpicID'];
    			$list[$key]['orgvwvideoid'] = $value['ORGVWVideoID'];
    			$list[$key]['orgvideoparam'] = $value['ORGVWVideoParam'];
    			 
    			$list[$key]['vwpicid'] = $value['VWpicID'];
    			$list[$key]['vwvideoid'] = $value['VWVideoID'];
    			$list[$key]['videoparam'] = $value['VWVideoParam'];
    			 
    			$list[$key]['viewnum'] = $value['ViewNum'];
    			$list[$key]['pubtime'] = $value['PubTime'];
    			$list[$key]['location'] = $value['Location'];
    			$list[$key]['channelid'] = $value['ChannelID'];
    			$list[$key]['authority'] = $value['Authority'];
    			$list[$key]['content'] = $value['Content'];
    			$list[$key]['tpclist'] = $value['tpclist'];
    			$list[$key]['atlist'] = $value['atlist'];
    			$list[$key]['ensembles'] = $value['ensembles'];
    			$list[$key]['praisenum'] =  $value['praisenum'];
    			$list[$key]['fowardnum'] =  $value['fowardnum'];
    			$list[$key]['commentnum'] =  $value['commentnum'];
    			$list[$key]['ensemblenum'] =  $value['ensemblenum'];
    			$list[$key]['pstatus'] = $value['pstatus'];
    			$list[$key]['fstatus'] = $value['fstatus'];
    			$list[$key]['cstatus'] = $value['cstatus'];
    			$list[$key]['estatus'] = $value['estatus'];
    		}
    		$returnArray['total'] = $total;
    		$returnArray['page'] = $page;
    		$returnArray['pagenum'] = count($list);
    		$returnArray['list'] = array_values($list);
    		$this->ajaxReturn($returnArray);
    	}elseif($result === null){
    		$this->ajaxReturn('ERR_INDEX_NULL');
    	}else{
    		$this->ajaxReturn('ERR_INDEX_FAIL');
    	}	
    }

}