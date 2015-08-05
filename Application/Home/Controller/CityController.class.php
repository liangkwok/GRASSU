<?php
namespace Home\Controller;
use Think\Controller;
class CityController extends Controller {
	private	$cityModel =  NULL;
	public	function __construct(){
		$this->cityModel = D('City');
	}
	
	/**
	 * 获取城市列表
	 * @access public
	 * @param	NULL
	 * @return array
	 */
	public function CitysList(){
		$citys	=	$this->cityModel->CitysList();
		if(is_array($citys)){
			$CitysList = array();
			foreach ($citys as $key => $value){
				$tmp = array('cityid'=>$value['CityID'],'code'=>$value['Code'],'cityname'=>$value['CityName']);
				$CitysList[$key]["province"]=$value['PvcName'];
				$CitysList[$key]["cities"]=$tmp;
			}			
			$this->ajaxReturn($CitysList);
		}elseif($citys === null){
			$this->ajaxReturn('ERR_REG_CITYSLIST_NULL');
		}else{
			$this->ajaxReturn('ERR_REG_CITYSLIST_FAIL');
		}
	}
}