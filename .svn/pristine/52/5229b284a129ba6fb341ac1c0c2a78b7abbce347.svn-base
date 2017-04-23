<?php

//v 1.13.7.16
class DbConnection{
    
	private $database;
    private $hostname;
	private $username;
    private $password;
	private $port;
	//public $instance;
    private $is_transaction = false;
    private $write_sql = false;
	
    private $myConnection;
    public $sql = array();
	
	function isTransaction(){
		return $this->is_transaction;
	}
	
	function write_sql(){
		$this->write_sql = true;
	}
	
    function __construct($hostname = "") {
		$connection_string = require("connection_string.php");
		if($hostname == ""){
			$hostname = str_replace('www.', '', $_SERVER['HTTP_HOST']);
			if(!strpos($hostname, ':')){
				$hostname .= ":$_SERVER[SERVER_PORT]";
			}
			//$this->instance = str_replace(":$_SERVER[SERVER_PORT]", '', $hostname);
		}
		/*if(is_array($connection_string["[app]$hostname"])){
			$string = $connection_string["[app]$hostname"];
			$this->instance = "[app]$this->instance";
		}else{
			$string = $connection_string[$hostname];
		}*/
		if(isset($connection_string[$hostname]) && is_array($connection_string[$hostname])){
			$string = $connection_string[$hostname];
			$this->hostname = isset($string['hostname']) ? $string['hostname'] : 'localhost';
			$this->database = $string['database'];
			$this->username = $string['username'];
			$this->password = $string['password'];
			$this->port = isset($string['port']) ? $string['port'] : '5432';
			$timezone = isset($string['timezone']) ? $string['timezone'] : 'America/Mexico_City';
		}else{
			throw new Exception('No se encontraron datos para la conexión', 985);
		}
		
		try{
            $this->myConnection = new PDO("pgsql:host=$this->hostname;port=$this->port;dbname=$this->database;user=$this->username;password=$this->password");
			date_default_timezone_set($timezone);
			$this->Query("SET CLIENT_ENCODING TO 'UTF8'");
			$this->Query("SET TIME ZONE INTERVAL '" . date('P') . "'");
		}catch(Exception $e){
			throw new Exception(utf8_encode($e->getMessage()), 99);
		}
    }
    
    /*private function errorCheck(){
		//$err = new MessageHandler();
		$error_no = mysqli_errno($this->myConnection);
		if( $error_no != 0 ){
			throw new Exception(mysqli_error($this->myConnection),$error_no);
			if($this->is_transaction){
				$this->Rollback();
			}
		}
    }*/
    
    function Insert($sTableName, $aColumnsValues){
		$sColums = "";
		$sValues = "";
		if(is_array($aColumnsValues)){
			$sColums = implode( ", ", array_keys($aColumnsValues) );
			$aValues = array();
			$i = 0;
			foreach($aColumnsValues as &$value){
				//$aValues[] = is_numeric($value) ? "{{$i}}" : (is_null($value) ? "NULL" : "'{{$i}}'");
				
				//file_put_contents('log.txt',$value."\n",FILE_APPEND);
				if(is_bool($value)){
					//file_put_contents('log.txt','entro '.($value == true ? 'true' : 'false'),FILE_APPEND);
					$value = $value ? '1' : '0';	
				}
				file_put_contents('log.txt','dato '.$value."   ",FILE_APPEND);
				$aValues[] = is_numeric($value) ? "'{{$i}}'" : "'{{$i}}'";
				$i++;
			}
			$sValues = implode( ", ", $aValues);
		}
		$sql="INSERT INTO $sTableName ($sColums) VALUES ($sValues) RETURNING id";
		return $this->Query($sql, array_values($aColumnsValues));
    }
    
	function Replace($sTableName, $aColumnsValues){
		$sColums = "";
		$sValues = "";
		if(is_array($aColumnsValues)){
			$sColums = "`" . implode( "`, `", array_keys($aColumnsValues) ) . "`";
			$aValues = array();
			for( $i=0; $i<count($aColumnsValues); $i++){
				$aValues[] = "'{{$i}}'";
			}
			$sValues = implode( ", ", $aValues);
		}
		$sql="REPLACE $sTableName ($sColums) VALUES ($sValues)";
		return $this->Query($sql, array_values($aColumnsValues));
    }
	
