<?php
namespace Home\Controller;
use Think\Controller;
class CommentController extends Controller {
	private	$userModel =  NULL;
	private	$commentModel =  NULL;
	private	$logInstance =  NULL;
	private $AMQConf = NULL;
	private $serverModel = NULL;
	private $utilModel = NULL;
	
	public	function __construct(){
		A("User")->CheckLogin();
		$this->userModel = D('User');
		$this->commentModel = D('Comment');
		$this->AMQConf = C('ASYNC_MESSAGE_QUEUE');
		$this->logInstance =  D('Log');
		$this->serverModel = D('Server');
		$this->utilModel = D('Util');
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
	public function Comment(){
		/*$result = $this->ChkLogin();
		if(!$result){
			$this->ajaxReturn('ERR_LOGIN_NOT');
		}
		$result = $this->ChkAuthority();
		if(!$result){
			$this->ajaxReturn('ERR_COMMENT_NO_AUTHORITY');
		}*/
		/*$_POST = array(
				'uid' => 8,
				'vweiboid' => 92,
				'content' =>"那些年，我们一起追过的女孩",
				'atlist'=>"9,10"
		);*/
		$param = array();
		$param['uid'] = $_POST['uid'];
		$param['vweiboid'] = $_POST['vweiboid'];
		$param['content'] = $_POST['content'];
		if ($this->utilModel->IsDirty($param['content'])){
			$this->ajaxReturn('ERR_CONTENT_ILLEGAL');
		}
		$param['atlist'] = isset($_POST['atlist'])?'':$_POST['atlist'];
		$param['status'] = 1;
		
		$result	=	$this->commentModel->Comment($param);
		if($result){    	
			$param['commentid'] = $result;
			$param['time'] = date("Y-m-d H:i:s");
    		$this->serverModel->Push($this->AMQConf['COMMENT_VWEIBO'],$param);
    		$this->ajaxReturn('SUCCESS');
    	}else{
    		$this->ajaxReturn('ERR_COMMENT_FAIL');
    	}
	}
	
	/**
	 * 用户评论合拍
	 * @access mobile
	 * @return void
	 */
	public function DelComment(){
		/*$result = $this->ChkLogin();
			if(!$result){
			$this->ajaxReturn('ERR_LOGIN_NOT');
			}
			$result = $this->ChkAuthority();
			if(!$result){
			$this->ajaxReturn('ERR_COMMENT_NO_AUTHORITY');
			}*/
		/*$_POST = array(
				'commentid' => 1				
		);*/
		$param = array();
		//$param['uid'] = $_POST['uid'];
		//$param['vweiboid'] = $_POST['vweiboid'];		
		$param['commentid'] = $_POST['commentid'];	
		$result	=	$this->commentModel->DelComment($param);
		if($result){
			//$this->serverModel->Push($this->AMQConf['COMMENT_VWEIBO'],$param);
			$this->ajaxReturn('SUCCESS');
		}else{
			$this->ajaxReturn('ERR_COMMENT_FAIL');
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
		 $this->ajaxReturn('ERR_COMMENT_NO_AUTHORITY');
		 }*/
		/*$_POST = array(
				'uid' => 22,
				'vweiboid' => 392
				
		);*/
		$param = array();		
		$param['vweiboid'] = $_POST['vweiboid'];
		$total	=	$this->commentModel->Count($param);
		//var_dump($total);
		if (is_int($total)){
		
		}else{
			$this->ajaxReturn('ERR_COMMENT_LIST_FAIL');
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
		$result	=	$this->commentModel->Lists($param);
		//var_dump($result);
		if(is_array($result)){			
			$Uids = array();
	    	foreach ($result as $key=>$value){
	    		if(!empty($value['C_Uid'])){
	    			array_push($Uids, $value['C_Uid']);
	    		}
	    	}	
	    	//var_dump($Uids);
	    	$Uids = implode(',', $Uids);
	    	$param = array();
	    	$param['uids'] = $Uids;
	    	//var_dump($Uids);
	    	$Users = $this->userModel->Users($param);
	    	if(is_array($Users)){
	    		
	    	}else{
	    		$this->ajaxReturn('ERR_COMMENT_LIST_FAIL');
	    	}	    	
	    	$list = array();
			foreach ($result as $key=>$value){
				foreach ($Users as $k=>$v){
					if($value['C_Uid'] == $v['Uid']){
						$list[$key]['commentid'] = $result[$key]['CommentID'];
						$list[$key]['vweiboid'] = $result[$key]['VWeiboID'];
						$list[$key]['cdate'] = $result[$key]['CDate'];
						$list[$key]['content'] = $result[$key]['Cotent'];
						$list[$key]['atlist'] = $result[$key]['AtList'];
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
	
	public function Comments($param){
		if(empty($param['commentids'])){
			return false;
		}
		$result = $this->commentModel->Comments($param);
		if(is_array($result)){
			 $list = array();
			 foreach ($result as $key=>$value){
			 	$list[$key]['commentid'] = $result[$key]['CommentID'];
				$list[$key]['vweiboid'] = $result[$key]['VWeiboID'];
				$list[$key]['cdate'] = $result[$key]['CDate'];
				$list[$key]['content'] = $result[$key]['Cotent'];
				$list[$key]['atlist'] = $result[$key]['AtList'];				
			 }
			 return $list;
		}else{
			return  false;
		}		
	}
}