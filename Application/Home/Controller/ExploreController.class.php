<?php
namespace Home\Controller;
use Think\Controller;
class ExploreController extends Controller {
	private $vweiboModel = null;
	private $opModel = null;
	public	function __construct(){
		//A("User")->CheckLogin();
		$this->vweiboModel = D("VWeibo");
		$this->opModel = D("OP");
		//var_dump($this->vweiboModel);
		//var_dump($this->opModel);
	}

	public function Explores(){
		/*$data = array();
		$data['topic'] = array("topicid"=>"20","name"=>"卡农","picture"=>"");
		$data['discover'] = array("topicid"=>"20","name"=>"发现啪啪啪","picture"=>"");
		$data['citywides'] = array("topicid"=>"20","name"=>"同城嘿咻","picture"=>"");*/
		
		$data = array();
		 $data[] = "http://7xj2iz.com2.z0.glb.qiniucdn.com/topic20150612.png";
		 $data[] = "http://7xj2iz.com2.z0.glb.qiniucdn.com/discover20150612.png";
		 $data[] = "http://7xj2iz.com2.z0.glb.qiniucdn.com/local20150612.png";
		$this->ajaxReturn($data);
	}
	
	/*public function TopicLists(){
		$Topics = A("Topic");
		if (!$Topics){
			$this->ajaxReturn('ERR_LIST_FAIL');
		}
		return $Topics->Lists();
	}*/
	public function Topics(){
		/*$result = $this->ChkLogin();
		 if(!$result){
		 $this->ajaxReturn('ERR_LOGIN_NOT');
		 }
		 $result = $this->ChkAuthority();
		 if(!$result){
		 $this->ajaxReturn('ERR_COMMENT_NO_AUTHORITY');
		 }*/
		/*$_POST = array(
		 'uid' => 10100,
		 'topicid'=>'#',
		 'type' =>1
		);*/
		if (!isset($_POST['type'])){
			$_POST['type'] = 1;
		}
		if ($_POST['topicid'] == "#"){
			$Topics =  $this->opModel->Topics();
			//var_dump($Topics);
			if (is_array($Topics)){
				$_POST['topicid'] = $Topics[0]['TopicID'];
			}else{
				$this->ajaxReturn('ERR_EXPLORE_LIST_NULL');
			}			
		}
		//var_dump($_POST);
		$param = array();
		$param['topicid'] = intval($_POST['topicid']);
		$param['type'] = $_POST['type'];
		$result	=	$this->vweiboModel->Topics_Lists($param);
		//var_dump($result);
		if (is_array($result)){
			$page = empty($_POST['page'])?1:$_POST['page'];
			$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];
			$begin = ($page-1) * $pagenum;
			$end = $page * $pagenum;
			$total = $result['total'];
			$vweibos = $result['list'];
			if($end > $total){
				$end = $total;
			}
			//var_dump($vweibos);
			$vweibos = array_values($vweibos);
			foreach ($vweibos as $key=>$value){
				//var_dump($key);
				if($key < $begin || $key >= $end){
					unset($vweibos[$key]);
				}
			}
			//var_dump($vweibos);
			$VWeiboController = A("VWeibo");
			if(empty($VWeiboController)){
				$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
			}
			//var_dump($vweibos);
			$vweibos = $VWeiboController ->VWeibosListInfo($vweibos);	
			//var_dump($vweibos);
			if(is_array($vweibos)){
				$returnArray = array();
				$returnArray['total'] = $total;
				$returnArray['page'] = $page;
				$returnArray['pagenum'] = count($vweibos);
				$returnArray['list'] = array_values($vweibos);
				$this->ajaxReturn($returnArray);
			}elseif($vweibos === null){
				$this->ajaxReturn('ERR_EXPLORE_LIST_NULL');
			}else{
				$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
			}
	
		}elseif($result == null){
			$this->ajaxReturn('ERR_EXPLORE_LIST_NULL');
		}else{
			$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
		}
	}

	/**
	 * 城市列表
	 * @access mobile
	 * @return void
	 */
	public function Discovers(){
		//1最热，0最新
		
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
		 'topicid'=>111,
		 'type' =>0
		);*/
		if (!isset($_POST['type'])){
			$_POST['type'] = 1;
		}
		$param = array();
		$param['type'] = $_POST['type'];
		$result	=	$this->vweiboModel->Discovers_Lists($param);
		//var_dump($result);
		if (is_array($result)){
			$page = empty($_POST['page'])?1:$_POST['page'];
			$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];
			$begin = ($page-1) * $pagenum;
			$end = $page * $pagenum;
			$total = $result['total'];
			$vweibos = $result['list'];
			if($end > $total){
				$end = $total;
			}
			
			foreach ($vweibos as $key=>$value){
				if($key < $begin || $key >= $end){
					unset($vweibos[$key]);
				}
			}
			$VWeiboController = A("VWeibo");
			if(empty($VWeiboController)){
				$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
			}
			//var_dump($vweibos);
			$vweibos = $VWeiboController ->VWeibosListInfo($vweibos);			
			if(is_array($vweibos)){
				$returnArray = array();
				$returnArray['total'] = $total;
				$returnArray['page'] = $page;
				$returnArray['pagenum'] = count($vweibos);
				$returnArray['list'] = array_values($vweibos);
				$this->ajaxReturn($returnArray);
			}elseif($vweibos === null){
				$this->ajaxReturn('ERR_EXPLORE_LIST_NULL');
			}else{
				$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
			}
	
		}elseif($result == null){
			$this->ajaxReturn('ERR_EXPLORE_LIST_NULL');
		}else{
			$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
		}
	}
		
	public function Citywides(){
		//最新0，最近1
		/*$_POST = array(
		 'uid' => 10100,
		 'cityid' => 362,
		 'latitude'=> 40.006722,
		 'longitude' =>116.483780,
		 'type' =>1,
		 'pagenum'=>10
		);*/
		file_put_contents("/tmp/debug.txt", var_export($_POST,true),FILE_APPEND);
		if (!isset($_POST['type'])){
			$_POST['type'] = 1;
		}else{
			$_POST['type'] = intval($_POST['type']);
		}
		if ($_POST['type'] == 1){
			return $this->Citywides_Near();
		}else{
			return $this->Citywides_New();
		}		
	}
	
	private function Citywides_Near(){
		$param = array();
		$param['type'] = ($_POST['type']);
		$param['cityid'] = $_POST['cityid'];
		$result	=	$this->vweiboModel->Citywides_Lists($param);
		//var_dump($result);
		if (is_array($result)){
			$page = empty($_POST['page'])?1:$_POST['page'];
			$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];
			$begin = ($page-1) * $pagenum;
			$end = $page * $pagenum;
			$total = $result['total'];
			$vweibos = $result['list'];
			if($end > $total){
				$end = $total;
			}		
				
			foreach ($vweibos as $key=>$value){
				$latitude1 = $_POST['latitude'];
				$longitude1 = $_POST['longitude'];
				$latitude2 = $value['Latitude'];
				$longitude2 = $value['Longitude'];
				$vweibos[$key]['distance'] = $this->GetDistance($latitude1, $longitude1, $latitude2, $longitude2);
			}
				
		
			$distance = array();
			foreach ($vweibos as $vweibo) {
				$distance[] = $vweibo['distance'];
			}
			array_multisort($distance, SORT_ASC , $vweibos);
			//var_dump($vweibos);
				
			foreach ($vweibos as $key=>$value){
				if($key < $begin || $key >= $end){
					unset($vweibos[$key]);
				}
			}
			$VWeiboController = A("VWeibo");
			if(empty($VWeiboController)){
				$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
			}
			//var_dump($vweibos);
			$vweibos = $VWeiboController ->VWeibosListInfo($vweibos);
			if(is_array($vweibos)){
				$returnArray = array();
				$returnArray['total'] = $total;
				$returnArray['page'] = $page;
				$returnArray['pagenum'] = count($vweibos);
				$returnArray['list'] = array_values($vweibos);
				$this->ajaxReturn($returnArray);
			}elseif($vweibos === null){
				$this->ajaxReturn('ERR_EXPLORE_LIST_NULL');
			}else{
				$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
			}
		
		}elseif($result == null){
			$this->ajaxReturn('ERR_EXPLORE_LIST_NULL');
		}else{
			$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
		}
	}
	
	private function Citywides_New(){
		$param = array();
		$param['type'] = ($_POST['type']);
		$param['cityid'] = $_POST['cityid'];
		$result	=	$this->vweiboModel->Citywides_Lists($param);
		//var_dump($result);
		if (is_array($result)){
			$page = empty($_POST['page'])?1:$_POST['page'];
			$pagenum = empty($_POST['pagenum'])?10:$_POST['pagenum'];
			$begin = ($page-1) * $pagenum;
			$end = $page * $pagenum;
			$total = $result['total'];
			$vweibos = $result['list'];
			if($end > $total){
				$end = $total;
			}
		
			foreach ($vweibos as $key=>$value){
				if($key < $begin || $key >= $end){
					unset($vweibos[$key]);
				}
			}
			$VWeiboController = A("VWeibo");
			if(empty($VWeiboController)){
				$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
			}
			//var_dump($vweibos);
			$vweibos = $VWeiboController ->VWeibosListInfo($vweibos);
			if(is_array($vweibos)){
				$returnArray = array();
				$returnArray['total'] = $total;
				$returnArray['page'] = $page;
				$returnArray['pagenum'] = count($vweibos);
				$returnArray['list'] = array_values($vweibos);
				$this->ajaxReturn($returnArray);
			}elseif($vweibos === null){
				$this->ajaxReturn('ERR_EXPLORE_LIST_NULL');
			}else{
				$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
			}
		
		}elseif($result == null){
			$this->ajaxReturn('ERR_EXPLORE_LIST_NULL');
		}else{
			$this->ajaxReturn('ERR_EXPLORE_LIST_FAIL');
		}
	}
	
	
	private function Distance($latitude1, $longitude1, $latitude2, $longitude2) {	
		$theta = $longitude1 - $longitude2;	
		$miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) *
				cos(deg2rad($theta)));	
		$miles = acos($miles);	
		$miles = rad2deg($miles);	
		$miles = $miles * 60 * 1.1515;	
		$kilometers = $miles * 1.609344;	
		$retData = sprintf("%.1f",$kilometers);	
		return $retData;
	}
	
	private function GetDistance($lat1, $lng1, $lat2, $lng2)
	{
		$EARTH_RADIUS = 6378.137;
		$radLat1 = ($lat1) * 3.1415926535898 / 180.0;
		//echo $radLat1;
		$radLat2 = ($lat2)* 3.1415926535898 / 180.0;
		$a = $radLat1 - $radLat2;
		$b = ($lng1)* 3.1415926535898 / 180.0 - ($lng2)* 3.1415926535898 / 180.0;
		$s = 2 * asin(sqrt(pow(sin($a/2),2) +
				cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
		$s = $s *$EARTH_RADIUS;
		$s = round($s * 10000) / 10000/1000;
		$s = sprintf("%.2f",$s);
		return $s;
	}
}