    function Update($sTableName, $aColumnsValues, $sWhere = "", $aWhere = array()){
		$aColums = array();
		$iColumn = 0;
		foreach( $aColumnsValues as $nColumn => $sValue){
			$aColums[] = "$nColumn=".(in_array(strtolower($sValue), array("now()", "null")) ? "{{$iColumn}}" : "'{{$iColumn}}'")." ";
			$iColumn++;
		}
		$sql="UPDATE $sTableName SET ".implode( ",", $aColums )." ".( $sWhere != "" ? "WHERE $sWhere" : "");
		while( $iPos = strpos($sql,"{?}") ){
			$sql= preg_replace("/\{\?\}/", "{{$iColumn}}", $sql,1);
			$iColumn++;
		}
		return $this->Query($sql, array_merge( array_values($aColumnsValues), $aWhere ));
    }
    
    
    function Query($sql, $params=array()){
		if( substr( $sql, 0, 5) == "EXEC "){
			eval(substr($sql, 5));
		}
		if( count($params) > 0){
			foreach($params as $iParam => $value){
				$sql= str_replace("{{$iParam}}", $value, $sql);
			}
		}
		$sql = preg_replace( '/\{\d+\}/', '', $sql);
		if($this->write_sql){
			$log_path = "sqlLog";
			if(!file_exists($log_path)){
				@mkdir($log_path);
				chmod($log_path, 0777);
			}
			$log_path .= "/$this->instance.txt";
			@file_put_contents($log_path, date('Y-m-d H:i:s').": ".$sql.";\n", FILE_APPEND);
			chmod($log_path, 0777);
			$this->write_sql = false;
		}
		//$result = mysqli_query($this->myConnection, $sql);
		//try{
			$stmt = $this->myConnection->prepare($sql);
			$stmt->execute();
		/*}catch(Exception $e){
			throw new Exception($e->getMessage(), $e->getCode());
		}*/
		$error_info = $stmt->errorInfo();
		if($error_info[0] != '00000'){
			throw new Exception($error_info[2], $error_info[0]);
		}
		//var_dump($stmt->errorInfo());
		//$this->errorCheck();
		$results = array();
		//echo gettype($result);
		//echo "total: ".$stmt->rowCount();
		if($stmt->rowCount() > 0){
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			/*foreach($stmt->fetchAll() as $row){
				var_dump($row);
				$results[] = $row;
			}*/
		}
		
		/*if( gettype($result) == "object" ){
			if(@mysqli_num_rows($result) > 0){
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
					$results[] = $row;
				}
			}
			mysqli_free_result($result);
		}*/
		return $results;
    }
    
    function MultiInsert($sTableName, $aColums, $aValues){
		$sql = "INSERT INTO $sTableName ";
		if( is_array($aColums) ){
			$sql .= "(`" .implode( "`, `", array_values($aColums) )."`) ";
		}
		$sql .= " VALUES ";
		$aValuesQuery = array();
		$aRowValuesQuery = array();
		$iValue = 0;
		foreach( $aValues as $row){
			$aColumsValues = array();
			foreach( $aColums as $column ){
				$aColumsValues[] .= "'{{$iValue}}'";
				$iValue++;
			}
			$aRowValuesQuery[] = "(" .implode( ",", $aColumsValues ).")";
			$aValuesQuery = array_merge($aValuesQuery, $row);
		}
		$sql .= implode( ",", $aRowValuesQuery );
		return $this->Query($sql, $aValuesQuery);
    }
    
    function Delete($table, $sWhere, $aWhere){
		$sql = "DELETE FROM $table WHERE $sWhere";
		$this->Query($sql, $aWhere);
    }
    
    function StartTransaction(){
		//$this->Query("START TRANSACTION");
		$this->Query("BEGIN");
		$this->is_transaction = true;
    }
    
    function Commit(){
		if($this->is_transaction){
			$this->is_transaction = false;
			$this->Query("COMMIT");
		}
    }
    
    function Rollback(){
		if($this->is_transaction){ 
			$this->is_transaction = false;
			$this->Query("ROLLBACK");
		}
    }
    
    function Close(){
		mysqli_close($this->myConnection);
    }
    
    function InsertId($seq = null){
		return $this->myConnection->lastInsertId($seq);
    }
}
?>