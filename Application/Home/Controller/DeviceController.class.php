<?php
namespace Home\Controller;
use Think\Controller;
class DeviceController extends Controller {
	private	$userModel =  NULL;
	private	$deviceModel =  NULL;
	private	$logInstance =  NULL;
	
	public	function __construct(){
		A("User")->CheckLogin();
		$this->userModel = D('User');
		$this->deviceModel = D('Device');
		$this->logInstance =  D('Log');
	}
	/**
	 * 更新用户设备号
	 * @access public
	 * @param string mobile
	 * @return void
	 */
	public function Report(){	
		/*$result = $this->ChkLogin();
		if(!$result){
			$this->ajaxReturn('ERR_LOGIN_NOT');
		}
		$uuid = $_POST['uuid'];
		if(empty($uuid)){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}*/
		//channel_id与user_id
		/*$_POST = array(	
				'uid' => 6,			
				'appid'=>'5316726',
				'channel_id' => '5112383950553808255',
				'user_id'=>'595926028594133711',
				'source' =>100 //100-IOS,200-Android
		);*/
		if(empty($_POST['appid']) || empty($_POST['channel_id']) || empty($_POST['user_id']) || empty($_POST['source'])){
			$this->ajaxReturn('ERR_PARAM_ILLEGAL');
		}		
		$result = $this->deviceModel->Update($_POST);
		if($result){
			$this->ajaxReturn('SUCCESS');
		}else{
			$this->ajaxReturn('ERR_FAILED');
		}
		
        
    }
}