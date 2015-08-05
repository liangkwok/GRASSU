<?php
/**
 * @desc 消息处理类 
 *
 * @author  
 * @version v1.0.0 
 * @package common
 */
require_once ( "/root/grassu/Server/Baidu-Push-SDK-Php-2.0.4-advanced/Channel.class.php" ) ;
class AsynMsgServer {  
	private static $_instance = null;
    private $grassuDB = null;
    private $operation = null;
    private $apiKey = null;
    private $secretKey = null;
    private $channel = null;
    
    public static function GetInstance($grassuDB){
        if(self::$_instance == null){
            self::$_instance = new self($grassuDB);
        }
    
        return self::$_instance;
    }
    
    /**
     * 构造函数
     *
     * @param object $this->grassuDB 数据库实例
     * @param void
     */
    private function __construct($grassuDB) {
    	$this->grassuDB = $grassuDB;
    	$this->grassuDB->connect();
    	$this->apiKey = "L1q0V4VBUS3RnoMIFXy5Uqdk";
    	$this->secretKey ="UZor3AOFWGPMxEMGlh96RNdydcGaeRi2";
    	$this->channel = new Channel( $this->apiKey, $this->secretKey ) ;
    }
    
    public function __clone(){
        exit('Clone is not allow!');
    }
	
	/**
	 * 服务器开始运行
	 * @return boolean
	 */
	public function Run() {
		while(true){
			sleep(2);
			$this->AsynCountVWeiboScore();
			$operations = $this->GetOperations();					
			if(empty($operations)){
				continue;
			}
			$this->HandleOperation($operations);			
		}	
		
	}
	/**
	 * 获取messages表中新增加的用户操作；
	 * @return array
	 */
	public function GetOperations(){
		$sql = "select * from messages where Status = 1 limit 10";
		$operations = $this->grassuDB->getAll($sql);
		//var_dump($operations);
		return $operations;
	}
	
	
	/**
	 * 获取messages表中新增加的用户操作；
	 * @return array
	 */
	public function GetFellows($uid){
		if(empty($uid)){
			return false;
		}
		$sql = "select * from fellows where T_Uid =".$uid." and FStatus = 1";
		$fellows = $this->grassuDB->getAll($sql);
		return $fellows;
	}
	
