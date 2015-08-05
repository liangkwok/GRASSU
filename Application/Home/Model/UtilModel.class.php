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
class UtilModel extends Model {  
	public	function __construct(){
		
	}
	
    /**
     * 运营同城话题
     * @access protected
     * @return void
     */
    public	function IsDirty($contents) {
    	if (empty($contents)){
    		return  false;
    	}
    	$Dirty = M("dirty");
    	$record = $Dirty->select();
    	if($record){
    		$allergicWord = array();
    		foreach ($record as $key=>$value){
    			array_push($allergicWord,$value['Words']);
    		} 
    		$info = 0;
    		for ($i=0;$i<count($allergicWord);$i++){
    			$content = substr_count($contents, $allergicWord[$i]);
    			if($content>0){
    				$info = $content;
    				break;
    			}
    		}
    		
    		if($info>0){
    			//有违法字符
    			return TRUE;
    		}else{
    			//没有违法字符
    			return FALSE;
    		}
    	}else{
    		return false;
    	}
    }
    
    
}