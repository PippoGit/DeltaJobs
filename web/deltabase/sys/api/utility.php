<?php
   function goToErrorPage($error){
         header("Location: ../../../error.php?e=" . $error);
   }

	function echoCroppedStyle($picture, $max_size) {
		$size = getimagesize($picture); 
		if($size[0] == $size[1]) {  //no need to crop  
			return "width: " . $max_size ."px; height: " . $max_size . "px;";
		}
		if($size[0]>$size[1]) {
			$crop = 'height';
			$pos = 'left';
			$ds = ($max_size * $size[0] /$size[1])/2;

		}
		else {
			$crop = 'width';
			$pos = 'top';
			$ds = ($max_size * $size[1] /$size[0])/2;

		}

		return $crop . ': ' . $max_size .'px;' . $pos . ': 50%;' . 'margin-'.$pos . ":-" . $ds . 'px;';
	}

   function lev($s, $words) {
		$shortest = -1;

		// loop through words to find the closest
		foreach ($words as $word) {
		    // calculate the distance between the string,
		    // and the current word
		    $lev = levenshtein($s, $word);
		    // check for an exact match
		    if ($lev == 0) {
		        // closest word is this one (exact match)
		        $closest = $word;
		        $shortest = 0;
		        // break out of the loop; we've found an exact match
		        break;
		    }
		    // if this distance is less than the next found shortest
		    // distance, OR if a next shortest word has not yet been found
		    if ($lev <= $shortest || $shortest < 0) {
		        // set the closest match, and shortest distance
		        $closest  = $word;
		        $shortest = $lev;
		    }
		}
		return array($closest, $shortest);
	}

	function utf8ize($d) {
	    if (is_array($d)) {
	        foreach ($d as $k => $v) {
	            $d[$k] = utf8ize($v);
	        }
	    } 
	    else if (is_string ($d)) {
	        return utf8_encode($d);
	    }
	    return $d;
	}


	$errors = Array(
                   //Session and page error
                   '100'=>"The page you request was not found.", 
                   '101'=>"Session has expired", 
                   '102'=>"Wrong username or password. Please retry.",

                   //Upload errors
                   '200'=>"The uploaded file is not valid. Please retry.",

                   //Semantic error
                   '300'=>"Semantic error in your request/response",

                   //DB Errors
                   '600'=>"Error while connecting to the database.", 
                   '601'=>"Query not valid.", 
                   '602'=>"Error during connection with MySQL",
                   '603'=>"Query result is empty.",

                    //Generic Errors
                   '-1'=>"You don't have the privilege to access this resource.", 
                   '404'=>"four-oh-four",
                   '0' => "Generic error. Please contact us at report@delta.com.");  

	class DeltaException extends Exception {
		//Error
	    public function __construct($code = 0, $message=null, Exception $previous = null) {
	        // some code
	    	if($message == null) {
	    		$message = $GLOBALS['errors']['' . $code];
	    	}
	        // make sure everything is assigned properly
	        parent::__construct($message, $code, $previous);
	    }

	    //Tostring
	    public function __toString() {
	        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	    }

	    public function isCode($code) {
	    	return ($this->code == $code);
	    }

	    public function toJSON() {
	    	$error = array("status"=>"error", "error_id"=>$this->code, "msg"=>$this->message);
	    	return json_encode($error);
	    }		
	}

?>