	/**
	 * 循环处理消息；
	 * @return array
	 */
	public function HandleOperation($operations){
		$operationsID = array();
		foreach ($operations as $key => $value){
			$OType = $value['OType'];
			$operation = json_decode($value['Param'],true);
			$result = true;
			//file_put_contents("/tmp/debug.txt", var_export($value,true),FILE_APPEND);
			//file_put_contents("/tmp/debug.txt", var_export($OType,true),FILE_APPEND);
			switch($OType){
				case $GLOBALS['G_MsgType']['USER_FELLOW']:{
					$result = $this->ProcessFellow($operation);
					//$result = $this->PushUser($operation);
					break;
				}
				case $GLOBALS['G_MsgType']['CANCEL_FELLOW']:{
					$result = $this->ProcessCancelFellow($operation);
					//$result = $this->PushUser($operation);
					break;
				}
				case $GLOBALS['G_MsgType']['VWEIBO_PUBLISH']:{
					$result = $this->ProcessPublishWeibo($operation);
					//$result = PushVWeibo($operation);
					break;
				}
				case $GLOBALS['G_MsgType']['VWEIBO_DELETE']:{
					$result = $this->ProcessDeleteWeibo($operation);
					//$result = PushVWeibo($operation);
					break;
				}
				case $GLOBALS['G_MsgType']['PRAISE_VWEIBO']:{
					$result = $this->ProcessPraise($operation);
					//$result = PushPraise($operation);
					break;
				}
				case $GLOBALS['G_MsgType']['COMMENT_VWEIBO']:{
					$result = $this->ProcessComment($operation);
					//$result = PushComment($operation);
					break;
				}
				case $GLOBALS['G_MsgType']['FOWARD_VWEIBO']:{
					$result = $this->ProcessFoward($operation);
					//$result = PushFoward($operation);
					break;
				}
				case $GLOBALS['G_MsgType']['ENSEMBLE_VWEIBO']:{
					$result = $this->ProcessEnsemble($operation);
					//$result = PushEnsemble($operation);
					break;
				}				
				default:break;
			}
			//var_dump($result);
			//var_dump($value['ID']);
			array_push($operationsID, $value['ID']);
			if($result == false){
				file_put_contents("/tmp/ErrorIDS.txt", $value['ID']."\n",FILE_APPEND);
			}
		}
		$operationsID = implode(",", $operationsID);
		$this->grassuDB->query("update messages set Status = 0 where ID in (".$operationsID.")");
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////
	public function ProcessFellow($operation){
		$uid = $operation['uid'];
		$tuid = $operation['tuid'];
		$status = $operation['status'];
		$time = empty($operation['cdate'])?date("Y-m-d H:i:s"):$operation['cdate'];
		if(empty($uid) || empty($tuid)){
			return false;
		}
		//var_dump($operation);
		$param = array();
		$param['uid'] = $uid;
		$param['tuid'] = $tuid;
		$this->UpdateIndexs($GLOBALS['G_MsgType']['USER_FELLOW'],$param);
		//关注

		$result = $this->NoticeMe($uid,$GLOBALS['G_MsgType']['USER_FELLOW'],$tuid,0,0,0,$time);
		if($result){
			$result = $this->NoticeFellows($uid,$GLOBALS['G_MsgType']['USER_FELLOW'],$tuid,0,0,0,$time);
			if($result){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}			
		
	}
	/////////////////////////////////////////////////////////////////////////////////////////////
	public function ProcessCancelFellow($operation){
		$uid = $operation['uid'];
		$tuid = $operation['tuid'];
		$status = $operation['status'];
		$time = empty($operation['cdate'])?date("Y-m-d H:i:s"):$operation['cdate'];
		if(empty($uid) || empty($tuid)){
			return false;
		}
		//var_dump($operation);
		$param = array();
		$param['uid'] = $uid;
		$param['tuid'] = $tuid;		
		$this->UpdateIndexs($GLOBALS['G_MsgType']['CANCEL_FELLOW'],$param);		
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	public function ProcessPublishWeibo($operation){
		$uid = $operation['uid'];
		$vweiboid = empty($operation['vweiboid'])?"0":$operation['vweiboid'];
		//var_dump($vweiboid);
		$time = empty($operation['cdate'])?date("Y-m-d H:i:s"):$operation['cdate'];
		if(empty($uid) || empty($vweiboid)){
			return false;
		}
		//更新用户的合拍信息表
		$param=array();
		$param['uid']=$uid;
		$param['vweiboid'] = $vweiboid;
		$this->UpdateIndexs($GLOBALS['G_MsgType']['VWEIBO_PUBLISH'],$param);
		//发合拍通知好友关闭
		/*$result = $this->NoticeFellows($uid,$GLOBALS['G_MsgType']['VWEIBO_PUBLISH'],0,$vweiboid,0,0,$time);
		if($result){
			return true;
		}else{
			return false;
		}*/

		if(!empty($operation['atlist'])){
			$atArray = array_filter(explode(",", $operation['atlist']));
			foreach ($atArray as $key => $value){
				$result = $this->NoticeMe($uid,$GLOBALS['G_MsgType']['VWEIBO_ATLIST'],$value,$vweiboid,0,0,$time);
			}
		}
	
		if(!empty($operation['topiclist'])){
			$topiclist = array_filter(explode(",", $operation['topiclist']));
			$topiclist = implode(",", $topiclist);
			$sql = "select TopicID,UseCount from topics where TopicID in (".$topiclist.")";
			$result = $this->grassuDB->getAll($sql);
			if(is_array($result)){
				foreach ($result as $key => $value){
					$result[$key]['UseCount'] = $result[$key]['UseCount'] + 1 ;
					$sql = "update topics set UseCount = ".$result[$key]['UseCount']." where TopicID = ".$result[$key]['TopicID'];
					$this->grassuDB->query($sql);
				}
			}
		}
		
		return true;
	}
	
	public function ProcessDeleteWeibo($operation){
		$uid = $operation['uid'];
		$vweiboid = empty($operation['vweiboid'])?"0":$operation['vweiboid'];
		//var_dump($vweiboid);
		file_put_contents("/tmp/debug.txt", var_export($operation,true),FILE_APPEND);
		if(empty($uid) || empty($vweiboid)){
			return false;
		}
		//更新用户的合拍信息表
		$param=array();
		$param['uid']=$uid;
		$param['vweiboid'] = $vweiboid;
		
		//index
		$this->UpdateIndexs($GLOBALS['G_MsgType']['VWEIBO_DELETE'],$param);
		
		//喜欢
		$sql = "delete from praises where  VWeiboID = ".$vweiboid;
		$this->grassuDB->query($sql);
		//合奏
		$sql = "delete from ensembles where  V_VWeiboID = ".$vweiboid;
		$this->grassuDB->query($sql);
		//评论
		$sql = "delete from comments where  VWeiboID = ".$vweiboid;
		$this->grassuDB->query($sql);
		//转发 
		$sql = "delete from fowards where  VWeiboID = ".$vweiboid;
		$this->grassuDB->query($sql);
		
		//消息队列
		$sql = "delete from inboxs where  D_VWeiboID = ".$vweiboid;
		$this->grassuDB->query($sql);
		
		/*
		if(!empty($operation['atlist'])){
			$atArray = array_filter(explode(",", $operation['atlist']));
			foreach ($atArray as $key => $value){
				$result = $this->NoticeMe($uid,$GLOBALS['G_MsgType']['VWEIBO_ATLIST'],$value,$vweiboid,0,0,$time);
			}
		}
	
		if(!empty($operation['topiclist'])){
			$topiclist = array_filter(explode(",", $operation['topiclist']));
			$sql = "select TopicID,UseCount from topics where TopicID in (".$topiclist.")";
			$result = $this->grassuDB->getAll($sql);
			if(is_array($result)){
				foreach ($result as $key => $value){
					$result[$key]['UseCount'] = $result[$key]['UseCount'] + 1 ;
					$sql = "update topics set UseCount = ".$result[$key]['UseCount']." where TopicID = ".$result[$key]['TopicID'];
					$this->grassuDB->query($sql);
				}
			}
		}*/
	
		return true;
	}
	
	public function ProcessPraise($operation){
		$uid = $operation['uid'];
		$vweiboid = $operation['vweiboid'];
		$status = $operation['status'];
		$time = empty($operation['cdate'])?date("Y-m-d H:i:s"):$operation['cdate'];
		if(empty($uid) || empty($vweiboid)){
			return false;
		}
		
		//喜欢
		if($status ==1){
			$tuid = $this->GetUidByVWeibo($vweiboid);
			if (empty($tuid)){
				return false;
			}
			
			if($uid == $tuid){//自己的不做tong'zhi
				return true;
			}
			$result = $this->NoticeMe($uid,$GLOBALS['G_MsgType']['PRAISE_VWEIBO'],$tuid,$vweiboid,0,0,$time);
					
			if($result){
				$result = $this->NoticeFellows($uid,$GLOBALS['G_MsgType']['PRAISE_VWEIBO'],$tuid,$vweiboid,0,0,$time);
				if($result){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return true;
		}
	}
	public function ProcessComment($operation){
		$uid = $operation['uid'];
		$vweiboid = $operation['vweiboid'];
		$commentid = $operation['commentid'];
		$time = empty($operation['cdate'])?date("Y-m-d H:i:s"):$operation['cdate'];
		if(empty($uid) || empty($vweiboid) || empty($commentid)){
			return false;
		}
		//用户评论了某个合拍
		$tuid = $this->GetUidByVWeibo($vweiboid);
		if (empty($tuid)){
			return false;
		}
		if($uid == $tuid){//自己的不做tong'zhi
			return true;
		}
		
		$result = $this->NoticeMe($uid,$GLOBALS['G_MsgType']['COMMENT_VWEIBO'],$tuid,$vweiboid,$commentid,0,$time);
		
		if($result){
			$result = $this->NoticeFellows($uid,$GLOBALS['G_MsgType']['COMMENT_VWEIBO'],$tuid,$vweiboid,$commentid,0,$time);
			if($result){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	
		//合拍at列表
		/*if(!empty($operation['atlist'])){
			$atArray = array_filter(explode(",", $operation['atlist']));
			foreach ($atArray as $key => $value){
				$values = array($uid,$GLOBALS['G_MsgType']['COMMENT_ATLIST'],$value,$vweiboid,$commentid);
				$values = implode(",", $values);
				$sql = "insert into inboxs(S_Uid,OType,D_Uid,D_VWeiboID,D_CommentID) values(".$values.")";
				$this->grassuDB->query($sql);
			}
		}*/		
	}
	public function ProcessFoward($operation){
		$uid = $operation['uid'];
		$vweiboid = $operation['vweiboid'];
		$status = $operation['status'];
		$time = empty($operation['cdate'])?date("Y-m-d H:i:s"):$operation['cdate'];
		if(empty($uid) || empty($vweiboid)){
			return false;
		}
		//喜欢
		if($status ==1){
			$tuid = $this->GetUidByVWeibo($vweiboid);
			if (empty($tuid)){
				return false;
			}
			if($uid == $tuid){//自己的不做tong'zhi
				return true;
			}
			
			$result = $this->NoticeMe($uid,$GLOBALS['G_MsgType']['FOWARD_VWEIBO'],$tuid,$vweiboid,0,0,$time);
			
			if($result){
				$result = $this->NoticeFellows($uid,$GLOBALS['G_MsgType']['FOWARD_VWEIBO'],$tuid,$vweiboid,0,0,$time);
				if($result){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return true;
		}		
	}
	public function ProcessEnsemble($operation){		
		$uid = $operation['uid'];

		$evweiboid = $operation['vweiboid'];
		$ensemble = $operation['ensemble'];
		$time = empty($operation['cdate'])?date("Y-m-d H:i:s"):$operation['cdate'];
		if(empty($uid) || empty($ensemble)){
			return false;
		}
		file_put_contents("/tmp/debug.txt", var_export($operation,true),FILE_APPEND);
		$ensemble = array_filter(explode(",", $ensemble));
		//喜欢
		foreach ($ensemble as $key => $vweiboid){
			file_put_contents("/tmp/debug.txt", var_export($vweiboid,true),FILE_APPEND);
			$tuid = $this->GetUidByVWeibo($vweiboid);
			if (empty($tuid)){
				continue;
			}
			
			if($uid == $tuid){//自己的不做tong'zhi
				continue;
			}
			$result = $this->NoticeMe($uid,$GLOBALS['G_MsgType']['ENSEMBLE_VWEIBO'],$tuid,$evweiboid,0,0,$time);
					
			if($result){
				$result = $this->NoticeFellows($uid,$GLOBALS['G_MsgType']['ENSEMBLE_VWEIBO'],$tuid,$evweiboid,0,0,$time);
				continue;
			}
		}
	}
	

	/**
	 * 通知我；
	 * @return boolean
	 */
	public function NoticeMe($uid, $otype,$tuid = 0,$weiboid = 0,$commentid = 0,$topicid = 0,$time = 0){
		$tmp = array(
				'uid' => $tuid,
				'msgtype'=>1,
				'suid'=>$uid,
				'otype'=>$otype,
				'tuid'=>$tuid,
				'weiboid'=>$weiboid,
				'commentid'=>$commentid,
				'topicid'=>$topicid,
				'time'=>$time				
		);
		$MsgPushArray =array();
		array_push($MsgPushArray, $tmp);
		$values = "('".implode("','", array_values($tmp))."')";
		$sql = "insert into inboxs(U_Uid,MsgType,S_Uid,OType,D_Uid,D_VWeiboID,D_CommentID,D_TopicID,CDate) values".$values;
		//var_dump($values);
		$result = $this->grassuDB->query($sql);
		//var_dump($result);
		if (empty($result)){
			return false;
		}else{
			$this->BaiduPush($MsgPushArray);
			return true;
		}
	}
	/**
	 * 通知我的粉丝；
	 * @return boolea
	 */
	public function NoticeFellows($uid, $otype,$tuid = 0,$weiboid = 0,$commentid = 0,$topicid = 0,$time = 0){		
		$fellows = $this->GetFellows($uid);
		//var_dump($fellows);
		if (empty($fellows)){
			return false;
		}
		$values = array();
		$MsgPushArray =array();
		
		foreach ($fellows as $key => $value){			
			$tmp = array(
					'uid' => $value['F_Uid'],
					'msgtype'=>0,
					'suid'=>$uid,
					'otype'=>$otype,
					'tuid'=>$tuid,
					'weiboid'=>$weiboid,
					'commentid'=>$commentid,
					'topicid'=>$topicid,
					'time'=>$time
			);
			array_push($MsgPushArray, $tmp);
			$tmp = "('".implode("','", array_values($tmp))."')";
			array_push($values,$tmp);
		}
		if(count($values) == 0){
			return false;
		}
		$values = implode(",", $values);
		
		$sql = "insert into inboxs(U_Uid,MsgType,S_Uid,OType,D_Uid,D_VWeiboID,D_CommentID,D_TopicID,CDate) values".$values;
		//var_dump($sql);
		$result = $this->grassuDB->query($sql);
		if (empty($result)){
			return false;
		}else{
			//屏蔽好友信息推送
			//$this->BaiduPush($MsgPushArray);
			return true;
		}
	}
	
	/**
	 * 通过合拍id获取此weiboid的uid；
	 * @return boolea
	 */
	public function GetUidByVWeibo($vweiboid){
		if(empty($vweiboid)){
			return false;
		}
		$sql = "select V_Uid from vweibos where VWeiboID = " . $vweiboid;
		$result = $this->grassuDB->getAll($sql);
		if (is_array($result)){
			return  $result[0]['V_Uid'];
		}else{
			return false;
		}
	}    
	
	//此功能放在crontab里面更好，后续修改
	public  function AsynCountVWeiboScore(){
		/*
		 * 算法更正：S = (G+R+2D+4C) /(T+1)^1.5
		 * 算法考察单位时间内获得赞，转发，评论，合奏的效率，同时加入时间衰减因素
		 * 每小时给所有30天（可变参数）内发布的合拍重新计算一次分数S，按S进行降序排序
		 * S = L /(T+1)^1.5 * (1+G+R+2D+4C)
		 * L：合拍的总循环次数
		 * T：合拍发布截至当前的小时数取整（不足1小时按0小时计算，以此类推）
		 * G：合拍的喜欢总数
		 * R：合拍的转发总数，包含转发至我的空间以及成功转发至第三方平台的次数
		 * D：合拍的总评论条数
		 * C：基于此合拍的合奏次数，不计入其子合拍的合奏次数，与是否完整调用其组成内容无关
		 * */
		
		//$time = time();
		//if($time % 3600 == 0){
		$result = $this->grassuDB->getAll("select * from vweibos ");
		if (is_array($result)){
			foreach ($result as $key => $value){
				$vweiboid = $value['VWeiboID'];
				$L =$value['ViewNum'] ;
				$T = ceil((time() - strtotime($value['PubTime']))/3600);
				$G = 0;
				$record = $this->grassuDB->getAll("select count(*) as count from praises where VWeiboID = ".$vweiboid);
				if(is_array($record)){
					$G = $record[0]['count'];
				}
				
				$R = 0;
				$record = $this->grassuDB->getAll("select count(*) as count from fowards where VWeiboID = ".$vweiboid);
				if(is_array($record)){
					$R = $record[0]['count'];
				}
				
				$D = 0;
				$record = $this->grassuDB->getAll("select count(*) as count from comments where VWeiboID = ".$vweiboid);
				if(is_array($record)){
					$D = $record[0]['count'];
				}
				
				$C = 0;
				$record = $this->grassuDB->getAll("select count(*) as count from ensembles where VWeiboID = ".$vweiboid);
				if(is_array($record)){
					$C = $record[0]['count'];
				}
				
				$Score = 100000*($G*1+$D*2+$R*100+$C*4) /pow(($T+1),2);
				//var_dump($vweiboid."\t".$G."\t".$R."\t".$D."\t".$C."\t".$T."\t".pow(($T+1),1.5)."\t".$Score);
				$sql = "update vweibos set Score = ".$Score." where VWeiboID = ".$vweiboid;
				$this->grassuDB->query($sql);
			}
		}
		//}
	}
	
	
	public function BaiduPush($messages){
		//var_dump($messages);
		foreach ($messages as $key => $message){	
			$userset = $this->CheckUserSet($message);
			if(!$userset){
				continue;
			}				
			$device = $this->GetDeviceInfo($message['uid']);			
			if(empty($device)){
				continue;
			}
			//var_dump($device);
			$count = $this->GetUnreadMsgCount($message['uid']);
			if(!$count){
				$count = 0;
			}
			$message['count'] = $count;
			$message['tips'] = $this->GetPushTips($message);
			$message['userid'] = $device[0]['UserID'];
			$message['appid'] = $device[0]['AppID'];
			
			$type = $device[0]['Source'];
			var_dump($message);
			if($type == 100){
				$this->pushMessage_ios($message);
			}else{
				$this->pushMessage_android($message);
			}
		}		
	}
	
	public function GetDeviceInfo($uid){
		if(empty($uid)){
			return false;
		}
		$sql = "select * from devices where U_Uid = ".$uid;
		return $this->grassuDB->getAll($sql);
	}
	public function CheckUserSet($message){
		var_dump($message);
		$uid = $message['uid'];
		$msgtype = $message['msgtype'];
		$otype = $message['otype'];
		$sql = "select * from sets where Uid = ".$uid;
		$result = $this->grassuDB->getAll($sql);
		var_dump($result);
		if(!$result){
			return false;
		}
		$sets = json_decode($result[0]['Sets'],true);
		switch ($otype){
			case  $GLOBALS['G_MsgType']['USER_FELLOW']:
				if ($sets['notice_type_newfellow'] == '101'){
					return true;
				}else{
					return false;
				}
				break;

			case  $GLOBALS['G_MsgType']['VWEIBO_ATLIST']:
				if ($sets['notice_type_publishat'] == '101'){
					return true;
				}else{
					return false;
				}
				break;
			case  $GLOBALS['G_MsgType']['PRAISE_VWEIBO']:
				if ($sets['notice_type_praise'] == '101'){
					return true;
				}else{
					return false;
				}
				break;
			case  $GLOBALS['G_MsgType']['COMMENT_VWEIBO']:
				if ($sets['notice_type_comment'] == '101'){
					return true;
				}else{
					return false;
				}
				break;
			case  $GLOBALS['G_MsgType']['COMMENT_ATLIST']:
				if ($sets['notice_type_commentat'] == '101'){
					return true;
				}else{
					return false;
				}
				break;
			case  $GLOBALS['G_MsgType']['FOWARD_VWEIBO']:
				if ($sets['notice_type_foward'] == '101'){
					return true;
				}else{
					return false;
				}
				break;
			case  $GLOBALS['G_MsgType']['ENSEMBLE_VWEIBO']:
				if ($sets['notice_type_ensamble'] == '101'){
					return true;
				}else{
					return false;
				}					
			default:
				return false;
				break;
		}
	}
	public function GetPushTips($message){
		$uid = $message['uid'];
		$msgtype = $message['msgtype'];
		$otype = $message['otype'];	
		$tips = "用户";
		if ($msgtype == 1){
			$sql = "select * from users where Uid = ".$message['suid'];
			$result = $this->grassuDB->getAll($sql);
			
			if($result){
				$tips = $result[0]['Unick'];
			}
			
			switch ($otype){
				case  $GLOBALS['G_MsgType']['USER_FELLOW']:
					$tips = $tips."关注了你";
					break;
				case  $GLOBALS['G_MsgType']['VWEIBO_PUBLISH']:
					$tips = $tips."发布合拍";
					break;
				case  $GLOBALS['G_MsgType']['VWEIBO_ATLIST']:
					$tips = $tips."发布合拍时提到你";
					break;
				case  $GLOBALS['G_MsgType']['PRAISE_VWEIBO']:
					$tips = $tips."喜欢你的合拍";
					break;
				case  $GLOBALS['G_MsgType']['COMMENT_VWEIBO']:
					$tips = $tips."评论了你的合拍";
					break;
				case  $GLOBALS['G_MsgType']['COMMENT_ATLIST']:
					$tips = $tips."评论合拍时提到你";
					break;
				case  $GLOBALS['G_MsgType']['FOWARD_VWEIBO']:
					$tips = $tips."转发了你的合拍";
					break;
				case  $GLOBALS['G_MsgType']['ENSEMBLE_VWEIBO']:
					$tips = $tips."加入了你的合拍";
					break;
			
				default:
					$tips = "您有一条更新";
					break;
			}
		}else{
			$sql = "select * from users where Uid = ".$message['suid'];
			$result = $this->grassuDB->getAll($sql);

			if($result){
				$tips = $result[0]['Unick'];
			}
			
			$sql = "select * from users where Uid = ".$message['tuid'];
			$result = $this->grassuDB->getAll($sql);
			$uname2 = '';
			if($result){
				$uname2  = $result[0]['Unick'];
			}			
			
			switch ($otype){
				case  $GLOBALS['G_MsgType']['USER_FELLOW']:
					$tips = $tips."关注了".$uname2;
					break;
				case  $GLOBALS['G_MsgType']['VWEIBO_PUBLISH']:
					$tips = $tips."发布合拍";
					break;
			
				case  $GLOBALS['G_MsgType']['PRAISE_VWEIBO']:
					$tips = $tips."喜欢了".$uname2."的合拍";
					break;
				case  $GLOBALS['G_MsgType']['COMMENT_VWEIBO']:
					$tips = $tips."评论了".$uname2."的合拍";
					break;
				
				case  $GLOBALS['G_MsgType']['FOWARD_VWEIBO']:
					$tips = $tips."转发了".$uname2."的合拍";
					break;
				case  $GLOBALS['G_MsgType']['ENSEMBLE_VWEIBO']:
					$tips = $tips."加入了".$uname2."的合拍";
					break;
				case  $GLOBALS['G_MsgType']['TOPIC_ADD']:
					$tips = "新的话题已经发布";
					break;
				case  $GLOBALS['G_MsgType']['TOPIC_RSS']:
					$tips = $tips."参加了新话题";
					break;
				default:
					$tips =  $tips."有一条更新";
					break;
			}			
		}
		return $tips;
	}
	
	public function GetUnreadMsgCount($uid){
		if(empty($uid)){
			return false;
		}
		$sql = "select count(*) as count from inboxs where U_Uid = ".$uid." and Status =1 and MsgType = 1";
		$result =  $this->grassuDB->getAll($sql);
		if($result){
			return $result[0]['count'];
		}else{
			return false;
		}
	}
	
	public function  pushMessage_ios($message){
		$push_type = 1; //推送单播消息
		$optional[Channel::USER_ID] = $message['userid']; //如果推送单播消息，需要指定user		
		//指定发到ios设备
		$optional[Channel::DEVICE_TYPE] = 4;
		//指定消息类型为通知
		$optional[Channel::MESSAGE_TYPE] = 1;
		//如果ios应用当前部署状态为开发状态，指定DEPLOY_STATUS为1，默认是生产状态，值为2.
		//旧版本曾采用不同的域名区分部署状态，仍然支持。
		$optional[Channel::DEPLOY_STATUS] = 2;
		//通知类型的内容必须按指定内容发送，示例如下：
		$msg = '{
			"aps":{
				"alert":"'.$message['tips'].'",
				"sound":"",
				"badge":'.$message['count'].'
			},
			"server":{
				"type":"'.$message['msgtype'].'"				
			}	
	 	}';
		/**
		 * 
		 * ,
			"server":{
				"type":"'.$message['msgtype'].'"				
			}		
		 */
		$msg_key = "msg_key";
		//var_dump($msg);
		file_put_contents("/tmp/debug.txt", "\n".date("Y-m-d H:i:s")."\n".var_export($msg,true),FILE_APPEND);
		//var_dump($message);
		$ret = $this->channel->pushMessage ( $push_type, $msg, $msg_key, $optional ) ;
		//var_dump($ret);
		//var_dump($this->channel->errno ( ));
		//var_dump($this->channel->errmsg ( ));
		//var_dump($this->channel->getRequestId ( ));
		if($ret===false){
			return false;
		}else{			
			return true;
		}
	}
	public function  pushMessage_android($message){
		//推送消息到某个user，设置push_type = 1;
		//推送消息到一个tag中的全部user，设置push_type = 2;
		//推送消息到该app中的全部user，设置push_type = 3;
		$push_type = 1; //推送单播消息
		$optional[Channel::USER_ID] = $message['userid']; //如果推送单播消息，需要指定user	
		//optional[Channel::TAG_NAME] = "xxxx";  //如果推送tag消息，需要指定tag_name
		
		//指定发到android设备
		$optional[Channel::DEVICE_TYPE] = 3;
		//指定消息类型为通知
		$optional[Channel::MESSAGE_TYPE] = 1;
		//通知类型的内容必须按指定内容发送，示例如下：
		$msg = '{
			"title": "'.$message['tips'].'",
			"description": "'.$message['tips'].'",
			"notification_basic_style":7,
			"open_type":1,
			"url":""
 		}';
		
		$msg_key = "msg_key";
		$ret = $channel->pushMessage ( $push_type, $msg, $msg_key, $optional ) ;
		if ( false === $ret )
		{
			/*error_output ( 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!' ) ;
			error_output ( 'ERROR NUMBER: ' . $channel->errno ( ) ) ;
			error_output ( 'ERROR MESSAGE: ' . $channel->errmsg ( ) ) ;
			error_output ( 'REQUEST ID: ' . $channel->getRequestId ( ) );*/
			return false;
		}
		else
		{
			/*right_output ( 'SUCC, ' . __FUNCTION__ . ' OK!!!!!' ) ;
			right_output ( 'result: ' . print_r ( $ret, true ) ) ;*/
			return true;
		}	
	}
	
	public function UpdateIndexs($type,$param){	
		switch ($type){
			case $GLOBALS['G_MsgType']['USER_FELLOW']://用户关注或者取消关注
				{
					$uid = $param['uid'];
					$tuid = $param['tuid'];
					
					//获取tuid的所有合拍；
					$sql = "select *  from vweibos where V_Uid = ".$tuid;
					$result =  $this->grassuDB->getAll($sql);
					$weibos1 = array();
					foreach ($result as $key => $value){
						array_push($weibos1, $value['VWeiboID']);
					}
						
					//获取uid的indexs数据；
					$sql = "select *  from indexs where M_Uid = ".$uid;
					$result =  $this->grassuDB->getAll($sql);
					if (!is_array($result)){
						break;
					}
					if(count($result) == 0){					
						$weibos = $weibos1;
						rsort($weibos,SORT_NUMERIC);
						$weibos = implode(",", $weibos);
						$sql = "insert into indexs values (".$uid.",'".$weibos."')";
						//var_dump($sql);
						$result = $this->grassuDB->query($sql);	
					}elseif(count($result) == 1){
						$weibos2 = explode(",", $result[0]['Lists']);					
						$weibos = array_merge($weibos1, $weibos2);
						//$weibos = array_diff($weibos1, $weibos2);						
						rsort($weibos,SORT_NUMERIC);
						$weibos = implode(",", $weibos);
						$sql = "update indexs set Lists = '".$weibos."' where M_Uid=".$uid;
						//var_dump($sql);
						$result = $this->grassuDB->query($sql);
					}else{
						break;
					}					
				}
				break;
				case $GLOBALS['G_MsgType']['CANCEL_FELLOW']://用户关注或者取消关注
					{
						$uid = $param['uid'];
						$tuid = $param['tuid'];
							
						//获取tuid的所有合拍；
						$sql = "select *  from vweibos where V_Uid = ".$tuid;
						$result =  $this->grassuDB->getAll($sql);
						$weibos1 = array();
						foreach ($result as $key => $value){
							array_push($weibos1, $value['VWeiboID']);
						}
						
						//获取uid的indexs数据；
						$sql = "select *  from indexs where M_Uid = ".$uid;
						$result =  $this->grassuDB->getAll($sql);
						if (!is_array($result)){
							break;
						}
						if(count($result) == 0){
							/*$weibos = $weibos1;
							rsort($weibos,SORT_NUMERIC);
							$weibos = implode(",", $weibos);
							$sql = "insert into indexs values (".$uid.",'".$weibos."')";
							//var_dump($sql);
							$result = $this->grassuDB->query($sql);*/
						}elseif(count($result) == 1){
							$weibos2 = explode(",", $result[0]['Lists']);
							//$weibos = array_merge($weibos1, $weibos2);
							$weibos = array_diff($weibos2, $weibos1);
							rsort($weibos,SORT_NUMERIC);
							$weibos = implode(",", $weibos);
							$sql = "update indexs set Lists = '".$weibos."' where M_Uid=".$uid;
							//var_dump($sql);
							$result = $this->grassuDB->query($sql);
						}else{
							break;
						}
					}
					break;
			case  $GLOBALS['G_MsgType']['VWEIBO_PUBLISH']:
				{
					//file_put_contents("/tmp/debug.txt", var_export($param,true),FILE_APPEND);
					$uid = $param['uid'];
					$vweiboid = $param['vweiboid'];
					$fellows = $this->GetFellows($uid);
					if (($fellows) == false){
						return false;
					}
					array_push($fellows, array('F_Uid'=>$uid));					
					//file_put_contents("/tmp/debug.txt", var_export($fellows,true),FILE_APPEND);
					foreach ($fellows as $key => $value){
						$fuid = $value['F_Uid'];
						$sql = "select *  from indexs where M_Uid = ".$fuid;
						$result =  $this->grassuDB->getAll($sql);
						//file_put_contents("/tmp/debug.txt", var_export($sql,true),FILE_APPEND);
						//file_put_contents("/tmp/debug.txt", var_export($result,true),FILE_APPEND);
						if (!is_array($result)){
							continue;
						}
						if(count($result) == 0){	
							$weibos = array();
							array_push($weibos, $vweiboid);							
							$weibos = implode(",", $weibos);
							$sql = "insert into indexs values (".$fuid.",'".$weibos."')";
							//var_dump($sql);
							$result = $this->grassuDB->query($sql);
							
						}elseif(count($result) == 1){
							$weibos = explode(",", $result[0]['Lists']);
							array_push($weibos, $vweiboid);
							rsort($weibos,SORT_NUMERIC);
							$weibos = implode(",", $weibos);
							$sql = "update indexs set Lists = '".$weibos."' where M_Uid=".$fuid;
							//var_dump($sql);
							$result = $this->grassuDB->query($sql);
						}else{
							continue;
						}						
					}					
				}
				break;
				case  $GLOBALS['G_MsgType']['VWEIBO_DELETE']:
					{
						file_put_contents("/tmp/debug.txt", var_export($param,true),FILE_APPEND);
						$uid = $param['uid'];
						$vweiboid = $param['vweiboid'];
						$fellows = $this->GetFellows($uid);
						if (($fellows) == false){
							return false;
						}
						array_push($fellows, array('F_Uid'=>$uid));
						file_put_contents("/tmp/debug.txt", var_export($fellows,true),FILE_APPEND);
						foreach ($fellows as $key => $value){
							$fuid = $value['F_Uid'];
							$sql = "select *  from indexs where M_Uid = ".$fuid;
							$result =  $this->grassuDB->getAll($sql);
							file_put_contents("/tmp/debug.txt", var_export($sql,true),FILE_APPEND);
							file_put_contents("/tmp/debug.txt", var_export($result,true),FILE_APPEND);
							if (!is_array($result)){
								continue;
							}
							if(count($result) == 0){
								/*$weibos = array();
								array_push($weibos, $vweiboid);
								$weibos = implode(",", $weibos);
								$sql = "insert into indexs values (".$fuid.",'".$weibos."')";
								//var_dump($sql);
								$result = $this->grassuDB->query($sql);*/
									
							}elseif(count($result) == 1){
								$weibos = explode(",", $result[0]['Lists']);
								if (in_array($vweiboid, $weibos)){
									
									foreach ($weibos as $k=>$v){
										if($v == $vweiboid){
											unset($weibos[$k]);
											break;
										}
									}									
									$weibos = implode(",", $weibos);
									$sql = "update indexs set Lists = '".$weibos."' where M_Uid=".$fuid;
									//var_dump($sql);
									$result = $this->grassuDB->query($sql);
								}else{
									
								}
								
							}else{
								continue;
							}
						}
					}
					break;
			default:break;
		}	
			
	}
}
