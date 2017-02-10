<?php
  require_once("deltabase/sys/api/utility.php");

   if (!empty($_GET) && array_key_exists($_GET['e'], $GLOBALS['errors'])) {
      $error_code = $_GET['e'];
   }
   else {
      $error_code = "0";   
   }
   
   $e = $GLOBALS['errors'][$error_code];
?>

<!doctype html>
<html lang="en">
  <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <title>delta-jobs.com – Error</title>
   <!-- Include CSS and stylez and some magic-->
   <link href="./css/apple.css" rel="stylesheet" media="screen and (min-width: 900px)" type="text/css">
   <link href="./css/apple-mobile.css" rel="stylesheet" media="screen and (max-width: 900px)" type="text/css">
   <link href="./css/localfonts.css" rel="stylesheet" type="text/css">
   </head>
   <body>
      <header class="site-header">
         <div id="header-wrapper">
            <div id="logo"></div>
            <h1 id="logo-text">Delta-Jobs</h1>
         </div>
         <nav>
            <a href="./index.php">
               <div class="menu-item current">
                  return to the home
               </div>
            </a>
         </nav>
         <div class="divider outer">
            <div class="divider inner">
            </div>
         </div>
      </header>
      <main>
         <section class="intro-s">
            <header class="section-intro section-header dash-intro-header">
               <h1>An error has occurred :(</h1>
               <span class="subtitle"> <?php echo $e; ?> </span>
            </header>  
         </section>
      </main>
      <footer>
         <div id="footer-content">
            <div id="credit">
               Filippo Scotto © 2014-2015 UNIPI
            </div>
         </div>
      </footer>
   </body>
</html>