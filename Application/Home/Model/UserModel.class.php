<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Home\Model;
use Think\Model;
/**
 * ThinkPHP视图模型扩展 
 */
class UserModel extends Model {  
	public	function __construct(){
		
	}
	public  function CheckLogin(){
		$uid = $_POST['uid'];
		$token = $_POST['token'];
		//$uid = '22';
		//$token ='ee3f150c1cf8dddedb0faad6f6835a4b';
		$User = M("users");
		$record = $User->where("Uid=%d",$uid)->select();
		//var_dump($record);
		if(is_array($record)){
    		if($record[0]['UToken'] == $token){
    			return true;
    		}else{
    			return false;
    		}
    	}else{
    		return false;
    	}
		
	}
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function User($param) {
    	$User = M("users");
    	$record = array();
    	if($param['uid']){
    		$record = $User->where("Uid=%d",$param['uid'])->select();
    	}elseif($param['nick']){
    		$record = $User->where("Unick='%s'",$param['nick'])->select();
    	}elseif($param['mobile']){
    		$record = $User->where("UMobile='%s'",$param['mobile'])->select();
    	}else{
    		return false;
    	}    	
    	if(is_array($record)){
    		return $record[0];
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $User->getDbError();
    		return false;
    	}
    }    
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function ThirdUser($param) {
    	$User = M("users");
    	$record = array();
    	$third = $param['platform']."_".$param['serial'];
    	$record = $User->where("UThird = '%s'",$third)->select();    	
    	if(is_array($record)){
    		return $record[0];
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $User->getDbError();
    		return false;
    	}
    }
    /**
     * 根据参数获取用户信息
     * @access public
     * @return void
     */
    public function Users($param) {
    	$User = M("users");
    	$record = array();
    	if(!empty($param['uids'])){
    		$record = $User->where("Uid in (%s)",$param['uids'])->select();
    	}elseif($param['nicks']){
    		$record = $User->where("Unick in (%s)",$param['nicks'])->select();
    	}elseif($param['mobiles']){
    		$record = $User->where("UMobile in (%s)",$param['mobiles'])->select();
    	}else{
    		return false;
    	}
    	if(is_array($record)){
    		return $record;
    	}elseif($record === null){
    		return null;
    	}else{
    		$error = $User->getDbError();
    		return false;
    	}
    }
    /**
     * 用户注册
     * @access protected
     * @return void
     */
    public function Register($param) {
    	$User = M("users");
    	$userInfo = array();
    	$userInfo['UPwd'] = $param['pwd'];
    	$userInfo['UMobile'] = $param['mobile'];
    	$userInfo['URegDate'] = date("Y-m-d H:i:s");    	
    	$userInfo['ULLoginDate'] = date("Y-m-d H:i:s");
    	$result = $User->add($userInfo);    	
    	if($result){    		
    		return $result;
    	}else{
    		$error = $User->getDbError();
    		return false;
    	}
    }
    
    /**
     * 用户注册
     * @access protected
     * @return void
     */
    public function ThirdRegister($param) {
    	$User = M("users");
    	$userInfo = array();
    	$userInfo['UThird'] = $param['platform']."_".$param['serial'];    	
    	$userInfo['URegDate'] = date("Y-m-d H:i:s");
    	$userInfo['ULLoginDate'] = date("Y-m-d H:i:s");
    	$result = $User->add($userInfo);
    	//var_dump($result);
    	if($result){
    		return $result;
    	}else{
    		$error = $User->getDbError();
    		return false;
    	}
    }
    /**
     * 用户登录
     * @access protected
     * @return void
     */
    public function Login($uid,$pwd,$signature) {
    	$User = M("users");
    	$userInfo = array();
    	$userInfo['Uid'] = $uid;
    	$userInfo['UToken'] = $signature;    
    	$result = $User->where("Uid='%s' and UPwd='%s'",$uid,$pwd )->save($userInfo);
    	if(is_int($result)){
    		return true;
    	}else{
    		$error = $User->getDbError();
    		return false;
    	}
    }
    
