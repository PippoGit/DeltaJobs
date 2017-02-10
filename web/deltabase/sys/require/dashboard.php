<?php
$notification_column_caption = array('c'=> 'Who want to work in your company', 'u'=>'Notifications');
$nomore_notification_caption = array('c'=> 'attention requests', 'u'=>'notifications');
?>
<!-- DASHBOARD -->
<section id="dashboard-s" class="section full-width-section">
   <header class="relative-heading">
      <h1>Dashboard </h1>
      <div id="refresh-notifications-button" class="actionbutton px_32 refresh" title="refresh" onclick="refreshDashboard()"></div>
   </header>
   <div class="column-layout">
      <section class="column first" id="inbox">
         <h3>Inbox
         <span id="new-messages-counter" class="counter">
         <?php if($newmessage_counter) { echo '+' . $newmessage_counter; }?>
         </span>
         </h3>
         <?php
         echo '<ul class="messages-list">';
            if($message_counter) {
               $i = -1;
               foreach($messages as $m) {
                  $i++;
                  $class = "message";
                  if (!$m->read)
                     $class .= " new";
                  echo '<li class="'. $class . '" id="message-'. $m->id_message . '" onclick="readMessage('. $m->id_message . ')">';
                     
                     if($mode=='u')
                        echo '   <span class="message-from">' . '<b>'.$m->company_name . '</b></span>';
                     else
                        echo '   <span class="message-from">' . '<b>'.$m->user_name . ' ' . $m->user_surname . '</b></span>';

                     echo '   <span>Object: </span><span id="message-object-'. $m->id_message . '">' . $m->object . ' </span>';
                     //echo '   <div class="reply" title="reply" onclick="replyMsg(event);"></div>';
                     echo '   <div class="actionbutton px_20 delete" title="delete message" onclick="deleteMsg(\'message-' .$m->id_message . '\', event);"></div>';
                     echo '</li>';
               }
            }
            else {
               echo '<li><p>There are no messages.</p></li>';
            }
         echo '</ul>';
         ?>
      </section>
      <section class="column last" id="notifications">
         <h3><?php echo $notification_column_caption[$mode];?>
         <span id="notifications-counter" class="counter">
         <?php if($notifications_counter) { echo '+' . $notifications_counter; }?>
         </span>
         </h3>
         <?php
            echo '<ul class="notifications-list">';
            if($notifications_counter) {
               $i = -1;
               foreach($notifications as $n) {
                  $i++;
                  if($mode=='u')
                     echo '<li class="notification new" id="notification-'. $n->id_notification . '"';
                  else
                     echo '<li class="notification new" id="arequest-'. $n->id_user . '-'. $n->id_company . '" onclick="changeLocation(\'profile.php?u=' . $n->id_user .'\')">';
                  if($mode=='u' && $n->id_category == '1')
                     echo ' onclick="readCollaboration('. $n->id_notification . ')">';
                  else if ($mode=='u' && $n->id_category == '2')
                     echo ' onclick="seeReview('. $n->id_notification . ', \'' . $n->link . '\')">';

                  if($mode=='u') {
                     echo '   <span class="notification-object">' . '<b>'. $n->company_name .'</b>' . $category_caption[$n->id_category] . ' </span>';
                     echo '   <span class="notification-time">' . $n->start_time . ' </span>';
                  }
                  else {
                     echo '   <span class="notification-object">' . '<b>'. $n->user_name . ' ' . $n->user_surname .'</b>' . ' is a <b>' . $n->role .' </b> ' .' interested in working for your company.'. ' </span>';
                  }
                  echo '</li>';
               }
            }
            else {
               echo '<li><p>There are no ' . $nomore_notification_caption[$mode] . '.</p></li>';
            }
            echo '</ul>';
            ?>
         </section>
      </div>
   </section>
   <!-- /DASHBOARD -->