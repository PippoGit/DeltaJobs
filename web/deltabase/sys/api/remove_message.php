<?php 
   require_once("utility.php");
   require_once("DatabaseHandler.php");   
   session_start(); 
    
   if( !isset($_SESSION['id']) ) {
      $error = array("status"=>"error", "error_id"=>"101", "msg"=>$errors['101']);
      die(json_encode($error));   //If session has expired...
   }

   $id_message = $_POST['id'] or goToErrorPage(-1); //You can't access this page
   $mode = $_SESSION['mode'] or goToErrorPage(-1);
   
   $table = array('u'=>"usr_inbox", 'c'=>"com_inbox");

   try {
      $db = new DatabaseHandler;      
      $params = array($id_message);

      $db->executeQuery("DELETE FROM `" . $table[$mode] . "` WHERE id_message =%d", $params, true);
   }
   catch(DeltaException $e) {
      die($e->toJSON());
   }
   finally {
      $result = array("status"=>"ok");
      echo json_encode($result);
   }
?>