    /**
     * 用户登出
     * @access public
     * @return void
     */
    public function Logout($uid,$token,$signature) {
    	$User = M("users");
    	$userInfo = array();
    	$userInfo['Uid'] = $uid;
    	$userInfo['UToken'] = $signature;    	
    	$result = $User->where("Uid=%d and UToken='%s'",$uid,$token )->save($userInfo);
    	if($result){
    		return true;
    	}else{
    		$error = $User->getDbError();
    		return false;
    	}
    }

    /**
     * 用户更新
     * @access public
     * @return void
     */
    public function Update($param) {
    	$User = M("users");
    	$userInfo = array();
    	$userInfo['Uid'] = $param['uid'];
    	$userInfo['UToken'] = $param['token'];
    	$userInfo = array();
    	if(!empty($param['nick'])){
    		$userInfo['Unick'] = $param['nick'];
    	}
    	if(!empty($param['avatar'])){
    		$userInfo['UAvatar'] = $param['avatar'];
    	}
    	if(!empty($param['gender'])){
    		$userInfo['UGender'] = $param['gender'];
    	}
    	if(!empty($param['cityid'])){
    		$userInfo['U_CityID'] = $param['cityid'];
    	}
    	if(!empty($param['mobile'])){
    		$userInfo['UMobile'] = $param['mobile'];
    	}
    	if(!empty($param['email'])){
    		$userInfo['UEmail'] = $param['email'];
    	}
    	if(!empty($param['remarks'])){
    		$userInfo['URemarks'] = $param['remarks'];
    	}
    	file_put_contents('/tmp/debug.txt', var_export($param,true)."\n", FILE_APPEND);
    	$result = $User->where("Uid=%d and UToken='%s'",$param['uid'],$param['token'])->save($userInfo);
    	file_put_contents('/tmp/debug.txt', var_export($result,true)."\n", FILE_APPEND);
    	if(is_int($result)){
    		return true;
    	}else{
    		$error = $User->getDbError();
    		file_put_contents('/tmp/debug.txt', var_export($error,true)."\n", FILE_APPEND);
    		return false;
    	}
    }
    
    public function UpPassword($param){    	
    	if (empty($param['mobile']) || empty($param['newpwd'])){
    		return false;
    	}
    	$User = M("users");
    	$userInfo = array();
    	$userInfo['UPwd'] = $param['newpwd'];
    	$result = $User->where("UMobile='%s'",$param['mobile'])->save($userInfo);
    	if(is_int($result)){
    		return true;
    	}else{
    		$error = $User->getDbError();    		
    		return false;
    	}    	
    }    
    
    public  function ChkLogin($param){
    	if (empty($param['uid']) || empty($param['token'])){
    		return false;
    	}
    	$User = M("users");    	
    	$result = $User->where("Uid=%d and UToken='%s'",$param['uid'],$param['uid'])->count();
    	if($result == '1'){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    public function Count($param) {
    	$User = M("users");    	
    	$count = "0";
    	if(!empty($param['words'])){
    		$map = array();
    		$map['Unick'] = array("like","%".$param['words']."%");
    		$count = $User ->where($map)->count();
    		//var_dump($count);
    	}else{
    		$count = $User -> count();
    	}
    	
    	if(is_string($count)){
    		return (int)$count;
    	}else{
    		$error = $User->getDbError();
    		return false;
    	}
    }
    
    public function Lists($param){
    	$User = M("users");   
    	if(!empty($param['words'])){
    		$map = array();
    		$map['Unick'] = array("like","%".$param['words']."%");
    		$result = $User->where($map)->limit($param['begin'],$param['pagenum'])->select();
    	}else{
    		$result = $User->limit($param['begin'],$param['pagenum'])->select();
    	}
    	if(is_array($result)){
    		return $result;
    	}elseif($result === null){
    		return null;
    	}else{
    		$error = $User->getDbError();
    		return false;
    	}
    }
    
    public  function Delete($param){
    	if (empty($param['uid']) ){
    		return false;
    	}
    	$User = M("users");
    	$result = $User->where("Uid=%d",$param['uid'])->delete();
    	if($result){
    		return true;
    	}else{
    		return false;
    	}
    }
}
