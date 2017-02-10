var dashSlider, messageCard, search;
var CURRENT_MSG = -1,
   CURRENT_NOT = -1;

//INIT PAGE 
function initPage() {
   dashSlider = new Slider("dashboard-suggestion-slider");
   search = new Search();
   messageCard = new DialogCard("", "", "", "");
}

//APPLE    
function slide(direction) {
   dashSlider.slide(SlidingDirection[direction]);
   clearInterval(dashSlider._timer);
   dashSlider.setTimer(dashSlider._timerDirection, dashSlider._timerDelay);
}

function readCollaboration(nid) {
   var UINotification = document.getElementById("notification-" + nid),
      aHandler;
   aHandler = new Ajax("../deltabase/sys/api/read_notification.php", {
      id: nid
   });

   aHandler.done(function (response) {

      messageCard.setPictureWithSrc("");
      messageCard.setTitleWithText("New collaboration request");
      messageCard.setDescriptionWithDOMElement(parseCollaboration(response.link, true));
      messageCard.setCaptionWithText("");
      messageCard.changeState();

      if (UINotification.className === "notification new") {
         UINotification.className = "notification";
         evaluateCounter("notifications-counter", -1);
      }
   });
   aHandler.post();
}

function parseMessage(response, mid) {
   var content = [];
   CURRENT_MSG = response;

   var bubble = document.createElement('div');
   var corner = document.createElement('div');
   corner.className = "corner blue";
   bubble.className = "msg-bubble";
   bubble.textContent = CURRENT_MSG.text;
   bubble.appendChild(corner);

   var replyDiv = document.createElement('div');
   var bubbleReply = document.createElement('div');
   var textArea = document.createElement('textarea');

   bubbleReply.className = "msg-bubble reply";
   bubbleReply.setAttribute("data-mid", CURRENT_MSG.id_message);
   replyDiv.className = "reply-div";
   textArea.placeholder = "Reply...";
   textArea.id = "reply-msg";
   textArea.required = true;
   bubbleReply.appendChild(textArea);
   replyDiv.appendChild(bubbleReply);

   var sendButton = document.createElement('div');
   sendButton.className = "actionbutton px_32 send-button";
   sendButton.addEventListener("click", replyMsg);
   replyDiv.appendChild(sendButton);

   content.push(bubble);
   content.push(replyDiv);

   return content;
}

//AUSER
function readMessage(mid) {
   var UIMessage = document.getElementById("message-" + mid),
      aHandler;

   aHandler = new Ajax("../deltabase/sys/api/read_message.php", { id: mid });

   aHandler.done(function (response) {
      messageCard.setPictureWithSrc("");
      messageCard.setTitleWithText("Message from " + UIMessage.getElementsByClassName("message-from")[0].textContent);
      messageCard.setDescriptionWithDOMElement(parseMessage(response, mid));
      messageCard.setCaptionWithText(document.getElementById("message-object-" + mid).textContent);
      messageCard.changeState();
      if (UIMessage.className === "message new") {
         UIMessage.className = "message";
         evaluateCounter("new-messages-counter", -1);
      }
   });

   aHandler.post();
}

function seeReview(nid, link) {
   aHandler = new Ajax("../deltabase/sys/api/read_notification.php", {
      id: nid
   });

   aHandler.done(function (response) {
      changeLocation(link);
   });

   aHandler.post();
}

function deleteMsg(id, ev) {
   ev.stopPropagation();
   var numberId = id.split('-')[1];

   if (confirm("Do you really want to delete this message?")) {

      var aHandler = new Ajax("../deltabase/sys/api/remove_message.php", {
         id: numberId
      });
      aHandler.done(function (response) {
         var element = document.getElementById(id);
         element.outerHTML = "";
         if (element.className === 'message new') {
            evaluateCounter("new-messages-counter", -1);
         }
      });
      aHandler.post();
   }
}

function replyMsg() {
   var id_company = -1, id_user = -1;
   //var mid = CURRENT_MSG['id_message'];
   if (typeof USER_ID !== 'undefined') {
      id_to = CURRENT_MSG.from_com;
      id_from = USER_ID;
   } else {
      id_to = CURRENT_MSG.from_usr;
      id_from = COMPANY_ID;
   }
   var message = document.getElementById("reply-msg").value;

   if (message === "") {
      return alert("You need to write something, what do you think?");
   }
   var object = "RE: " + messageCard._caption.textContent;

   aHandler = new Ajax("../deltabase/sys/api/write_new_message.php", {
      idt: id_to,
      idf: id_from,
      m: message,
      o: object
   });

   aHandler.done(function (response) {
      messageCard.setPictureWithSrc("");
      messageCard.setTitleWithText("Message sent");
      messageCard.setDescriptionWithText("Now you can close this dialog card...");
      messageCard.setCaptionWithText("");
   });
   aHandler.post();

}

function cleanDashboard() {
   removeAllChildrenOf(document.getElementsByClassName('messages-list')[0]);
   removeAllChildrenOf(document.getElementsByClassName('notifications-list')[0]);
}

