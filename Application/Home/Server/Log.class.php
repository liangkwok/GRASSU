<?php
//日志操作类
class Log {
	public  $logpath;
	public  $logname;
   /*
	 * 系统日志记录
	 */
	public function __construct($logpath, $name='std'){
		$this->logpath=$logpath;
        $this->logname=$name;
	}
	public  function sysLog($destination = '',$ext="err") {
		$info['time']=date("Y-m-d H:i:s");
		$info['des'] = $destination;
		self :: doLog(self :: doimplode(":", $info), $ext);
	}
	public  function pushlog($destination = '',$ext="push") {
		$info['time']=date("Y-m-d H:i:s");
		$info['des'] = $destination;
		self :: doLog(self :: doimplode(":", $info),$ext);
	}
	public function doimplode($type=":",$array=array()){
        if(is_string($array)){
            return $array;
        }
        if(!is_array($array)){
            return '';
        }
        $str=null;
        foreach($array as $key=>$val){
			$str.=$key.$type.$val."|";
		}
		return $str;
	}
	//文件目录操作
	private function dirOpt() {
		self :: mkDirs($this->logpath . "/" . date("Y/m/d"));
	}
	// 循环创建目录
	private function mkDirs($dir, $mode = 0755) {
		if (is_dir($dir) || @ mkdir($dir, $mode))
			return true;
		if (!$this->mk_dir(dirname($dir), $mode))
			return false;
		return @ mkdir($dir, $mode);
	}
	//日志记录 写入文件
	private function doLog($content, $ext = "sys") {
		self :: dirOpt(); //目录检查
		$filename =$this->logpath . "/" . date("Y/m/d") . "/" . $this->logname . "." . $ext . "." . "log"; //一天记录一个文件
		$handle = @ fopen($filename, 'a');
		// 将$content写入到我们打开的文件中。
		$res = @ fwrite($handle, "\n" . $content);
		fclose($handle);
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	// 循环创建目录
    private function mk_dir($dir, $mode = 0777) {
		if (is_dir($dir) || @ mkdir($dir, $mode))
			return true;
		if (!$this->mk_dir(dirname($dir), $mode))
			return false;
		return @ mkdir($dir, $mode);
	}
}
?>