<?php
namespace Home\Controller;
use Think\Controller;
class TopicRssController extends Controller {
	private	$userModel =  NULL;
	private	$vweiboModel =  NULL;
	private	$topicModel =  NULL;
	private	$topicRssModel =  NULL;
	private	$ensembleModel =  NULL;
	private $serverModel = NULL;
	private $pConf = NULL;
	private $AMQConf = NULL;
	
	public	function __construct(){
		parent::__construct();
		$this->userModel = D('User');
		$this->vweiboModel = D('VWeibo');
		$this->topicModel = D('Topic');
		$this->topicRssModel = D('TopicRss');		
		$this->ensembleModel = D('Ensemble');
		$this->logInstance =  D('Log');
		$this->serverModel = D('Server');
	}
	
	/**
	 * 检测用户对某微博的点赞权限
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
		/*$testid = rand(1,100);
		$_POST = array('topicname'=>"test".$testid,'uid'=>$testid);*/
		if (empty($_POST['topicname']) || empty($_POST['uid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$param = array();
		$param['topicname'] = $_POST['topicname'];		
		$param['uid'] = $_POST['uid'];
		$result = $this->topicModel->Add($param);
		if($result){//增加成功
			//增加用户订阅			
			$result	=	$this->topicRssModel->Rss($param);			
			$this->ajaxReturn("SUCCESS");			
		}else{//失败
			$this->ajaxReturn('ERR_TOPIC_ADD_FAIL');			
		}
	}
	
	/**
	 * 用户取消点赞微博
	 * @access mobile
	 * @return void
	 */
	public function Rss(){
		$result = $this->ChkLogin();
		if(!$result){
			$this->ajaxReturn('ERR_LOGIN_NOT');
		}
		if (empty($_POST['topicname'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$param = array();
		$param['topicname'] = $_POST['topicname'];
		$param['uid'] = $_POST['uid'];
		
		//增加用户订阅
		$result	=	$this->topicRssModel->Rss($param);
		if($result){
			$this->ajaxReturn('SUCCESS');
		}else{
			$this->ajaxReturn('ERR_TOPIC_RSS_FAIL');
		}
	}
	
	/**
	 * 获取用户点赞微博的列表
	 * @access mobile
	 * @return void
	 */
	public function CancelRss(){
		$result = $this->ChkLogin();
		if(!$result){
			$this->ajaxReturn('ERR_LOGIN_NOT');
		}
		if (empty($_POST['topicname'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$param = array();
		$param['topicname'] = $_POST['topicname'];
		$param['uid'] = $_POST['uid'];
		
		//增加用户订阅
		$result	=	$this->topicRssModel->CancelRss($param);
		if($result){
			$this->ajaxReturn('SUCCESS');
		}else{
			$this->ajaxReturn('ERR_TOPIC_RSS_FAIL');
		}
	}
	
	/**
	 * 获取微博点赞的用户列表
	 * @access mobile
	 * @return void
	 */
	public function HotList(){
		if(empty($_POST['topicname']) || empty($_POST['page']) || empty($_POST['pagenum'])){			
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$begin = ($page-1) * $_POST['pagenum'];
		$end = $page * $_POST['pagenum'];
		$total	=	$this->topicRssModel->Count($param);
		if(is_int($total)){
				
		}else{
			$this->ajaxReturn('ERR_TOPIC_HOT_FAIL');
		}
		if($end > $total){
			$end = $total;
		}
		$param = array();
		$param['topicname'] = $_POST['topicname'];
		$param['begin'] =  $begin;
		$param['end'] = $end;
		$result	=	$this->topicModel->HotList($param);
		if(is_array($result)){
			$arr = array();
			foreach ($result as $k => $v){
				array_push($arr, $v['VWeiboID']);
			}
			$param =array();
			$param['vweibos'] = implode(",", $arr);
			$result =	$this->vweiboModel->VWeibos($param);
			if(is_array($result)){
				$list = array();
				$userIDs = array();
				foreach ($result as $key => $value){
					$tmp = array();
					$tmp['vweiboid'] = $value['VWeiboID'];
				
					$tmp['uid'] = "";
					$tmp['nick'] = "";
					$tmp['avatar'] = "";//头像
					$param = array();
					$param['uids'] = $value['V_Uid'];
					$users = $this->userModel->Users($param);
					if(is_array($users)){
						$tmp['uid'] = $users[0]['Uid'];
						$tmp['nick'] = $users[0]['Unick'];
						$tmp['avatar'] = $users[0]['Avatar'];//头像
					}
				
					$tmp['vwpicid'] = $value['VWpicID'];
					$tmp['vweiboid'] = $value['VWVideoID'];
					$tmp['viewnum'] = $value['ViewNum'];
					$tmp['pubtime'] = $value['PubTime'];
					$tmp['location'] = $value['Location'];
					$tmp['channelid'] = $value['ChannelID'];
					$tmp['authority'] = $value['Authority'];
					$tmp['content'] = $value['Content'];
					 
					$tmp['tpclist'] = '';//话题数组
					if (!empty($value['TopicList'])){
						$tmp['tpclist'] = explode(",",$value['TopicList']);
					}
					 
					$tmp['atlist'] = '';//@数组
					if (!empty($value['AtList'])){
						$param = array();
						$param['uids'] = $value['AtList'];
						$users = $this->userModel->Users($param);
						if(is_array($users)){
							$tmpArr =array();
							foreach ($users as $k=>$v){
								array_push($tmpArr,array($v['Uid'],$v['Unick']));
							}
							$tmp['atlist'] = $tmpArr;
						}
					}
					$tmp['ensembles'] = '';//合奏数组
					$ensembleList = $this->ensembleModel->EnsembledList($value['VWeiboID']);
					if(is_array($ensembleList)){
						$tmpArr =array();
						foreach ($ensembleList as $k=>$v){
							array_push($tmpArr,$v['E_Uid']);
						}
						$param = array();
						$param['uids'] = implode(",", $tmpArr);
						$users = $this->userModel->Users($param);
						if(!is_array($users)){
							$this->ajaxReturn('ERR_INDEX_FAIL');
						}else{
							$tmpArr =array();
							foreach ($users as $k=>$v){
								array_push($tmpArr,array($v['Uid'],$v['Unick']));
							}
							$tmp['ensembles'] = $tmpArr;
						}
					}
					/*$tmp['praisenum'] = $value['PraiseNum'];
					$tmp['fowardnum'] = $value['FowardNum'];
					$tmp['commentnum'] = $value['CommentNum'];
					$tmp['ensemblenum'] = $value['EnsembleNum'];*/
					array_push($list, $tmp);
				}
				$returnArray = array();
				$returnArray['total'] = $total;
				$returnArray['page'] = $page;
				$returnArray['pagenum'] = count($list);
				$returnArray['list'] = array_values($list);
				
				$this->ajaxReturn($returnArray);
			}elseif($result === null){
				$this->ajaxReturn('ERR_INDEX_NULL');
			}else{
				$this->ajaxReturn('ERR_PRAISED_LIST_FAIL');
			}
		}elseif($result === null){
			$this->ajaxReturn('ERR_INDEX_NULL');
		}else{
			$this->ajaxReturn('ERR_PRAISED_LIST_FAIL');
		}
	}
	/**
	 * 获取微博点赞的用户列表
	 * @access mobile
	 * @return void
	 */
	public function NewList(){
		$vweiboid = $_POST['vweiboid'];
		if(!empty($vweiboid)){
			$code = 'ERR_PARAM_ILLEGAL';
		}
		$result	= $this->topicModel->NewList($vweiboid);
		
		
		$this->vweiboModel->VWeibos($param);
		$this->userModel->Users($param);
		
		if(is_array($result)){
			$this->ajaxReturn($result);
		}elseif($result === null){
			$this->ajaxReturn('SUCCESS');
		}else{
			$this->ajaxReturn('ERR_PRAISED_LIST_FAIL');
		}
	}
}