<?php 
   //Init
   require_once("../deltabase/sys/api/DeltaCompany.php");
   require_once("../deltabase/sys/api/DeltaUser.php");

   session_start();
   $current_page = basename(__FILE__, '.php'); 

   $logged = $_SESSION['id'] or 0;
   $client_mode = $_SESSION['mode'] or 0;
   $second_option_menu = array('u'=>"pending requests", 'c'=>"collaborations");


   if(isset($_GET['u']))
      $mode = 'u';
   else
      $mode = 'c';

   //init the page
   switch($mode) {
      case "u":
         $user = new DeltaUser($_GET['u']); 
         $c = 'undefined';
         $more_info = "full_user_profile.php";
         break;
      case "c":
         $user = new DeltaCompany($_GET['c']); 
         $more_info = "full_company_profile.php";   
         $u = 'undefined';
         break;
   }

   $user->loadAllInformation();

?>
<!doctype html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>delta-jobs.com – <?php echo $user->profileInformation_->name; ?></title>
   <!-- Include CSS and stylez and some magic-->
   <link href="../css/apple.css" rel="stylesheet" type="text/css">
   <link href="../css/profile.css" rel="stylesheet" type="text/css">
   <link href="../css/search-bar.css" rel="stylesheet" type="text/css">
   <link href="../css/profile-common-style.css" rel="stylesheet" type="text/css">

   <link href="../css/localfonts.css" rel="stylesheet" type="text/css">
   <!-- Include javascript -->
   <script type="text/javascript" src="../js/auser.js"></script>
   <script type="text/javascript" src="../js/DialogCard.js"></script>
   <script type="text/javascript" src="../js/Search.js"></script>
   <script type="text/javascript" src="../js/Ajax.js"></script>  
   <script type="text/javascript" src="../js/utility.js"></script>   
   <script type="text/javascript">
      var MODE = '<?php echo $mode; ?>';       
   </script>
</head>

<body>
   <header class="site-header">
      <div id="header-wrapper">
         <div id="logo"></div>
         <h1 id="logo-text">Delta-Jobs</h1>
         <?php 
            if($logged == $user->id_) {
               echo "<div id=\"logout-button\" class=\"header-button\" onclick=\"changeLocation('edit_profile.php')\">edit profile</div>";
            }
         ?>
      </div>
      <nav>
          <?php 
            if($logged) { ?>
         <a href="dashboard.php">
            <div class="menu-item">
               home
            </div>
         </a>
         <a href="pending_requests.php">
            <div class="menu-item">
               <?php echo $second_option_menu[$client_mode]; ?></div>
         </a>   
         <a href="profile.php?<?php echo $client_mode . '=' . $logged; ?>">
            <div class="menu-item">
               your profile</div>
         </a>
         <input id="search-bar" class="search-bar clickable" placeholder="search" accesskey="f">
         <?php
            }
            else {
         ?>

         <a href="../index.php">
            <div class="menu-item">
               discover delta jobs
            </div>
         </a>

         <?php } ?>

      </nav>
      <div class="divider outer">
         <div class="divider inner">
         </div>
      </div>
   </header>
   <?php require_once("../deltabase/sys/require/dialog_card.php"); ?>
   <?php require_once("../deltabase/sys/require/search_result.php"); ?>
   <main>
      <?php require_once("../deltabase/sys/require/profile_intro.php"); ?>
      <?php require_once("../deltabase/sys/require/" . $more_info);  ?>
      <?php if($logged && $logged !== $user->id_ && $mode !== $client_mode || $logged === $user->id_ && $mode !== $client_mode) { require_once("../deltabase/sys/require/contact_hire.php"); }?>      
   </main>
   <?php require_once("../deltabase/sys/require/footer.php"); ?>

</body>
</html>