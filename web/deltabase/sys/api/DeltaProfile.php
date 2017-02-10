<?php
require_once("DatabaseHandler.php");

class DeltaProfile {
		//PowerAttributes
		protected $dbHandler_;
		private $mode_;

		//basic information
		// $id_; $name_; $surname_; $telephone_; $bio_; $_avg_; $locationId_; $languages
		protected $id_;
		protected $profileInformation_;	

		//Magic methods get-set
		public function __get($property) {
			if (property_exists($this, $property)) {
				return $this->$property;
			}
		}
		public function __set($property, $value) {
			if (property_exists($this, $property)) {
				$this->$property = $value;
			}
			return $this;
		}
		
		function __construct($id_user, $m) {
			$this->id_ = $id_user;
			$table = array('u'=>'user', 'c'=>'company');
			$this->mode_ = $table[$m];

			try {
				$this->dbHandler_ = new DatabaseHandler; 
		   		$this->isValid($this->mode_);
		   	}
		   	catch (DeltaException $e) {
		   		goToErrorPage($e->getCode()); 
		   	}

		}

		function __destruct() {
		}
		
		//Private method //what must be 'user' OR 'company'
		private function isValid($what) {
			$params = array($this->id_);

			try { 
				$this->dbHandler_->executeQuery("SELECT * FROM " . $what . " WHERE id_". $what . "=%d", $params, true);
			}
			catch(DeltaException $e) {
				if($e->isCode(603)) //empty result, throw id not valid
					throw new DeltaException(100);
				else
					throw $e;
			}
			$this->dbHandler_->releaseResult();			
		}		

		//get some magic basic information
		public function loadProfileInformation() {
			$procedure = "get" . $this->mode_ . "Information"; 
			$this->profileInformation_ = $this->dbHandler_->call($procedure, "%d", array($this->id_));
		}
		public function loadAllInformation() {
			$this->loadProfileInformation();
		}
		public function getMessages(){
			$procedure = "get" . $this->mode_ . "Messages"; 
			return $this->dbHandler_->call($procedure, "%d", array($this->id_), false);			
		}
		public function getAdvices() {
			$procedure = "getAdvicesFor" . $this->mode_; 
			return $this->dbHandler_->call($procedure, "%d", array($this->id_), false);		
		}	
		public function getBasicInformation(){
			$procedure = "get" . $this->mode_ . "BasicInformation"; 
			return $this->dbHandler_->call($procedure, "%d", array($this->id_));						
		}	
		public function getNotifications(){
			$procedure = "get" . $this->mode_ . "Notifications"; 
			return $this->dbHandler_->call($procedure, "%d", array($this->id_), false);						
		}	
		public function getPendingRequests(){
			$procedure = "get" . $this->mode_ . "PendingRequests"; 
			return $this->dbHandler_->call($procedure, "%d", array($this->id_), false);						
		}			
}
?>
