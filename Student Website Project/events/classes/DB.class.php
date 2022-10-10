<?php
	class DB {
		var $_conn = NULL;
		var $_rs = NULL;
		var $_host = '';
		var $_user = '';
		var $_pwd  = '';
		var $_db   = '';
		
		function DB(){
			$Database = new Database;
			$this->_host = $Database->default['host'];
			$this->_user = $Database->default['login'];
			$this->_pwd = $Database->default['password'];
			$this->_db = $Database->default['database'];
		}
		
		function IsConnected(){
			if (!is_null($this->_conn)) return true;
			else return false;
		}
		
		function OpenConnection(){
			//echo $this->_host . $this->_user . $this->_pwd;exit;
			$this->_conn = mysqli_connect($this->_host,$this->_user,$this->_pwd); 
			//mysql_unbuffered_query('SET NAMES utf8');
			if (!$this->_conn){ 
				$this->_conn = NULL;
				echo mysqli_error(); exit; 
			}
			else{
				if (!mysqli_select_db($this->_conn, $this->_db)){ 
					$this->_conn = NULL;
					echo mysqli_error(); exit; 
				}
			}
		}
		
		function CloseConnection(){
			if ($this->IsConnected()){
				mysqli_close($this->_conn);
				$this->_conn = NULL;
			}
		}
		
		function AutoIncrementID(){
			return mysqli_insert_id($this->_conn);
		}
		
		function Save($table,$data,$key='id'){
			if (empty($table) || count($data) < 1) {
				$this->CloseConnection();
				trigger_error("DB:Save() Illegal SQL:\n", E_USER_ERROR);
				exit;
			}
			$sql = "";
			$columns = array_keys((array)$data);
			if (!$this->IsConnected()) $this->OpenConnection();
			$result = mysqli_query("DESC ".$table,$this->_conn); 
			$error_no = mysqli_errno($this->_conn);
			if($error_no){
				$err_string = mysqli_error($this->_conn);
				$this->CloseConnection();
				trigger_error("DB:Save() failed, err no ".$error_no.
							"\nMessage: ".$err_string."\n", E_USER_ERROR);
				exit;
			}else{
				while($row = mysqli_fetch_object($result)){
					if($row->Field != $key && in_array($row->Field,$columns)){
						if($sql != "") $sql .= ", ";
						$sql .= $row->Field . " = '" . addslashes($data->{$row->Field}) . "'";
					}
				}
				if(isset($data->{$key}) && $data->{$key} != ""){
					$sql = "UPDATE ".$table." SET ".$sql." WHERE ".$key." = '".$data->{$key}."' ";
					if($this->Update($sql)){
						return $data->{$key};
					}
					else{
						return false;
					}
				}
				else{
					$sql = "INSERT INTO ".$table." SET ".$sql;
					return $this->Insert($sql);
				}
			}
		}
		
		function Insert($sql){
			if (empty($sql) || !preg_match("/^INSERT/",$sql."")) {
				$this->CloseConnection();
				trigger_error("DB:Insert() Illegal SQL:\n".$sql, E_USER_ERROR);
				exit;
			}
			if (!$this->IsConnected()) $this->OpenConnection();
			$result = mysqli_query($this->_conn, $sql);
			$error_no = mysqli_errno($this->_conn);
			if($error_no){
				$err_string = mysqli_error($this->_conn);
				$this->CloseConnection();
				trigger_error("DB:Insert() failed, err no ".$error_no.
							"\nMessage: ".$err_string.
							"\nSQL: ".$sql."\n", E_USER_ERROR);
				exit;
			}else{
				return $this->AutoIncrementID();
			}
		}
		
		function Update($sql){
			if (empty($sql) || !preg_match("/^UPDATE/",$sql."\n")) {
				$this->CloseConnection();
				trigger_error("DB:Update() Illegal SQL:\n".$sql, E_USER_ERROR);
				exit;
			}
			if (!$this->IsConnected()) $this->OpenConnection();
			$results = mysqli_query($this->_conn, $sql) or die("Invalid query:<br/>".$sql."<br/>".mysqli_error());
			if (!$results) return false;
			return true;
		}
		
		function Delete($sql) {
			if (empty($sql) || !preg_match("/^DELETE/",$sql."\n")) {
				$this->CloseConnection();
				trigger_error("DB:Delete() Illegal SQL:\n".$sql, E_USER_ERROR);
				exit;
			}
			if (!$this->IsConnected()) $this->OpenConnection();
			$results = mysqli_query($this->_conn, $sql);
			if (!$results) return false;
			return true;
		}


		function Select($sql){
			if (empty($sql) || !preg_match("/^SELECT/",$sql."\n")) {
				$this->CloseConnection();
				trigger_error("DB:Select() Illegal SQL:\n".$sql, E_USER_ERROR);
				exit;
			}
			
			if (!$this->IsConnected()) $this->OpenConnection();
			$result = mysqli_query($this->_conn, $sql); 
			$error_no = mysqli_errno($this->_conn);
			if($error_no){
				$err_string = mysqli_error($this->_conn);
				$this->CloseConnection();
				trigger_error("DB:Select() failed, err no ".$error_no.
							"\nMessage: ".$err_string.
							"\nSQL: ".$sql."\n", E_USER_ERROR);
				exit;
			}
			$returnObject = array();
			while($row = mysqli_fetch_object($result)){
				$returnObject[] = $row;
			}
			return $returnObject;
		}
		
		function Execute($sql){
			if (empty($sql)) {
				$this->CloseConnection();
				trigger_error("DB:Execute() Illegal SQL:\n".$sql, E_USER_ERROR);
				exit;
			}
			if (!$this->IsConnected()) $this->OpenConnection();
			$result = mysqli_query($this->_conn, $sql); 
			$error_no = mysqli_errno($this->_conn);
			if($error_no){
				$err_string = mysqli_error($this->_conn);
				$this->CloseConnection();
				trigger_error("DB:Execute() failed, err no ".$error_no.
							"\nMessage: ".$err_string.
							"\nSQL: ".$sql."\n", E_USER_ERROR);
				exit;
			}
			if($result == true){
				return true;
			}
			else{
				$returnObject = array();
				while($row = mysqli_fetch_object($result)){
					$returnObject[] = $row;
				}
				return $returnObject;
			}
		}
		
		function SafeString($string){
			if (!$this->IsConnected()) $this->OpenConnection();
			return mysqli_real_escape_string($string,$this->_conn);
		}
		
		function dateDifference($endDate, $startDate = ''){
			$dateDifference = '';
			
			if($startDate == '') $startDate = date("Y-m-d H:i:s");
			$startDate = strtotime($startDate);
			$endDate = strtotime($endDate);
			if($endDate > $startDate){
				$difference = $endDate - $startDate;
				if( $days = intval((floor($difference / 86400))) )
					$difference = $difference % 86400;
				if( $hours = intval((floor($difference / 3600))) )
					$difference = $difference % 3600;
				if( $minutes = intval((floor($difference / 60))) )
					$difference = $difference % 60;
				$difference = intval( $difference );
				
				$dateDifference = $days.'d '.$hours.'h '.$minutes.'m '.$difference.'s';
			}
			
			return $dateDifference;
		}
		
		function SelectAssoc($sql){
			if (empty($sql) || !preg_match("/^SELECT/",$sql."\n")) {
				$this->CloseConnection();
				trigger_error("DB:Select() Illegal SQL:\n".$sql, E_USER_ERROR);
				exit;
			}
			
			if (!$this->IsConnected()) $this->OpenConnection();
			
			$result = mysqli_query($this->_conn, $sql); 
			$error_no = mysqli_errno($this->_conn);
			if($error_no){
				$err_string = mysqli_error($this->_conn);
				$this->CloseConnection();
				trigger_error("DB:Select() failed, err no ".$error_no.
				"\nMessage: ".$err_string.
				"\nSQL: ".$sql."\n", E_USER_ERROR);
				exit;
			}
	
			$returnObject = array();
			while($row = mysqli_fetch_assoc($result)){
				$returnObject[] = $row;
			}
			return $returnObject;
		}
		
		function InsertData($data,$tablename){
			$arrayFind = array("pexels_video_slides");
			$qr="";
			foreach($data as $key =>$val){
				$qr .= $key ."=". "'".addslashes(str_replace($arrayFind,"",$val))."'".",";
			}
			
			$qr =substr($qr,0,-1);
			$sql = "INSERT INTO ".$tablename." SET ".$qr;
			
			return mysqli_query($sql)or die(mysql_error());	
		}
		
	}
