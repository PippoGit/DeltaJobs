<?php
	require_once("DeltaProfile.php");

	class DeltaCompany extends DeltaProfile {

		function __construct($id_user) {
			parent::__construct($id_user, 'c');
		}

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
	}
?>

