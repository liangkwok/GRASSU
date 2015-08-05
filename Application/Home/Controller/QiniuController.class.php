<?php
namespace Home\Controller;
use Think\Controller;

class QiniuController extends Controller {
	public	function __construct(){
		
	}
	
	/**
	 * qiniu
	 * @access mobile
	 * @return void
	 */
	public function Token(){
		//var_dump(APP_PATH);
		if(file_exists(APP_PATH.'Home/Common/qiniu/qiniu/qiniu/rs.php'))
		{
			echo __LINE__;
			require_once APP_PATH.'Home/Common/qiniu/qiniu/qiniu/rs.php';
		}
		else{
			echo __LINE__;
		}

		
		$bucket = 'grassuz';
		$accessKey = 'Ooc3G1APJIOxMixKio1nfOFUgi4_JvTG-Zb9f407';
		$secretKey = 'XNkVhsIoJ7oxCCq82bKQz3Gsor2iXgdx-5NWNgVo';
		Qiniu_SetKeys($accessKey, $secretKey);
		$putPolicy = new Common\qiniu\qiniu\qiniu\Qiniu_RS_PutPolicy($bucket);
		$Token = $putPolicy->Token(null);
		$resultArray = array();
		$resultArray['qiniutoken'] = $Token;
		$this->ajaxReturn($resultArray);
		
	}
}