<?php
namespace Home\Controller;
use Think\Controller;
class InboxController extends Controller {
	private	$userModel =  NULL;
	private $inboxModel = null;
	private $fellowModel = null;
	private $AMQConf = NULL;
	
	
	
	public	function __construct(){
		parent::__construct();
		//A("User")->CheckLogin();
		$this->userModel = D('User');
		$this->inboxModel = D('Inbox');
		$this->fellowModel = D('Fellow');
		$this->AMQConf = C('ASYNC_MESSAGE_QUEUE');
		
	}
	
    public function Lists(){
    	/*$_POST = array(
    			'uid' => '22',
    			'token'=>'ee3f150c1cf8dddedb0faad6f6835a4b',
    			'type' =>'0',//1自己，0好友
    			'pagenum' => '8'
    	);*/
    	//file_put_contents("/tmp/debug.txt", var_export($_POST,true),FILE_APPEND);
    	if(empty($_POST['uid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		
		$param = array();
		$param['uid'] = $_POST['uid'];
		$param['type'] = $_POST['type'];
		$total	=	$this->inboxModel->Count($param);
		
		//var_dump($total);
		if (is_int($total)){
		
		}else{
			$this->ajaxReturn('ERR_MSG_LIST_FAIL');
		}
		$page = empty($_POST['page'])?1:$_POST['page'];
		$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];
		$begin = ($page-1) * $pagenum;
		$end = $page * $pagenum;
		if($end > $total){
			$end = $total;
		}
		$param =array();
		$param['uid'] = $_POST['uid'];
		$param['type'] = $_POST['type'];
		$param['begin'] =  $begin;
		$param['end'] = $end;
		$param['pagenum'] = $pagenum;
		$msgs	= $this->inboxModel->Lists($param);
		//var_dump($result);
		if(empty($msgs)){
			$this->ajaxReturn('ERR_MSG_LIST_NULL');
		}
		
		$Uids = array();
		foreach ($msgs as $key =>$value){
			array_push($Uids, $value['S_Uid']);
			array_push($Uids, $value['D_Uid']);
		}
		$Uids = array_unique($Uids);
		$Uids = implode(",", $Uids);
		$param = array();
		$param['uids'] = $Uids;
		$users = $this->userModel->Users($param);
		$param = array();
		$param['uid'] = $_POST['uid'];
		$param['tuids'] = $Uids;
		$relations = $this->fellowModel->Relations($param);
		
		if(is_array($users) ){
			$lists = array();
			foreach ($msgs as $key => $value){
				//消息的用户资料信息
				foreach ($users as $k => $v){
					if($value['S_Uid'] == $v['Uid']){						
						$lists[$key]['suser'] = array();
						$array = array();
						$array['uid'] = $v['Uid'];
						$array['nick'] = $v['Unick'];
						$array['gender'] = $v['UGender'];
						$array['avatar'] = $v['UAvatar'];//头像
						$array['uchannelid'] = $v['U_ChannelID'];						
						$array['remarks'] = $v['URemarks'];
						$array['status'] = 0;
						foreach ($relations as $k2 => $v2){//关注关系
							if($value['S_Uid'] == $v2['T_Uid']){
								$array['status'] = $v2['FStatus'];
							}
						}
						array_push($lists[$key]['suser'], $array);
					}
					if($value['D_Uid'] == $v['Uid']){
						$lists[$key]['duser'] = array();
						$array = array();
						$array['uid'] = $v['Uid'];
						$array['nick'] = $v['Unick'];
						$array['gender'] = $v['UGender'];
						$array['avatar'] = $v['UAvatar'];//头像
						$array['uchannelid'] = $v['U_ChannelID'];
						$array['remarks'] = $v['URemarks'];
						$array['status'] = 0;
						foreach ($relations as $k2 => $v2){//关注关系
							if($value['D_Uid'] == $v2['T_Uid'] ){
								$array['status'] = $v2['FStatus'];
							}
						}
						array_push($lists[$key]['suser'], $array);
					}
				}
				//依据消息类型处理
				$lists[$key]['otype'] = $msgs[$key]['OType'];
				$lists[$key]['cdate'] = $msgs[$key]['CDate'];
				$lists[$key]['status'] = $msgs[$key]['Status'];
				
				
				if ($_POST['type'] == 1){
					$this->processMySelf($lists[$key],$msgs[$key]);
				}else{
					$this->processFellows($lists[$key],$msgs[$key]);
				}
				
			}
			$returnArray = array();
			$returnArray['total'] = $total;
			$returnArray['page'] = $page;
			$returnArray['pagenum'] = count($lists);
			$returnArray['list'] = array_values($lists);
			$this->ajaxReturn($returnArray);
		}elseif($list === null){
			$this->ajaxReturn('ERR_MSG_LIST_NULL');
		}else{
			$this->ajaxReturn('ERR_MSG_LIST_FAIL');
		}			
    }
    private function processMySelf(&$Msg,$SrcMsg){
    	switch ($Msg['otype']){
    		case $this->AMQConf['USER_FELLOW']:
	    		{
	    			$Msg['tips']="关注了你";
	    			break;
	    		}
    		case $this->AMQConf['VWEIBO_PUBLISH']:
    			{
    				
    			}
    		case $this->AMQConf['VWEIBO_ATLIST']:
    			{
    				$Msg['tips']="发布合拍时提到你";
    				$param = array();
    				$param['vweibos'] = $SrcMsg['D_VWeiboID'];
    				$VWeiboAction = A("VWeibo");
    				$record =  $VWeiboAction->VWeibo($param);
    				if(is_array($record)){
    					$Msg['vweibo']=array($record);
    				}else{
    					$Msg['vweibo'] = array();
    				}
    				break;
    			}
    		case $this->AMQConf['PRAISE_VWEIBO']:
    			{
    				$Msg['tips']="喜欢你的合拍";
    				$param = array();
    				$param['vweibos'] = $SrcMsg['D_VWeiboID'];
    				$VWeiboAction = A("VWeibo");
    				$record =  $VWeiboAction->VWeibo($param);
    				if(is_array($record)){
    					$Msg['vweibo']=array($record);
    				}else{
    					$Msg['vweibo'] = array();
    				}
    				break;
    			}
    		case $this->AMQConf['COMMENT_VWEIBO']:
    			{
    				$Msg['tips']="评论了你的合拍";
    				$param = array();
    				$param['vweibos'] = $SrcMsg['D_VWeiboID'];
    				$VWeiboAction = A("VWeibo");
    				$record =  $VWeiboAction->VWeibo($param);
    				if(is_array($record)){
    					$Msg['vweibo']=array($record);
    				}else{
    					$Msg['vweibo'] = array();
    				}
    				
    				$param = array();
    				$param['commentids'] = $SrcMsg['D_CommentID'];
    				$commentAction = A("Comment");
    				$record =  $commentAction->Comments($param);
    				if(is_array($record)){
    					$Msg['comment']=$record;
    				}else{
    					$Msg['comment'] = array();
    				}
    				break;    			
    			}
    		case $this->AMQConf['COMMENT_ATLIST']:
    			{
    				$Msg['tips']="评论合拍时提到你";
    				$param = array();
    				$param['vweibos'] = $SrcMsg['D_VWeiboID'];
    				$VWeiboAction = A("VWeibo");
    				$record =  $VWeiboAction->VWeibo($param);
    				if(is_array($record)){
    					$Msg['vweibo']=array($record);
    				}else{
    					$Msg['vweibo'] = array();
    				}
    				
    				$param = array();
    				$param['commentids'] = $SrcMsg['D_CommentID'];
    				$commentAction = A("Comment");
    				$record =  $commentAction->Comments($param);
    				if(is_array($record)){
    					$Msg['comment']=$record;
    				}else{
    					$Msg['comment'] = array();
    				}
    				break;    					 
    			}
    		case $this->AMQConf['FOWARD_VWEIBO']:
    			{
    				$Msg['tips']="转发了你的合拍";
    				$param = array();
    				$param['vweibos'] = $SrcMsg['D_VWeiboID'];
    				$VWeiboAction = A("VWeibo");
    				$record =  $VWeiboAction->VWeibo($param);
    				if(is_array($record)){
    					$Msg['vweibo']=array($record);
    				}else{
    					$Msg['vweibo'] = array();
    				}
    				break;
    			}
    		case $this->AMQConf['ENSEMBLE_VWEIBO']:
    			{
    				$Msg['tips']="加入了你的合拍";
    				$param = array();
    				$param['vweibos'] = $SrcMsg['D_VWeiboID'];
    				$VWeiboAction = A("VWeibo");
    				$record =  $VWeiboAction->VWeibo($param);
    				if(is_array($record)){
    					$Msg['vweibo']=array($record);
    				}else{
    					$Msg['vweibo'] = array();
    				}
    				break;
    			}
    	}
    }
    private function processFellows(&$Msg,$SrcMsg){
    	switch ($Msg['otype']){
    		case $this->AMQConf['USER_FELLOW']:
    			{
    				$Msg['tips']="关注了#";
    				$param = array();
    				$param['uid'] = $SrcMsg['D_Uid'];
    				$UserAction = A("User");
    				$User = $UserAction->User($param);
    				if(is_array($User)){
    					$Msg['duser']=array($User);
    				}else{
    					$Msg['duser'] = array();
    				}
    				break;
    			}
    		case $this->AMQConf['VWEIBO_PUBLISH']:
    			{
    				$Msg['tips']="发布合拍";
    				$param = array();
    				$param['vweibos'] = $SrcMsg['D_VWeiboID'];
    				$VWeiboAction = A("VWeibo");
    				$record =  $VWeiboAction->VWeibo($param);
    				if(is_array($record)){
    					$Msg['vweibo']=array($record);
    				}else{
    					$Msg['vweibo'] = array();
    				}
    				break;
    			}
    		case $this->AMQConf['VWEIBO_ATLIST']:
    			{
    				
    			}
    		case $this->AMQConf['PRAISE_VWEIBO']:
    			{
    				$Msg['tips']="喜欢#的合拍";
    				$param = array();
    				$param['uid'] = $SrcMsg['D_Uid'];
    				$UserAction = A("User");
    				$User = $UserAction->User($param);
    				if(is_array($User)){
    					$Msg['duser']=array($User);
    				}else{
    					$Msg['duser'] = array();
    				}
    				$param = array();
    				$param['vweibos'] = $SrcMsg['D_VWeiboID'];
    				$VWeiboAction = A("VWeibo");
    				$record =  $VWeiboAction->VWeibo($param);
    				if(is_array($record)){
    					$Msg['vweibo']=array($record);
    				}else{
    					$Msg['vweibo'] = array();
    				}
    				break;
    			}
    		case $this->AMQConf['COMMENT_VWEIBO']:
    			{
    				$Msg['tips']="评论了#的合拍";
    				$param = array();
    				$param['uid'] = $SrcMsg['D_Uid'];
    				$UserAction = A("User");
    				$User = $UserAction->User($param);
    				if(is_array($User)){
    					$Msg['duser']=array($User);
    				}else{
    					$Msg['duser'] = array();
    				}    				
    				
    				$param = array();
    				$param['vweibos'] = $SrcMsg['D_VWeiboID'];
    				$VWeiboAction = A("VWeibo");
    				$record =  $VWeiboAction->VWeibo($param);
    				if(is_array($record)){
    					$Msg['vweibo']=array($record);
    				}else{
    					$Msg['vweibo'] = array();
    				}
    
    				$param = array();
    				$param['commentids'] = $SrcMsg['D_CommentID'];
    				$commentAction = A("Comment");
    				$record =  $commentAction->Comments($param);
    				if(is_array($record)){
    					$Msg['comment']=$record;
    				}else{
    					$Msg['comment'] = array();
    				}
    				break;
    			}
    		case $this->AMQConf['COMMENT_ATLIST']:
    			{
    				
    			}
    		case $this->AMQConf['FOWARD_VWEIBO']:
    			{
    				$Msg['tips']="转发了#的合拍";
    				$param = array();
    				$param['uid'] = $SrcMsg['D_Uid'];
    				$UserAction = A("User");
    				$User = $UserAction->User($param);
    				if(is_array($User)){
    					$Msg['duser']=array($User);
    				}else{
    					$Msg['duser'] = array();
    				}
    				
    				$param = array();
    				$param['vweibos'] = $SrcMsg['D_VWeiboID'];
    				$VWeiboAction = A("VWeibo");
    				$record =  $VWeiboAction->VWeibo($param);
    				if(is_array($record)){
    					$Msg['vweibo']=array($record);
    				}else{
    					$Msg['vweibo'] = array();
    				}
    				break;
    			}
    		case $this->AMQConf['ENSEMBLE_VWEIBO']:
    			{
    				$Msg['tips']="加入了#的合拍";
    				$param = array();
    				$param['uid'] = $SrcMsg['D_Uid'];
    				$UserAction = A("User");
    				$User = $UserAction->User($param);
    				if(is_array($User)){
    					$Msg['duser']=array($User);
    				}else{
    					$Msg['duser'] = array();
    				}
    				
    				$param = array();
    				$param['vweibos'] = $SrcMsg['D_VWeiboID'];
    				$VWeiboAction = A("VWeibo");
    				$record =  $VWeiboAction->VWeibo($param);
    				if(is_array($record)){
    					$Msg['vweibo']=array($record);
    				}else{
    					$Msg['vweibo'] = array();
    				}
    				break;
    			}
    		case $this->AMQConf['TOPIC_ADD']:
    			{
    				$Msg['tips']="新的话题已经发布";
    				$param = array();
    				$param['topics'] = $SrcMsg['D_TopicID'];
    				$TopicAction = A("Topic");
    				$record =  $TopicAction->Topics($param);
    				if(is_array($record)){
    					$Msg['topics']=array($record);
    				}else{
    					$Msg['topics'] = array(array());
    				}
    				break;
    			}
    		case $this->AMQConf['TOPIC_RSS']:
    			{
    				$Msg['tips']="参加了新话题";
    				$param = array();
    				$param['topics'] = $SrcMsg['D_TopicID'];
    				$TopicAction = A("Topic");
    				$record =  $TopicAction->Topics($param);
    				if(is_array($record)){
    					$Msg['topics']=array($record);
    				}else{
    					$Msg['topics'] = array(array());
    				}
    				break;
    			}
    	}
    }

	public function UnreadCount(){
		/*$_POST = array(
		 'uid' => 6,		 
		);*/
		if(empty($_POST['uid'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$param = array();
		$param['uid'] = $_POST['uid'];		
		$total	=	$this->inboxModel->UnreadCount($param);
		
		//var_dump($total);
		if (is_int($total)){
			$returnArray['total'] = $total;
			$this->ajaxReturn($returnArray);
		}else{
			$this->ajaxReturn('ERR_FAIL');
		}
	}	
}