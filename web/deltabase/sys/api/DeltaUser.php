<?php
	require_once("DeltaProfile.php");

	class DeltaUser extends DeltaProfile {
		//More Information
		private $collaborations__;
		private $skills__;
		private $portfolio__;

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

		function __construct($id_user){
			parent::__construct($id_user, 'u');
		}

		public function loadUserCollaborations(){
			$params = array($this->id_);
			$procedure = "getUserCollaborations";
			$this->collaborations__ = $this->dbHandler_->call($procedure, "%d", $params, false);
		}
		public function loadUserSkills(){
			$params = array($this->id_);
			$procedure = "getUserSkills";
			$tmp = $this->dbHandler_->call($procedure, "%d", $params, false);

			//parse skills in 3 different arrays
			foreach ($tmp as $entry) {
		        switch ($entry->id_category) {
		        	case '1':
		        		$this->skills__['1'][] = $entry;
		        		break;
		        	case '2':
		        		$this->skills__['2'][] = $entry;
		        		break;	
		        	case '3':
		        		$this->skills__['3'][] = $entry;
		        		break;			        	
		        }
		    }			
		}

		//load User Portfolio
		public function loadUserPortfolio(){
			$params = array($this->id_);
			$procedure = "getUserPortfolio";
			$this->portfolio__ = $this->dbHandler_->call($procedure, "%d", $params, false);			
		}

		//Load All
		public function loadAllInformation() {
			parent::loadProfileInformation();
			$this->loadUserCollaborations();
			$this->loadUserSkills();
			$this->loadUserPortfolio();
		}
	}
?>
