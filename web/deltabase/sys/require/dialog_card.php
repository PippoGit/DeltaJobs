<?php 
   $prefix_border ="";
   $prefix_background ="";

   if($current_page === "index") {
        $prefix_border = "noborder-bottom";
        $prefix_background = "nobackground";
    }
?>
   <!-- OVERLAY DIALOGBOX -->
   <div id="dialog-window-wrapper" class="overlay">
      <div class="dialog-card">
         <header <?php echo ' class="' . $prefix_border . '"'; ?> >
            <span class="title "></span>
            <div class="actionbutton close-button" id="close-dialog"></div>
            <div class="picture"></div>
         </header>
         <div class="content <?php echo $prefix_background; ?>">
            <div class="caption"></div>
            <div class="description">
            </div>
         </div>
      </div>
   </div>
   <!-- /OVERLAY DIALOGBOX -->
