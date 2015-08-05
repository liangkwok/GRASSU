<?php
/**
 * @desc mysql类型数据库的访问类 
 *
 * @author  
 * @version v1.0.0 
 * @package common
 */
class DbMysqli {  
    private $_dbConf;
    private $_autoCommitTime = 0;
    private $_conn;	 
    
    private static $_instance;
    
    public static function getInstance($db='gs'){
        if(!isset(self::$_instance[$db])){
            self::$_instance[$db] = new self($db);
        }
    
        return self::$_instance[$db];
    }
    
    /**
     * MySQLi构造函数
     *
     * @param array $dbInfo 数据库配置信息
     * @param string $dbPath db的配置路径
     * @param 返回的数据格式 $fetchMode
     */
    private function __construct($db) {
    	$this->_dbConf = $GLOBALS['g_db'][$db];
    }
    
    public function __clone(){
        exit('Clone is not allow!');
    }
	
	/**
	 * 连接数据库
	 * @return boolean
	 */
	public function connect() {
		$dbHost = $this->_dbConf ["host"];
		$dbName = $this->_dbConf ["name"];
		$dbUser = $this->_dbConf ["user"];
		$dbPass = $this->_dbConf ["pass"];
		$dbPort = (int)$this->_dbConf ["port"];

		$this->_conn = mysqli_connect ( $dbHost, $dbUser, $dbPass, $dbName, $dbPort );
		
		if (! $this->_conn) {
			throw new DB_Exception ( 'connect to db fail: '.$dbHost.':'.$dbPort.'  '.$dbName, mysqli_connect_errno());
			return false;
		}
		
		$charset = 'latin1';
		if (isset($this->_dbConf['charset']) && (!empty($this->_dbConf['charset']))){
			$charset = $this->_dbConf['charset'];	
		} 
		$sql = "SET NAMES $charset";
		$this->update ( $sql );
		return true;
	}
	
	/**
	 * 关闭数据库连接
	 *
	 * 一般不需要调用此方法
	 */
	public function close() {
		if (is_object ( $this->_conn )) {
			mysqli_close ( $this->_conn );			
		}
		$this->_conn = null;
	}
	
	/**
	 * @desc 	转义需要插入或者更新的字段值
	 * @param 	mixed $str 需要处理的变量
	 * @return 	mixed 返回转义后的结果
	 * @note 	在所有查询和更新的字段变量都需要调用此方法处理数据
	 */
	public function escape($str) {
		if (is_array($str)) {
			foreach ($str as $key => $value) {
				$str[$key] = $this->escape($value);
			}
		} else {
			return addslashes($str);
		}
		return $str;
	}
	
	/**
	 * @desc 	去除转移
	 * @param 	mixed $str 需要处理的变量
	 * @return 	mixed 返回移除转义后的结果
	 * @note
	 */
	public function unescape($str) {
		if (is_array($str)) {
			foreach ($str as $key => $value) {
				$str[$key] = $this->unescape($value);
			}
		} else {
			return stripcslashes($str);
		}
		return $str;
	}
	
    /**
     * 执行一个SQL查询
     *
     * 本函数仅限于执行SELECT类型的SQL语句
     *
     * @param string $sql SQL查询语句
     * @param mixed $limit 整型或者字符串类型，如10|10,10
     * @param boolean $quick 是否快速查询
     * @return resource 返回查询结果资源句柄
     */
    public function query($sql, $limit = null, $quick = false) {
        if ($limit != null) {
            if (!preg_match('/^\s*SHOW/i', $sql) && !preg_match('/FOR UPDATE\s*$/i', $sql) && !preg_match('/LOCK IN SHARE MODE\s*$/i', $sql)) {
                $sql = $sql . " LIMIT " . $limit;
            }
        }
        
        if (!$this->_conn || !$this->ping($this->_conn)) {
        	if($this->_autoCommitTime){        		
        		$errno = (!$this->_conn) ? mysqli_connect_errno() : mysqli_errno($this->_conn);
        		throw new DB_Exception('auto commit time is not zero when reconnect to db', $errno);
        	}
        	else{
        		$this->connect();
        	}
        }

        $qrs = mysqli_query($this->_conn, $sql, $quick ? MYSQLI_USE_RESULT : MYSQLI_STORE_RESULT);
        if (!$qrs) {
        	return false;
        } else {
            return $qrs;
        }
    }

