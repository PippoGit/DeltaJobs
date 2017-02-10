<?php 
   require_once('utility.php');
   require_once('DeltaProfile.php');   

   session_start(); 
   
   if(!isset($_SESSION['id']) ) {
      $error = array('status'=>'error', 'error_id'=>'101', 'msg'=>$errors['101']);
      die(json_encode($error));   //If session has expired...
   }

   $mode = $_SESSION['mode'] or goToErrorPage(-1);

   $result = null;

   try {
      $user = new DeltaProfile($_SESSION['id'], $mode);
      $result = $user->getMessages();
   }
   catch(DeltaException $e) {
      die($e->toJSON());
   }
   finally {
      if( !is_null($result) ) {
         echo json_encode($result);
      }
   }
?>