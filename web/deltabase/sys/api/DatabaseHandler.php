<?php
	require_once("utility.php");
	class DatabaseHandler {

		//Class constants
		const SERVER = "localhost";
		const DBNAME = "deltabase";
		const USERNAME = "root";
		const PASSWORD = "password";

		//Attributes
		private $db_; 
		private $result_;
		public $error;

		//Constructor
		function __construct(){
			$this->db_ = new mysqli(self::SERVER, self::USERNAME, self::PASSWORD, self::DBNAME); 
			if(!$this->db_)
			{ 
				throw new DeltaException(600);
		   	}
		}

		//Destructor
		function __destruct(){
			$this->db_->close();
		}

		//check
		private function checkResult($query)
		{ 
			if (!$this->result_) {
				$message = 'Invalid query: ' . $this->db_->error . "\n";
				$message .= 'Whole query: ' . $query;
				
				$this->error = $message;
				return false;
			}
			return true;
		}

		//showResult
		function showResult()
		{	
			if($this->error) {
				echo($this->error);
			}
			print_r($this->fetchResultObject());
		}

		//filter SQL string query 
		private function filterSQL(&$string) {
			if (get_magic_quotes_gpc()) 
				$string = stripslashes($string);
			// removes slashes (\)
			if (!is_numeric($string))
				$string = "'" . mysqli_real_escape_string($this->db_, $string) . "'";
		}
		private function filterSQLQuery($params) {
			if(!is_array($params)) {
				$this->filterSQL($params);
			}
			else {
				foreach($params as $string)
				{
					$this->filterSQL($string);
				}
			}
			return $params; 
		}
		
		//execute a query 
		public function executeQuery ($query, $params, $numRowsMustBePositive = false) {

			//Prepare
			$query = vsprintf($query, $this->filterSQLQuery($params));

			//Execute
			$this->result_ = $this->db_->query($query);

			//Check for errors
			if(!$this->checkResult($query)) {
				throw new DeltaException(601, $this->error);
			}
			else if (!$this->db_->affected_rows && $numRowsMustBePositive) {
				throw new DeltaException(603);
			}
		}

		//CALL 
		public function call($procedure, $formatted_string, $params, $scalar=true) {
			try {
				$this->executeQuery("CALL " . $procedure . "(" . $formatted_string .");", $params);
			}
			catch (DeltaException $e) {
				goToErrorPage($e->getCode());
			}
			finally {
				if(!$scalar) {
					$result = $this->fetchResultArrayOfObjects();
				}
				else {
					$result = $this->fetchResultObject();
				}
				$this->releaseResult();
				$this->nextResult();

				return $result;
			}
		}

		//Result fetching functions
		public function fetchResultObject() {
			return $this->result_->fetch_object();
		}
		public function fetchResultAssoc() {
			return $this->result_->fetch_assoc();
		}
		public function fetchResultArray() {
			return $this->result_->fetch_array();
		}
		public function fetchResultArrayOfObjects(){
			$array = array();
			while ($row = $this->result_->fetch_object()) {
		        $array[] = $row;
		    }
		    return $array;
		}

		public function fetchJSONResult(){
			$array = array();
			$array[]['status'] = 'ok';
			
			// Create an array
			$rows = array(); 

			// Fetch and populate array
			while($row = $this->fetchResultAssoc()) { 
				$rows[]=$row; 
			} 

			//die( json_encode(array('status'=>'ok', 'result_list'=>$rows)) );
			return json_encode(utf8ize(array('result_list'=>$rows), JSON_PRETTY_PRINT));
		}
		//release result
		public function releaseResult() {
			$this->result_->close();
		}

		public function nextResult() {
			$this->db_->next_result();
		}

		public function insertId() {
			return $this->db_->insert_id;
		}
	}
?>
