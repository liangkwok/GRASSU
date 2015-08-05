<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
	private	$userModel =  NULL;
	private	$cityModel =  NULL;	
	private	$fellowModel =  NULL;
	private	$praiseModel =  NULL;
	private	$vweiboModel =  NULL;
	private	$fowardModel =  NULL;
	private	$commentModel =  NULL;
	private	$ensembleModel =  NULL;
	private $AMQConf = NULL;
	private $serverModel = NULL;
	private $utilModel = NULL;
	
	public	function __construct(){
		
		$this->userModel = D('User');
		$this->cityModel = D('City');	
		$this->vweiboModel = D('VWeibo');
		$this->fellowModel = D('Fellow');
		$this->praiseModel = D('Praise');
		$this->fowardModel =  D('Foward');
		$this->commentModel =  D('Comment');	
		$this->ensembleModel =  D('Ensemble');
		$this->AMQConf = C('ASYNC_MESSAGE_QUEUE');		
		$this->serverModel = D('Server');
		$this->utilModel = D('Util');
		
	}
	public function CheckLogin(){
		if (!$this->userModel->CheckLogin()){
			$this->ajaxReturn('ERR_LOGIN_NOT');
		}
	}
	public function DeleteUser(){
		$param = array();
		$param['uid'] = $_POST['uid'];		
		$result  = 	$this->userModel->Delete($param);
		if($result){
			$this->ajaxReturn('SUCCESS');
		}else{
			$this->ajaxReturn('ERR_FAILED');
		}
	}
	/**
	 * 检测手机号码/昵称是否注册
	 * @access public
	 * @param string nick/mobile 
	 * @return int
	 */
	public function ChkRegister(){
		$nick	 =  $_POST['nick'];
		$mobile	 =  $_POST['mobile'];	
		$code = 'SUCCESS';
		if(!empty($nick)){
			$code = 'ERR_REG_NICK_EXIST';
		}elseif(!empty($mobile)){
			$code = 'ERR_REG_MOBILE_EXIST';		
		}else{			
			$this->ajaxReturn('ERR_UNDEFINED');
		}
		$param = array();
		$param['nick'] = $nick;
		$param['mobile'] = $mobile;
		$user  = 	$this->userModel->User($param);
		if(empty($user)){
			$this->ajaxReturn('SUCCESS');
		}else{
			$this->ajaxReturn($code);
		}
	}

	
	/**
	 * 用户注册
	 * @access public
	 * @param string	pwd
	 * @param string	mobile
	 * @return int
	 */
	public function Register(){		
		$mobile = $_POST['mobile'];
		$pwd = $_POST['pwd'];
		if(empty($mobile) || empty($pwd) ){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$param = array();
		$param['mobile'] = $mobile;
		$param['pwd'] = $pwd;	
		$user  = 	$this->userModel->User($param);
		if(!empty($user)){
			$this->ajaxReturn('ERR_REG_MOBILE_EXIST');
		}

		$result	 =  $this->userModel->Register($param);
		if($result){
			$_POST['uid'] = $result;
			///*注册完毕，默认被关注列表*/
			$tuids = explode(",", C("GRASSUA_USERS_FELLOWED"));
			foreach ($tuids as $key => $value){
				$param = array();
				$param['uid'] = $_POST['uid'];
				$param['status'] = 1;
				$param['tuid'] = intval($value);
				$res	=	$this->fellowModel->Fellow($param);
				if($res == true){
					$this->serverModel->Push($this->AMQConf['USER_FELLOW'],$param);								
				}else{
					continue;					
				}
			}
			file_put_contents("/tmp/debug.txt", var_export($tuids,true),FILE_APPEND);
			/*注册完毕，默认关注别人列表*/
			$uids = explode(",", C("GRASSUA_USERS_FELLOWS"));
			foreach ($uids as $key => $value){
				$param = array();
				$param['uid'] = intval($value);
				$param['tuid'] = $_POST['uid'];
				$param['status'] = 1;
				
				$res	=	$this->fellowModel->Fellow($param);
				if($res == true){
					$this->serverModel->Push($this->AMQConf['USER_FELLOW'],$param);
				}else{
					continue;
				}
			}
			file_put_contents("/tmp/debug.txt", var_export($uids,true),FILE_APPEND);
			//操作完毕后登录
			$this->Login();
		}else{
			$this->ajaxReturn('ERR_REG_FAIL');
		}
	}
	
	/**
	 * 获取用户信息
	 * @access public
	 * @param uid
	 * @return void
	 */
	public function User($param){
		if(empty($param['uid']) ){
			return false;
		}
		$record	 =  $this->userModel->User($param);
		if(is_array($record)){
			$array = array();
			$array['uid'] = $record['Uid'];
			$array['nick'] = $record['Unick'];
			$array['cityid'] = $record['U_CityID'];
			$array['email'] = $record['UEmail'];
			$array['mobile'] = $record['UMobile'];
			$array['gender'] = $record['UGender'];
			$array['avatar'] = $record['UAvatar'];//头像
			$array['uchannelid'] = $record['U_ChannelID'];
			$array['remarks'] = $record['URemarks'];
			return $array;
		}else{
			return false;
		}
	}
	
	/**
	 * 获取用户信息
	 * @access public
	 * @param int tuid
	 * @return array
	 */
	public	function Profile(){	
		/*$_POST=array(
				'uid'=>10103,
				'tuid'=>10104
		);*/
    	$uid = $_POST['uid'];
    	$tuid = $_POST['tuid'];
    	if(empty($_POST['tuid']) || empty($_POST['uid'])){
    		$this->ajaxReturn('ERR_PARAM_ILLEGAL');
    	}
    	
    	$param = array();
    	$param['uid'] = $tuid;
		$record	 =  $this->userModel->User($param);
		//var_dump($record);
		$returnArray = array();
		if(is_array($record)){
			////资料
			$returnArray['uid'] = $record['Uid'];
			$returnArray['nick'] = $record['Unick'];
			$returnArray['cityid'] = $record['U_CityID'];
			$returnArray['email'] = $record['UEmail'];
			$returnArray['mobile'] = $record['UMobile'];
			$returnArray['channelid'] = $record['U_ChannelID'];
			$returnArray['avatar'] = $record['UAvatar'];
			$returnArray['remarks'] = $record['URemarks'];
			//粉丝数量	
			$param = array();
			$param['tuid'] = $tuid;
			$count = $this->fellowModel->Count($param);
			if(is_int($count)){
				$returnArray['fellowednum'] = $count;
			}else{
				$returnArray['fellowednum'] = 0;
			}
			//关注数量
			$param = array();
			$param['uid'] = $tuid;
			$count = $this->fellowModel->Count($param);
			if(is_int($count)){
				$returnArray['fellowsnum'] = $count;
			}else{
				$returnArray['fellowsnum'] = 0;
			}
			//原创数量
			$count = $this->vweiboModel->Count($param);
			if(is_int($count)){
				$returnArray['vweibosnum'] = $count;
			}else{
				$returnArray['vweibosnum'] = 0;
			}
			//喜欢
			$count = $this->praiseModel->Count($param);
			if(is_int($count)){
				$returnArray['praisenum'] = $count;
			}else{
				$returnArray['praisenum'] = 0;
			}
			
			//转发
			$count = $this->fowardModel->Count($param);
			if(is_int($count)){
				$returnArray['fowardnum'] = $count;
			}else{
				$returnArray['fowardnum'] = 0;
			}
			
			//评论
			$count = $this->commentModel->Count($param);
			if(is_int($count)){
				$returnArray['commentnum'] = $count;
			}else{
				$returnArray['commentnum'] = 0;
			}
			
			//评论
			$count = $this->ensembleModel->Count($param);
			if(is_int($count)){
				$returnArray['ensemblenum'] = $count;
			}else{
				$returnArray['ensemblenum'] = 0;
			}
			//与本人的关注状态
			$param = array();
			$param['uid'] = $uid;
			$param['tuid'] = $tuid;
			$result = $this->fellowModel->Relation($param);
			if(is_array($result) && $result['FStatus']==1){
				$returnArray['status'] = 1;
			}else{
				$returnArray['status'] = 0;
			}
			
			//是否可以看到此人的合拍、原创等信息
			if($_POST['uid'] == $_POST['tuid']){
				$returnArray['viewauthority'] = 1;
			}else{				
				$param = array();
				$param['uid'] = $uid;
				$param['tuid'] = $tuid;
				$result = $this->fellowModel->Relation($param);
				if(is_array($result) && $result['FStatus']==1){
					$returnArray['status'] = 1;
				}else{
					$returnArray['status'] = 0;
				}
				$param = array();
				$param['uid'] = $tuid;
				$sets = D("Sets")->Lists($param);
				//var_dump($sets);
				if(!$sets){
					$returnArray['viewauthority'] = 0;
				}else{
					//$sets['cotent_viewauthority'] = '102';
					switch ($sets['cotent_viewauthority']){
						case '100':{//所有人可见
							$returnArray['viewauthority'] = 1;
						}
						break;
						case '101':{//我关注的人可见
							$param = array();
							$param['uid'] = $tuid;
							$param['tuid'] = $uid;
							$result = $this->fellowModel->Relation($param);
							if(is_array($result) && $result['FStatus']==1){								
								$returnArray['viewauthority'] = 1;
							}else{
								$returnArray['viewauthority'] = 0;
							}
						}
						break;
						case '102':{//好友可见
							$param = array();
							$param['uid'] = $tuid;
							$param['tuid'] = $uid;
							$result = $this->fellowModel->Relation($param);
							if(is_array($result) && $result['FStatus']==1){	
								if($returnArray['status'] == 1){
									$returnArray['viewauthority'] = 1;
								}else{
									$returnArray['viewauthority'] = 0;
								}					
								
							}else{
								$returnArray['viewauthority'] = 0;
							}
						}
						break;
						case '103':{//仅自己可见
							$returnArray['viewauthority'] = 0;
						}
						break;
						default:	
					}
				}
			}
			$this->ajaxReturn($returnArray);
		}else if($record === null){
    		$this->ajaxReturn('ERR_USER_UNEXIST');
    	}else{
    		$this->ajaxReturn('ERR_USER_QUERY_FAIL');
    	}
	}
	
	/**
	 * 用户登录
	 * @access public
	 * @param nick/mobile
	 * @param pwd
	 * @return array
	 */
	public function Login(){
		/*$_POST =array(
			'mobile'=>'11111111111',
			'pwd' =>'111'
		);*/
	
		$nick = $_POST['nick'];
		$mobile = $_POST['mobile'];
		
		$pwd = $_POST['pwd'];
		if (empty($nick) && empty($mobile)) {
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}		
		if (empty($pwd)) {
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		if(!empty($nick) || !empty($mobile)){
			$param = array();			
			$param['nick'] = $nick;
			$param['mobile'] = $mobile;
			$record = $this->userModel->User($param);

			if(is_array($record)){
				$uid = $record['Uid'];
				$pwd = $record['UPwd'];
				if ($pwd != $_POST['pwd']){
					$this->ajaxReturn('ERR_LOGIN_PASSWORD_FAIL');
				}
				$llogindate = $record['ULLoginDate'];;
				$signature = $this->createSign($uid.$pwd);
				$result = $this->userModel->Login($uid,$pwd,$signature);

				if ($result) {
					$returnArray = array();
					$returnArray['uid'] = $record['Uid'];					
					$returnArray['token'] = $signature;
					$this->ajaxReturn($returnArray);
				}else{
					$this->ajaxReturn('ERR_LOGIN_FAIL');
				}
			}elseif($record === null){
				$this->ajaxReturn('ERR_LOGIN_USER_UNEXIST');
			}else{
				$this->ajaxReturn('ERR_LOGIN_FAIL');
			}
		}		
	}
	
	public function ThirdLogin(){
		/*$_POST =array(
		 'platform'=>'weibo',
		 'serial' =>'27340607571'
		);*/
		unset( $_POST['token']);
		unset( $_POST['uid']);
		$platform = $_POST['platform'];
		$serial = $_POST['serial'];
		if (empty($platform) && empty($serial)) {
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}

		$param = array();
		$param['platform'] = $platform;
		$param['serial'] = $serial;
		$record = $this->userModel->ThirdUser($param);
		//var_dump($record);
		if(is_array($record)){
			$uid = $record['Uid'];
			$third = $record['UThird'];
			$pwd = $record['UPwd'];
			$llogindate = $record['ULLoginDate'];;
			$signature = $this->createSign($uid.$third);
			$result = $this->userModel->Login($uid,$pwd,$signature);
			
			if ($result) {
				$returnArray = array();
				$returnArray['uid'] = $record['Uid'];
				$returnArray['token'] = $signature;
				$returnArray['type'] = 101;
				$this->ajaxReturn($returnArray);
			}else{
				$this->ajaxReturn('ERR_LOGIN_FAIL');
			}
		}elseif($record == null){
			$result	 =  $this->userModel->ThirdRegister($param);
			//var_dump($result);
			if ($result){
				$uid = $result;
				
				///*注册完毕，默认被关注列表*/
				$tuids = explode(",", C("GRASSUA_USERS_FELLOWED"));
				foreach ($tuids as $key => $value){
					$param = array();
					$param['uid'] = $_POST['uid'];
					$param['status'] = 1;
					$param['tuid'] = intval($value);
					$res	=	$this->fellowModel->Fellow($param);
					if($res == true){
						$this->serverModel->Push($this->AMQConf['USER_FELLOW'],$param);
					}else{
						continue;
					}
				}
				/*注册完毕，默认关注别人列表*/
				$uids = explode(",", C("GRASSUA_USERS_FELLOWS"));
				foreach ($uids as $key => $value){
					$param = array();
					$param['uid'] = intval($value);
					$param['tuid'] = $_POST['uid'];
					$param['status'] = 1;
				
					$res	=	$this->fellowModel->Fellow($param);
					if($res == true){
						$this->serverModel->Push($this->AMQConf['USER_FELLOW'],$param);
					}else{
						continue;
					}
				}
				
				$third = $param['platform']."_".$param['serial'];    	
				$pwd = '';
				$llogindate = date("Y-m-d H:i:s");;
				$signature = $this->createSign($uid.$third);
				$result = $this->userModel->Login($uid,$pwd,$signature);
					
				if ($result) {
					$returnArray = array();
					$returnArray['uid'] =strval( $uid);
					$returnArray['token'] = $signature;
					$returnArray['type'] = 100;
					$this->ajaxReturn($returnArray);
				}else{
					$this->ajaxReturn('ERR_LOGIN_FAIL');
				}
			}else{
				$this->ajaxReturn('ERR_LOGIN_FAIL');
			}
		}else{
			$this->ajaxReturn('ERR_LOGIN_FAIL');
		}
		
	}
	
	/**
	 * 用户登出
	 * @access public
	 * @param uid
	 * @param token
	 * @return void
	 */
	public function Logout(){
		$uid = $_POST['uid'];
		$token = $_POST['token'];
		if (empty($token)) {
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		if(!empty($uid)){
			$param = array();
			$param['uid'] = $uid;			
			$record = $this->userModel->User($param);
			if(is_array($record)){
				$uid = $record['Uid'];
				$signature = '';
				$result = $this->userModel->Logout($uid,$token,$signature);
				if ($result) {
					$this->ajaxReturn('SUCCESS');
				}else{
					$this->ajaxReturn('ERR_LOGIN_FAIL');
				}
			}elseif($record === null){
				$this->ajaxReturn('ERR_LOGIN_USER_UNEXIST');
			}else{
				$this->ajaxReturn('ERR_LOGIN_FAIL');
			}
		}
	}
	
	/**
	 * 用户更新资料
	 * @access public
	 * @param nick/mobile
	 * @param pwd
	 * @return void
	 */
	public function Update(){
		/*$_POST=array (
		  'uid' => '5',
		  'token' => '5717868c8dfa0fbd57e4b87d266f2b84',
		  'nick' => 'rong',
		  'avatar' => 'http://grassuz.qiniudn.com/icon.png',
		  'gender' => '1',
		  'cityid' => '123',
		);*/
		$param = array();
		$param['uid'] = $_POST['uid'];
		$param['token'] = $_POST['token'];
		$param['nick'] = $_POST['nick'];
		$param['avatar'] = $_POST['avatar'];
		$param['gender'] = $_POST['gender'];
		$param['cityid'] =  $_POST['cityid'];
		$param['email'] =  $_POST['email'];
		$param['mobile'] =  $_POST['mobile'];
		$param['remarks'] =  $_POST['remarks'];
		if ($this->utilModel->IsDirty($param['nick'])){
			$this->ajaxReturn('ERR_CONTENT_ILLEGAL');
		}
		if ($this->utilModel->IsDirty($param['remarks'])){
			$this->ajaxReturn('ERR_CONTENT_ILLEGAL');
		}
		$result = $this->userModel->Update($param);
		if($result){
			$this->ajaxReturn('SUCCESS');
		}else{
			$this->ajaxReturn('ERR_UPDATE_FAIL');
		}
	}
	
	/**
	 * 用户更新密码
	 * @access public
	 * @param string mobile
	 * @param string oldpwd
	 * @param string newpwd
	 * @return void
	 */
	public function UpPassword(){
		/*$_POST=array (
		 'uid' => '5',
		 'token' => '5717868c8dfa0fbd57e4b87d266f2b84',
		 'nick' => 'rong',
		 'avatar' => 'http://grassuz.qiniudn.com/icon.png',
		 'gender' => '1',
		 'cityid' => '123',
		);*/
		/*$_POST = array(
				'mobile'=>15123884071,
				'newpwd'=>1,
				'oldpwd'=>''
		);*/
		$param = array();
		$param['mobile'] = $_POST['mobile'];
		$param['oldpwd'] = $_POST['oldpwd'];	//可选	
		$param['newpwd'] =  $_POST['newpwd'];
		$result = $this->userModel->User($param);
		if (is_array($result)){
			if (empty($param['oldpwd'])){
				//var_dump(1);
				$result = $this->userModel->UpPassword($param);
				if (($result)){
					$this->ajaxReturn('SUCCESS');
				}else{
					$this->ajaxReturn('ERR_UPDATE_PWD_FAIL');
				}
			}elseif($result['UPwd'] == $param['oldpwd']){
				//var_dump(2);
				$result = $this->userModel->UpPassword($param);
				if (($result)){
					$this->ajaxReturn('SUCCESS');
				}else{
					$this->ajaxReturn('ERR_UPDATE_PWD_FAIL');
				}
			}else{
				$this->ajaxReturn('ERR_UPDATE_PWD_WRONG');
			}
		}elseif($result === null){
			$this->ajaxReturn('ERR_USER_UNEXIST');
		}else{
			$this->ajaxReturn('ERR_UPDATE_PWD_FAIL');
		}
	}
	
	
	public function ChkLogin(){		
		$_POST=array (
		 'uid' => '5',
		 'token' => '5717868c8dfa0fbd57e4b87d266f2b84'
		
		);
		if(empty($_POST['uid']) || empty($_POST['token'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$param = array();
		$param['uid'] = $_POST['uid'];
		$param['token'] = $_POST['token'];
		$result = $this->userModel->ChkLogin($param);
		if($result){
			return true;
		}else{
			return false;
		}		
	}
	
	public function Search(){
		/*$_POST=array (
				'words' => '3'
		);*/
		if(empty($_POST['words'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}
		$param = array();
		$param['words'] = $_POST['words'];
		$total	=	$this->userModel->Count($param);
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
		$param['words'] = $_POST['words'];
		$param['begin'] =  $begin;
		$param['end'] = $end;
		$param['pagenum'] = $pagenum;
		$result	=	$this->userModel->Lists($param);
		if (is_array($result)){
			$list = array();
			foreach ($result as $key => $value){
				$list[$key]['uid'] = $value['Uid'];
				$list[$key]['nick'] = $value['Unick'];				
				$list[$key]['channelid'] = $value['U_ChannelID'];
				$list[$key]['cityid'] = $record['U_CityID'];
				$list[$key]['email'] = $record['UEmail'];
				$list[$key]['mobile'] = $record['UMobile'];
				$list[$key]['avatar'] = $value['UAvatar'];
				$list[$key]['remarks'] = $value['URemarks'];				
			}
			$returnArray = array();
			$returnArray['total'] = $total;
			$returnArray['page'] = $page;
			$returnArray['pagenum'] = count($list);
			$returnArray['list'] = array_values($list);
			$this->ajaxReturn($returnArray);
		}elseif($result == null){
			$this->ajaxReturn("ERR_TOPIC_LIST_NULL");
		}else{
			$this->ajaxReturn("ERR_TOPIC_LIST_FAIL");
		}
	}	
}
