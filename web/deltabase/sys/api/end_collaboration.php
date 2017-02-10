<?php 
   require_once("utility.php");
   require_once("DatabaseHandler.php");   
   session_start();  
   
   if( !isset($_SESSION['mode']) ) {
      $error = array("status"=>"error", "error_id"=>"101", "msg"=>$errors['101']);
      die(json_encode($error));   //If session has expired...
   }

   $id_collaboration = $_POST['id'] or goToErrorPage(-1); //You can't access this page
   $rate = $_POST['rate'] or goToErrorPage(-1); //You can't access this page

   $params = array($rate, $id_collaboration);

   try {
      $db = new DatabaseHandler;
      $db->executeQuery("UPDATE collaboration SET review=%d,date_end=NOW() WHERE id_collaboration=%d", $params);
   }
   catch(DeltaException $e) {
      die($e->toJSON());
   }
   finally {
      $result = array("status"=>"OK");
      echo json_encode($result);
   }
?>