    /**
     * 获取结果集
     *
     * @param resource $rs 查询结果资源句柄
     * @return array 返回数据集每一行，并将$rs指针下移
     */
    public function fetch($rs) {    	
 
        $fields = mysqli_fetch_fields($rs);
    	$values = mysqli_fetch_array($rs, MYSQLI_ASSOC);
    	if ($values) {
	        foreach ($fields as $field) {
	            switch ($field->type) {
	                case MYSQLI_TYPE_TINY:
	                case MYSQLI_TYPE_SHORT:
	                case MYSQLI_TYPE_INT24:
	                case MYSQLI_TYPE_LONG:
// 	                	 if($field->type == MYSQLI_TYPE_TINY && $field->length == 1) {	
// 							$values[$field->name] = (boolean) $values[$field->name];	//如果类型为tinyint，值为0，这里会被转化为空，就出现了bug；
// 	                	 } else {
	                    	$values[$field->name] = (int) $values[$field->name];
// 	                	 }
					break;
	                case MYSQLI_TYPE_DECIMAL:
	                case MYSQLI_TYPE_FLOAT:
	                case MYSQLI_TYPE_DOUBLE:
	                case MYSQLI_TYPE_LONGLONG:
	                    $values[$field->name] = (float) $values[$field->name];
	                break;
	            }
        	}
    	}
		if(empty($values)) $values = array();
    	return $values;
    }

    /**
     * 执行一个SQL更新
     *
     * 本方法仅限数据库UPDATE操作
     *
     * @param string $sql 数据库更新SQL语句
     * @return boolean
     */
    public function update($sql) {
        if (!$this->_conn || !$this->ping($this->_conn)) {
        	if($this->_autoCommitTime){
        		$errno = (!$this->_conn) ? mysqli_connect_errno() : mysqli_errno($this->_conn);
        		throw new DB_Exception('auto commit time is not zero when reconnect to db', $errno);
        	}
        	else{
        		$this->connect();
        	}
        }
        //logger(DEBUG, 0, "SQL[$sql]");
        $urs = mysqli_query($this->_conn, $sql);
        if (!$urs) {
            return false;
        } else {
            return $urs;
        }
    }

    /**
     * 返回SQL语句执行结果中的第一行数据
     *
     * @param string $sql 需要执行的SQL语句
     * @param const $fetchMode 返回的数据格式
     * @return array 结果集数组
     */
    public function getRow($sql) {
        if (!$rs = $this->query($sql, 1, true)) {
            return false;
        }
        $row = $this->fetch($rs);
        $this->free($rs);
        return $row;
    }

    /**
     * 返回SQL语句执行结果中的所有行数据
     *
     * @param string $sql 需要执行的SQL语句
     * @param string $indexkey 返回数组的key，默认自增处理
     * @return array 结果集二维数组
     */
    public function getAll($sql, $indexkey=null) {
        if (!$rs = $this->query($sql, null, true)) {
            return false;
        }
        $allRows = array();
        if($indexkey){
	        while(($row = $this->fetch($rs)) != null) {
	            $allRows[$row[$indexkey]] = $row;
	        }
        }else{
	        while(($row = $this->fetch($rs)) != null) {
	            $allRows[] = $row;
	        }
        }
        $this->free($rs);
        return $allRows;
    }

