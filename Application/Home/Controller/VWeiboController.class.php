<?php
namespace Home\Controller;
use Think\Controller;
class VWeiboController extends Controller {
	private	$userModel =  NULL;
	private	$deviceModel =  NULL;
	private	$logInstance =  NULL;
	private	$vweiboModel =  NULL;
	private	$topicModel =  NULL;
	private	$topicRssModel =  NULL;
	private	$ensembleModel =  NULL;
	private	$commentModel =  NULL;
	private $channelModel = NULL;
	private $serverModel = NULL;
	private $vweiboConf = NULL;
	private $AMQConf = NULL;
	private $fowardModel = NULL;
	private $fellowModel = NULL;
	private $utilModel = NULL;
	
	
	public	function __construct(){
		//A("User")->CheckLogin();
		parent::__construct();
		$this->userModel = D('User');
		$this->deviceModel = D('Device');
		$this->vweiboModel = D('VWeibo');
		$this->praiseModel = D('Praise');
		$this->fowardModel = D('Foward');
		$this->fellowModel = D('Fellow');
		$this->topicModel = D('Topic');
		$this->topicRssModel = D('TopicRss');
		$this->ensembleModel = D('Ensemble');
		$this->commentModel = D('Comment');
		$this->utilModel = D('Util');
		
		$this->vweiboConf = C('VWEIBO');
		$this->AMQConf = C('ASYNC_MESSAGE_QUEUE');
		$this->channelModel = D('Channel');
		$this->serverModel = D('Server');
		
	}
	/**
	 * 发布合拍
	 * @access public
	 * @param string mobile
	 * @return void
	 */
    public function Publish(){
    	A("User")->CheckLogin();
    	/*$_POST =  array (
			  'atlist' => '',
			  'authority' => '101',
			  'channels' => '',
			  'cityid' => '362',
			  'content' => '',
			  'ensemble' => '',
			  'latitude' => '40.009716',
			  'location' => '北京 朝阳',
			  'longitude' => '116.481728',
			  'orgvwpicid' => 'http://grassuz.qiniudn.com/101001432544111.964993.jpg',
			  'orgvwvideoid' => 'http://grassuz.qiniudn.com/101001432544112.378645.mp4',
			  'orgvwvideoparam' => '',
			  'token' => '92cf310115ae9c3219bc7018b40e750c',
			  'tpclist' => '',
			  'uid' => '10100',
			  'viewauthority' => '101',
			  'vwpicid' => 'http://grassuz.qiniudn.com/101001432544111.964993.jpg',
			  'vwvideoID' => 'http://grassuz.qiniudn.com/101001432544112.378645.mp4',
			  'vwvideoparam' => '',
			);*/
			   	/*$result = $this->ChkLogin();
    	if(!$result){
    		$this->ajaxReturn('ERR_LOGIN_NOT');
    	}*/    
    	//file_put_contents("/tmp/debug.txt",var_export($_POST,true),FILE_APPEND);	
    	$param = array();
    	$param['uid'] = $_POST['uid'];
    	$param['token'] = $_POST['token'];
    	//请求接口增加字段：话题字段+@用户裂表字段
    	//file_put_contents("/tmp/debug.txt",var_export($param,true),FILE_APPEND);
    	if(empty($_POST['vwpicid']) || empty($_POST['vwvideoID'])){
    		$this->ajaxReturn('ERR_PARAM_ILLEGAL');
    	}
    	//file_put_contents("/tmp/debug.txt",var_export($param,true),FILE_APPEND);
    	$param['orgvwpicid'] = $_POST['orgvwpicid'];
    	$param['orgvwvideoid'] = $_POST['orgvwvideoid'];
    	$param['orgvwvideoparam'] = $_POST['orgvwvideoparam'];
    	$param['vwpicid'] = $_POST['vwpicid'];
    	$param['vwvideoID'] = $_POST['vwvideoID'];
    	$param['vwvideoparam'] = $_POST['vwvideoparam'];
    	$param['channels'] = $_POST['channels'];
    	$param['authority'] = empty($_POST['authority'])?'101':$_POST['authority'];
		$param['ensemblelist'] = $_POST['ensemble'];
		$param['viewauthority'] = empty($_POST['viewauthority'])?'101':$_POST['viewauthority'];
    	
    	$param['location'] = empty($_POST['location'])?$this->vweiboConf['VWEIBO_VW_DEFAULT_LOCATION']:$_POST['location'];
    	$param['cityid'] = empty($_POST['cityid'])?0:$_POST['cityid'];
    	$param['latitude'] = empty($_POST['latitude'])?$this->vweiboConf['VWEIBO_VW_DEFAULT_LATITUDE']:$_POST['latitude'];
    	$param['longitude'] = empty($_POST['longitude'])?$this->vweiboConf['VWEIBO_VW_DEFAULT_LONGITUDE']:$_POST['longitude'];
    	$param['content'] = empty($_POST['content'])?$this->vweiboConf['VWEIBO_VW_DEFAULT_CONTENT']:$_POST['content'];
		if ($this->utilModel->IsDirty($param['content'])){
			$this->ajaxReturn('ERR_CONTENT_ILLEGAL');
		}
    	$param['atlist'] = empty($_POST['atlist'])?"":$_POST['atlist'];
    	$param['topiclist'] = empty($_POST['tpclist'])?"":$_POST['tpclist'];
    	//file_put_contents("/tmp/debug.txt",var_export($result,true),FILE_APPEND);
    	$result = $this->vweiboModel->Publish($param);
    	//file_put_contents("/tmp/debug.txt",var_export($result,true),FILE_APPEND);
    	if($result){   
    		$tmp = array();
    		$tmp['uid'] = $param['uid'];
    		$tmp['vweiboid'] = $result;    		
    		$tmp['atlist'] = $param['atlist'];
    		$tmp['topiclist'] = $param['topiclist'];
    		$tmp['ensemblelist'] = $param['ensemblelist'] ;
    		
    		$this->serverModel->Push($this->AMQConf['VWEIBO_PUBLISH'],$tmp);
			if(!empty($_POST['ensemble'])){
				$param = array();
				$param['uid'] = $_POST['uid'];
				$param['vweiboid'] = $result;
				$param['ensemble'] = $_POST['ensemble'];
				$result = $this->ensembleModel->Ensemble($param);
				///file_put_contents("/tmp/debug.txt", var_export($result,true),FILE_APPEND);
				//file_put_contents("/tmp/debug.txt", var_export($param,true),FILE_APPEND);
				if($result){
					$this->serverModel->Push($this->AMQConf['ENSEMBLE_VWEIBO'],$param);
				}
			}
    		$this->ajaxReturn("SUCCESS");
    	}else{
    		$this->ajaxReturn('ERR_VWEIBO_PUBLISH_FAIL');
    	}    	
    }
    
    
    
