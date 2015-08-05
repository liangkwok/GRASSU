<?php
/**
 * @desc 消息处理类 
 *
 * @author  
 * @version v1.0.0 
 * @package common
 */

class PublishServer {
	private static $_instance = null;
    private $grassuDB = null;

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

    }
    
    public function __clone(){
        exit('Clone is not allow!');
    }
	
	/**
	 * 服务器开始运行
	 * @return boolean
	 */
	public function Run() {
	    $weibos = $this->GetWeibos();
		$this->Publish($weibos);
	}

    public function GetWeibos(){
        $contents = file_get_contents("/www/api/Application/Home/Server/weibos.txt");
        $contents = explode("\n",$contents);
        $weibos = array();
        foreach($contents as $key => $value){
            $record = explode(" ",$value);
            if(!isset($record[0])){
                continue;
            }
            $uid = 10000 + $record[0];
            if($uid <= 10000){
                continue;
            }
            if(!isset($weibos[$uid])){
                $weibos[$uid] = array();
            }
            array_push($weibos[$uid],$value);
        }
        return $weibos;
    }



    public function Publish($weibos){
        while(true){
            foreach($weibos as $uid => $uid_weibos){
                $weibo = explode(" ",$uid_weibos[0]);
                unset($weibos[$uid][0]);
                $weibos[$uid] = array_values($weibos[$uid]);
                //var_dump($uid_weibos[0]);
                //var_dump($weibo);
                file_put_contents("/www/api/Application/Home/Server/published.txt",$uid_weibos[0]."\n",FILE_APPEND);
/*
                $data['V_Uid'] = $param['uid'];
                $data['ORGVWpicID'] = $param['orgvwpicid'];
                $data['ORGVWVideoID'] = $param['orgvwvideoid'];
                $data['ORGVWVideoParam'] = $param['orgvwvideoparam'];
                $data['VWpicID'] = $param['vwpicid'];
                $data['VWVideoID'] = $param['vwvideoID'];
                $data['VWVideoParam'] = $param['vwvideoparam'];
                $data['PubTime'] = date("Y-m-d H:i:s");
                $data['Location'] = $param['location'];
                $data['CityID'] = $param['cityid'];
                $data['Latitude'] = $param['latitude'];
                $data['Longitude'] = $param['longitude'];
                $data['Channels'] = $param['channelid'];
                $data['Authority'] = $param['authority'];
                $data['ViewAuthority'] = $param['viewauthority'];
                $data['Content'] = $param['content'];
                $data['AtList'] = $param['atlist'];
                $data['TopicList'] = $param['topiclist'];
                $data['EnsembleList'] = $param['ensemblelist'];
*/
                $VWpicID = $weibo[3];
                $VWVideoID = $weibo[2];
                if(empty($VWVideoID) || empty($VWpicID)){
                    continue;
                }
                $PubTime = date("Y-m-d H:i:s");
                $LocArray = $this->GetLocaiton();
                $Location = $LocArray[0];
                $CityID = 0;
                $Latitude = $LocArray[1];
                $Longitude = $LocArray[2];

                $TopicList ="";
                if(!empty($weibo[4])){
                    if(!empty($weibo[5])){
                        if(!empty($weibo[6])){
                            $TopicList =$this->GetTopics($weibo[4],$weibo[5],$weibo[6]);
                        }else{
                            $TopicList =$this->GetTopics($weibo[4],$weibo[5]);
                        }

                    }else{
                        $TopicList =$this->GetTopics($weibo[4]);
                    }
                }

                //Publish
                $sql = "insert into vweibos(V_Uid,ORGVWpicID,VWpicID,ORGVWVideoID,VWVideoID,PubTime,Location,CityID,Latitude,Longitude,TopicList)
values (".$uid.",'".$VWpicID."','".$VWpicID."','".$VWVideoID."','".$VWVideoID."','".$PubTime."','".$Location. "','".$CityID."','".$Latitude."','".$Longitude."','".$TopicList."')";
                $record = $this->grassuDB->query($sql);
                //var_dump($sql);
                $rtime = rand(20,30);
                sleep($rtime*60);
            }
        }

    }
	/**
	 * 获取messages表中新增加的用户操作；
	 * @return array
	 */
	public function GetTopics($T1,$T2,$T3){
        $list = array();
        if(isset($T1)){
            $tmp = $this->GetTopicID($T1);
            if(!empty($T1)){
                array_push($list,$tmp);
            }
        }
        if(isset($T2)){
            $tmp = $this->GetTopicID($T2);
            if(!empty($T1)){
                array_push($list,$tmp);
            }

        }
        if(isset($T3)){
            $tmp = $this->GetTopicID($T3);
            if(!empty($T1)){
                array_push($list,$tmp);
            }

        }
        if(count($list) > 0){
            return implode(",",array_reverse($list));
        }else{
            return "";
        }
    }

    public function GetTopicID($topicname){
        $sql = "select * from topics where TopicName = '".$topicname."'";
        $record = $this->grassuDB->getAll($sql);
        //var_dump($operations);
        if(empty($record)){
            $sql = "insert into topics(TopicName) values('".$topicname."')";
            $record = $this->grassuDB->query($sql);
            $sql = "select * from topics where TopicName = '".$topicname."'";
            $record = $this->grassuDB->getAll($sql);
            if(empty($record)){
                return "";
            }else{
                return $record[0]['TopicID'];
            }
        }else{
            return $record[0]['TopicID'];
        }
    }

    public  function GetLocaiton(){
        $Loc = array(
            array("柏林",52.5075419,13.4251364),
            array("伦敦",51.5286416,	0.0064625),
            array("巴黎",48.8634045,	2.3492486),
            array("东京",35.6647315,	139.6965263),
            array("洛杉矶",34.0204989,-118.4117325),
            array("纽约",40.7033127,	-73.979681),
            array("香港",22.3576782,	114.1210175),
            array("新加坡",1.3147308,103.8470128),
            array("悉尼",-33.7969235,150.9224326),
            array("多伦多",43.7182412,-79.378058)
        );
        $index = rand(0,9);
        return $Loc[$index];
    }


}