    /**
     * 设置是否开启事务(是否自动提交)
     *
     * 当设置为false的时候,即开启事务处理模式,表类型应该为INNODB
     *
     * @param boolean $mode
     * @return boolean
     */
    public function autoCommit($mode = false) {
   	 	if (!$this->_conn || !$this->ping($this->_conn)) {
        	if($this->_autoCommitTime){
        		$errno = (!$this->_conn) ? mysqli_connect_errno() : mysqli_errno($this->_conn);
        		throw new DB_Exception('auto commit cnt is not zero when reconnect to db', $errno);
        	}
        	else{
        		$this->connect();
        	}
        }
        
        if ($mode) {
            //如果为true，则说明要提交
            if($this->_autoCommitTime)
            {
            	throw new DB_Exception('auto commit cnt is not zero when set autocommit to true', mysqli_errno($this->_conn));
            	return false;
            }
        } else {
            //如果为false，则说明要一起commit，并且会积累 
            $this->_autoCommitTime++;
        }
        return mysqli_autocommit($this->_conn, $mode);
    }

    /**
     * 直接提交执行的SQL
     *
     * 当开启事务处理后,要手动提交执行的SQL语句
     *
     * @return boolean
     */
    private function commit($mode = true) {
        $result = mysqli_commit($this->_conn);
        //mysql的实现是手工提交后，并不会复原自动提交的功能
        mysqli_autocommit($this->_conn, $mode);
        return $result;
    }
    
    /**
     * 尝试提交执行的SQL【当有多个autoCommit时，仅提交最后一次！】
     *
     * 当开启事务处理后,要手动提交执行的SQL语句
     *
     * @return boolean
     */
    public function tryCommit($mode = true) {
        $this->_autoCommitTime--;
        //最后一次commit才会提交
        if ($this->_autoCommitTime <= 0) {
        	$this->_autoCommitTime = 0;
            return $this->commit($mode);
        } else {
        	return true;
        }
    }

    /**
     * 回滚
     *
     * 当开启事务处理后,有需要的时候进行回滚
     *
     * @return boolean
     */
    public function rollback() {
        return mysqli_rollback($this->_conn);
    }

    /**
     * 返回最近一次查询返回的结果集条数
     *
     * @return int
     */
    public function rows($rs) {
        return mysqli_num_rows($rs);
    }
    
    /**
     * 返回最近一次更新的结果条数
     * 
     * @return int
     */
    public function affectedRows() {
        return mysqli_affected_rows($this->_conn);
    }

    /**
     * 返回最近一次插入语句的自增长字段的值
     *
     * @return int
     */
    public function lastID() {
        return mysqli_insert_id($this->_conn);
    }

    /**
     * 释放当前查询结果资源句柄
     *
     */
    public function free($rs) {
        if ($rs) {
            return mysqli_free_result($rs);
        }
        return true;
    }
    
    public function ping($conn) {
        return mysqli_ping($conn);
    }

    /**
     * 析构函数，暂时不需要做什么处理
     *
     */
    public function __destruct() {
    }
    
    
    /**
     * 返回最近一次查询的错误码
     * 
     * @return int
     */
    public function getErrorNum() {
        return mysqli_errno($this->_conn);
    }
    
	public function getErrorInfo() {
        return mysqli_error($this->_conn);
    } 

    /**
     * 遍历每个参数，为每个值根据需要增加'\'，返回key和value的数组
     * @param string $array
     * @return multitype:string
     */
	static public function instr($array)
	{
		$keys='';		
		$vars='';
		foreach ($array as $key=>$var){
			$keys.='`'.$key.'`,';
			$vars.="'".addslashes($var)."',";
		}
		$keys=substr($keys,0,-1);
		$vars=substr($vars,0,-1);
		return array($keys,$vars);
	}
	
	static public function setstr($array)
	{
		$str='';
		foreach ($array as $key=>$var){
			$str.='`'.$key."`='".addslashes($var)."',";
		}
		$str=substr($str,0,-1);
		return $str;
	}
    
}

/*
 * @desc 	数据库操作异常类
 * @author
 * @date
 * @note
 */
class DB_Exception extends Exception {

}