function loadMessages() {
   aHandler = new Ajax("../deltabase/sys/api/get_messages.php", {});

   aHandler.done(function (response) {
      var list = document.getElementsByClassName('messages-list')[0];

      if(response.length === 0) {
         var li = document.createElement("li");
         var text_caption = document.createTextNode("There are no messages.");
         var p = document.createElement('p');
         
         p.appendChild(text_caption);
         li.appendChild(p);
         list.appendChild(li);
         return;
      }
      var newMessages = 0;
      var oldCounter = parseInt(document.getElementById('new-messages-counter').textContent);
      for(var i =0; i<response.length; i++) {
         var docFragment = document.createDocumentFragment(); // contains all gathered nodes
         var message = document.createElement('LI');

         if(response[i].read === "0") {
            newMessages++;
            message.className = "message new";
         }
         else { 
            message.className = "message";
         }

         message.setAttribute("id", "message-" + response[i].id_message);
         message.addEventListener("click", readMessage.bind(undefined, response[i].id_message));

         docFragment.appendChild(message);

         var span = document.createElement('SPAN');
         span.setAttribute("class", "message-from");
         message.appendChild(span);

         var b = document.createElement('B');
         span.appendChild(b);

         var from = "";
         if(MODE === 'u') {
            from = response[i].company_name;
         }
         else {
            from = response[i].user_name + " " + response[i].user_surname;
         }


         var text = document.createTextNode(from);
         b.appendChild(text);

         var span_0 = document.createElement('SPAN');
         message.appendChild(span_0);
         var text_0 = document.createTextNode("Object: ");
         span_0.appendChild(text_0);

         var messageobject = document.createElement('SPAN');
         messageobject.setAttribute("id", "message-object-" + response[i].id_message);
         message.appendChild(messageobject);
         var text_1 = document.createTextNode(response[i].object);
         messageobject.appendChild(text_1);

         var div = document.createElement('DIV');
         div.setAttribute("class", "actionbutton px_20 delete");
         div.setAttribute("title", "delete message");
         //XXX CSP will forbid inline JavaScript and event handlers. Use addEventHandler instead!
         div.addEventListener("click", deleteMsg.bind(undefined, 'message-' + response[i].id_message));
         message.appendChild(div);
         list.appendChild(docFragment);
      }

      if(oldCounter < newMessages) {
         evaluateCounter('new-messages-counter', newMessages-oldCounter);
      }
   });
   aHandler.post();   
}

function loadNotifications() {
   aHandler = new Ajax("../deltabase/sys/api/get_notifications.php", {});

   aHandler.done(function (response) {
      var list = document.getElementsByClassName('notifications-list')[0];

      if(response.length === 0) {
         var li = document.createElement("li");
         var text_caption = document.createTextNode("There are no notifications.");
         var p = document.createElement('p');
         p.appendChild(text_caption);
         li.appendChild(p);
         list.appendChild(li);
         return;
      }

      var newNotifications = 0;
      var oldCounter = parseInt(document.getElementById('notifications-counter').textContent);

      var category = [];
      category[1] = " sent you a ";
      category[2] = " left you a ";
      category[3] = "collaboration request";
      category[4] = "review";
      var action = "";


      for(var i =0; i<response.length; i++) {
         if (response[i].id_category === "2") {
            action = seeReview.bind(undefined, response[i].id_notification, response[i].link);
         }
         else {
            action = readCollaboration.bind(undefined, response[i].id_notification);
         }

         var docFragment = document.createDocumentFragment(); // contains all gathered nodes
         var notification = document.createElement('LI');

         if(response[i].received === "0") {
            newNotifications++;
            notification.className = "notification new";
         }
         else { 
            notification.className = "notification";
         }

         if(MODE === 'u') {
            from = response[i].company_name;            
         }
         else {
            from = response[i].user_name + " " + response[i].user_surname;
            action = "";     
            object = " is a ";
         }
         var docFragment = document.createDocumentFragment(); // contains all gathered nodes

         var notification = document.createElement('LI');
         notification.setAttribute("class", "notification new");
         notification.setAttribute("id", "notification-" + response[i].id_notification);
         notification.addEventListener("click", action)


         docFragment.appendChild(notification);

         var span = document.createElement('SPAN');
         span.setAttribute("class", "notification-object");
         notification.appendChild(span);
         var b = document.createElement('B');
         span.appendChild(b);
         var text = document.createTextNode(from);
         b.appendChild(text);

         if(MODE==='c') {
            var object_c = document.createTextNode(object);
            var b_c = document.createElement('B');
            b_c.textContent = response[i].role;
            var caption2 = document.createTextNode( " interested in working for your company.");
            span.appendChild(object_c);
            span.appendChild(b_c);
            span.appendChild(caption2);

         }
         else {
            var text_0 = document.createTextNode(category[response[i].id_category]);
            span.appendChild(text_0);

            var b_0 = document.createElement('B');
            span.appendChild(b_0);
            var text_1 = document.createTextNode(category[parseInt(response[i].id_category) + 2]);
            b_0.appendChild(text_1);
            var text_2 = document.createTextNode(".");
            span.appendChild(text_2);

            var span_0 = document.createElement('SPAN');
            span_0.setAttribute("class", "notification-time");
            notification.appendChild(span_0);
            var text_3 = document.createTextNode(response[i].start_time);
            span_0.appendChild(text_3);
         }
         list.appendChild(docFragment);
      }
      if(oldCounter < newNotifications) {
         evaluateCounter('notifications-counter', newNotifications-oldCounter);
      }   });
   aHandler.post();   
}

function refreshDashboard() {
   cleanDashboard();
   loadMessages();
   loadNotifications();
}

window.onload = initPage;