    /**
     * 获取合拍列表
     * @access public
     * @param string mobile
     * @return void
     */
    public function Lists(){        	
    	/*$_POST = array (
    			'page' => '1',
    			'pagenum' => '10',
    			'token' => '831c6b96584c581d858c565ed2df71ea',
    			'uid' => '6',
    			'tuid' => '9',
    			'sorttype'=>'0'
    	);*/
    	if(empty($_POST['tuid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		if(!isset($_POST['sorttype'])){
			$_POST['sorttype'] = 0;
		}
		/*
		$param = array();
    	$param['uid'] = $tuid;
    	$sets = D("Sets")->Lists($param);
		if(!$sets){
			$this->ajaxReturn('ERR_USER_QUERY_FAIL');
		}
		$viewauthority = $sets['cotent_viewauthority'];
		switch ($viewauthority){
			case '100':break;
			case '101':break;
			case '102':break;
			case '103':break;
			default:
				$this->ajaxReturn('ERR_USER_QUERY_FAIL');
				break;
		}
		 * */
    	$param =array();
    	$param['uid'] = $_POST['tuid'];
    	$total = $this->vweiboModel->Count($param);
    	
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
    	$param['sorttype'] = $_POST['sorttype'];
    	$VWeibos = $this->vweiboModel->Lists($param);
    	$list = $this->VWeibosListInfo($VWeibos);
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
     * 删除合拍
     * @access public
     * @param string mobile
     * @return void
     */
    public function Delete(){
    	/*$_POST = array (
    	 'token' => 'ee3f150c1cf8dddedb0faad6f6835a4b',
    	 'uid' => '22',
    	 'vweiboid'=>918
    	);*/
    	A("User")->CheckLogin();    	
    	$param =array();
    	$param['uid'] = $_POST['uid'];
    	$param['vweiboid'] = $_POST['vweiboid'];
    	$result = $this->vweiboModel->Delete($param);
    	//var_dump($result);
    	if(($result) == 1){
    		$this->serverModel->Push($this->AMQConf['VWEIBO_DELETE'],$param);
    		$this->ajaxReturn('SUCCESS');
    	}elseif(($result) == 0){
    		$this->ajaxReturn('ERR_VWEIBO_NO_AUTHORITY');
    	}else{
    		$this->ajaxReturn('ERR_VWEIBO_DELETE_FAIL');
    	}
    }
    
    /**
     * 我关注的好友合拍列表
     * @access public
     * @param string mobile
     * @return void
     */
    public function FellowLists(){
    	/*$_POST = array (
    	 'page' => '1',
    	 'pagenum' => '10',
    	 'token' => '831c6b96584c581d858c565ed2df71ea',
    	 'uid' => '6',
    	 'tuid' => '9',
    	 'sorttype'=>'0'
    	);*/
    	
    	
    	$param =array();
    	$param['uid'] = $_POST['uid'];    	
    	$fellows = $this->fellowModel->ListsAll($param);
    	if (is_array($fellows)){
    		
    	}elseif($fellows == null){
    		$this->ajaxReturn('ERR_LIST_NULL');
    	}else{
    		$this->ajaxReturn('ERR_LIST_FAIL');
    	}
    	
    	
    	$total = $this->vweiboModel->Count($param);
    	 
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
    	$param['sorttype'] = $_POST['sorttype'];
    	$VWeibos = $this->vweiboModel->Lists($param);
    	$list = $this->VWeibosListInfo($VWeibos);
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
    public function VWeibosListInfo($VWeibos){
    	if(is_array($VWeibos)){
    		//获取用户信息
    		$Uids = array();
    		$VWeiboids = array();
    		$Channels = array();
    		$Topics = array();
    		$EWeibos = array();//合奏合拍ID
    		foreach ($VWeibos as $key => $value){
    			array_push($Uids, $value['V_Uid']);
    			array_push($VWeiboids, $value['VWeiboID']);    			
    			if(!empty($value['AtList'])){
    				$atArray = explode(",", $value['AtList']);
    				$Uids = array_merge($Uids,$atArray);
    			}   
    			if(!empty($value['Channels'])){
    				$ChArray = explode(",", $value['Channels']);
    				$Channels = array_merge($Channels,$ChArray);
    			} 		
    			if(!empty($value['TopicList'])){
    				$ToArray = explode(",", $value['TopicList']);
    				$Topics = array_merge($Topics,$ToArray);
    			}	    	
    			if(!empty($value['EnsembleList'])){
    				$EWeibosArray = explode(",", $value['EnsembleList']);
    				$EWeibos = array_merge($EWeibos,$EWeibosArray);
    				array_push($EWeibos, $value['VWeiboID']);
    			}	
    		}
    		$EUids = array();
    		$EWeibos = implode(",",array_unique($EWeibos));
    		if(empty($EWeibos)){
    			
    		}else{
    			$param= array();
    			$param['vweibos'] = $EWeibos;
    			$EWeibos = $this->vweiboModel->VWeibos($param);
    			if(is_array($EWeibos)){
    				foreach ($EWeibos as $k => $v){
    					array_push($Uids, $v['V_Uid']);
    				}
    			}
    		}
    		
    		
    		$Uids = implode(",", array_unique($Uids));
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
    								$tmpArray[$ke]['uid'] = $v['Uid'];
    								$tmpArray[$ke]['nick'] = $v['Unick'];
    								$tmpArray[$ke]['gender'] = $v['UGender'];
    								$tmpArray[$ke]['avatar'] = $v['UAvatar'];//头像
    								$tmpArray[$ke]['uchannelid'] = $v['U_ChannelID'];
    								$tmpArray[$ke]['remarks'] = $v['URemarks'];
    							}
    						}
    					}
    					$VWeibos[$key]['atlist'] = $tmpArray;
    				}else{
    					$VWeibos[$key]['atlist'] = array();
    				}
    				if(!empty($value['EnsembleList'])){
    					$value['EnsembleList'] = $value['VWeiboID'].",".$value['EnsembleList'];
    					$EArray = explode(",", $value['EnsembleList']);
    					//var_dump($EArray);
    					$tmpArray = array();
    					//var_dump($EWeibos);
    					//var_dump($EWeibos);
    					//var_dump($users);
    					foreach ($EArray as $ke => $val){
    						foreach ($EWeibos as $k => $v){
    							if($val == $v['VWeiboID']){
    								//var_dump($v['VWeiboID']);
		    						foreach ($users as $ku => $vu){
		    							if($v['V_Uid'] == $vu['Uid']){
		    								$tmpArray[$ke]['uid'] = $vu['Uid'];
		    								$tmpArray[$ke]['nick'] = $vu['Unick'];
		    								$tmpArray[$ke]['gender'] = $vu['UGender'];
		    								$tmpArray[$ke]['avatar'] = $vu['UAvatar'];//头像
		    								$tmpArray[$ke]['uchannelid'] = $vu['U_ChannelID'];
		    								$tmpArray[$ke]['remarks'] = $vu['URemarks'];
		    							}
		    						}
    							}
    						}
    					}
    					$VWeibos[$key]['ensemblelist'] = $tmpArray;
    				}else{
    					$VWeibos[$key]['ensemblelist'] = array();
    				}
    	
    			}
    		}else{
    			$this->ajaxReturn('ERR_LIST_FAIL');
    		}
    		///主题信息
    		$Channels = array_unique(array_filter($Channels));    	
    		$Channels = implode(",", $Channels);    		
    		$param = array();
    		$param['channels'] = $Channels;
    		$Channels = $this->channelModel->Channels($param);
    		if(is_array($Channels)){
    			foreach ($VWeibos as $key => $value){
    				if(!empty($value['Channels'])){
    					$ChArray = explode(",", $value['Channels']);
    					$tmpArray = array();
    					foreach ($ChArray as $ke => $val){
    						foreach ($Channels as $k => $v){
    							if($val == $v['ChannelID']){
    								$tmpArray[$ke]['channelid'] = $v['ChannelID'];
    								$tmpArray[$ke]['chgname'] = $v['ChgName'];
    								$tmpArray[$ke]['engname'] = $v['EngName'];
    								$tmpArray[$ke]['color'] = $v['Color'];//头像
    								$tmpArray[$ke]['description'] = $v['Description'];    								
    							}
    						}
    					}
    					$VWeibos[$key]['Channels'] = $tmpArray;
    				}else{
    					$VWeibos[$key]['Channels'] = array();
    				}
    			}
    		}
    		
