<?php 
	$button_request_caption = array('u'=> " an attention request", 'c' =>' a collaboration request');
?>
<!-- CONTACT OR HIRE OR REQUEST -->
<section class="section full-width-section" style="text-align: center;">
<h1>Do you like <?php 
   echo $user->profileInformation_->name; 
   if($mode=='u')
      echo " " . $user->profileInformation_->surname;
?>?</h1>
  <button class="button white" style="
    margin-right: 15px;
" onclick="<?php echo 'writeMessage(' . $logged . ',' . $user->id_ . ', \''. $user->profileInformation_->name .'\')'; ?>">Write a message</button>
  <button class="button blue" style="
    margin-left: 15px;
"onclick="<?php echo 'newCollaborationRequest(' . $logged . ',' . $user->id_ . ')';?>">Send <?php 
   echo $button_request_caption[$client_mode];
?></button>
</div>
</section>
<!-- /CONTACT OR HIRE OR REQUEST -->