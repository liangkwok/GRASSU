<?php
namespace Home\Controller;
use Think\Controller;
class ChannelController extends Controller {
	private	$channelModel =  NULL;
	public	function __construct(){
		$this->channelModel = D('Channel');
	}
	
	/**
	 * 城市列表
	 * @access mobile
	 * @return void
	 */
	public function Lists(){
		$channels	=	$this->channelModel->Lists();
		if(is_array($channels)){
			$List = array();
			foreach ($channels as $key => $value){
				$tmp = array(
						'channelid'=>$value['ChannelID'],
						'chgname'=>$value['ChgName'],
						'engname'=>$value['EngName'],
						'color'=>$value['Color'],
						'description'=>$value['Description']
				);
				array_push($List,$tmp);
			}			
			$this->ajaxReturn($List);
		}elseif($channels === null){
			$this->ajaxReturn('ERR_CHANNELS_NULL');
		}else{
			$this->ajaxReturn('ERR_CHANNELS_FAIL');
		}
	}
}