    		//话题信息
    		$Topics = array_unique(array_filter($Topics));
    		$Topics = implode(",", $Topics);
    		$param = array();
    		$param['topics'] = $Topics;
    		//var_dump($param);
    		$Topics = $this->topicModel->Topics($param);
    		//var_dump($Topics);
    		if(is_array($Topics)){
    			foreach ($VWeibos as $key => $value){
    				if(!empty($value['TopicList'])){
    					$ToArray = explode(",", $value['TopicList']);
    					$tmpArray = array();
    					foreach ($ToArray as $ke => $val){
    						foreach ($Topics as $k => $v){
    							if($val == $v['TopicID']){
    								$tmpArray[$ke]['topicid'] = $v['TopicID'];
    								$tmpArray[$ke]['topicname'] = $v['TopicName'];
    								$tmpArray[$ke]['uid'] = $v['T_Uid'];
    								$tmpArray[$ke]['cdate'] = $v['CDate']; 
    								$tmpArray[$ke]['description'] = $v['Description'];
    							}
    						}
    					}
    					$VWeibos[$key]['tpclist'] = $tmpArray;
    				}else{
    					$VWeibos[$key]['tpclist'] = array();
    				}
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
    				if($value['VWeiboID'] == $v['E_VWeiboID']){
    					$VWeibos[$key]['ensemblenum'] = $v['count'];
    				}
    			}
    		}
    		//合拍与本人的关系
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
    			$list[$key]['distance'] = isset($value['distance'])?$value['distance']:0;
    			$list[$key]['channels'] = $value['Channels'];
    			$list[$key]['authority'] = $value['Authority'];
    			$list[$key]['content'] = $value['Content'];
    			$list[$key]['tpclist'] = $value['tpclist'];
    			$list[$key]['ensemblelist'] = $value['ensemblelist'];
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
    		return ($list);
    	}elseif($result === null){
    		return null;
    	}else{
    		return false;
    	}
    }
    public function VWeibo($param){
    	if(empty($param['vweibos'])){
    		return false;
    	}
    	$result = $this->vweiboModel->VWeibos($param);
    	if(is_array($result)){
    	
    	}else{
    		return  false;
    	}
    	$record = array();
    	$record['vweiboid'] = $result[0]['VWeiboID'];
    	$record['uid'] = $result[0]['uid'];
    	$record['nick'] = $result[0]['nick'];
    	$record['avatar'] = $result[0]['avatar'];
    	$record['orgvwpicid'] = $result[0]['ORGVWpicID'];
    	$record['orgvwvideoid'] = $result[0]['ORGVWVideoID'];
    	$record['orgvideoparam'] = $result[0]['ORGVWVideoParam'];    	
    	$record['vwpicid'] = $result[0]['VWpicID'];
    	$record['vwvideoid'] = $result[0]['VWVideoID'];
    	$record['videoparam'] = $result[0]['VWVideoParam'];    	
    	$record['viewnum'] = $result[0]['ViewNum'];
    	$record['pubtime'] = $result[0]['PubTime'];
    	$record['location'] = $result[0]['Location'];
    	$record['channels'] = $result[0]['Channels'];
    	$record['authority'] = $result[0]['Authority'];
    	return $record;
    }
    
    public function VWeibos(){
    	//$_POST['vweibos'] = '165,112,134';
    	if(empty($_POST['vweibos'])){
    		$this->ajaxReturn('ERR_PARAM_ILLEGAL');
    	}
    	$param = array();
    	$param['vweibos'] = $_POST['vweibos'];
    	$VWeibos = $this->vweiboModel->VWeibos($param);
    	if(is_array($VWeibos)){
    		$list = $this->VWeibosListInfo($VWeibos);
    		$this->ajaxReturn($list);
    	}elseif($list === null){
    		$this->ajaxReturn('ERR_LIST_NULL');
    	}else{
    		$this->ajaxReturn('ERR_LIST_FAIL');
    	}	
    }
    public function Report(){
    	A("User")->CheckLogin();
    	/*$_POST = array (
    	 'vweiboid' => 48
    	 
    	);*/
    	if(empty($_POST['vweiboid'])){
    		$this->ajaxReturn('ERR_PARAM_ILLEGAL');
    	}
    	$param = array();
    	$param['vweibos'] = $_POST['vweiboid'];    	
    	$result = $this->vweiboModel->VWeibos($param);
    	if(is_array($result)){
    		
    	}else{
    		$this->ajaxReturn('SUCCESS');
    	}
    	$count = $result[0]['RptNum'];
    	$count = $count +1;
    	$param = array();
    	$param['uid'] = $_POST['uid'];
    	$param['vweiboid'] = $_POST['vweiboid'];
    	$param['rptnum'] = $count;
    	$result = $this->vweiboModel->Report($param);
    	$this->ajaxReturn('SUCCESS');
    }
}