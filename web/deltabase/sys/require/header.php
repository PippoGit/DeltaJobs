<?php
   if( !isset($_SESSION['id']) ) {
   		$button_id = "login-button";
   		$button_caption = "log in";
   		$logged = false;
      $button_onclick = "";
   }
   else {
   	  $button_id = "logout-button";
   		$button_caption = "log out";
   		$logged = true;   	
      $button_onclick = "onclick=\"location.href='../logout.php'\"";	
   }
?>
<header class="site-header">
  <div id="header-wrapper">
     <div id="logo"></div>
     <h1 id="logo-text">Delta-Jobs</h1>
     <div id="<?php echo $button_id; ?>" class="header-button" <?php echo $button_onclick; ?> > <?php echo $button_caption; ?></div>
  </div>
<?php 
	if($logged) {
		require_once("logged_nav.php"); 
	}
	else {
		require_once("nav.php");
	}
?>
  <div class="divider outer">
     <div class="divider inner">
     </div>
  </div>
</header>