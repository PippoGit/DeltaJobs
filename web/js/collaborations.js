function parseCollaboration(rid, needDisclaimer) {
   var array = [];

   var form = document.createElement('form');
   var p = document.createElement('p');
   var p2 = document.createElement('p');
   var disclaimer = document.createElement('p');
   var accept = document.createElement('button');
   accept.className = "button alert right"
   var decline = document.createElement('button');
   decline.className = "button alert destroy-button  left"

   disclaimer.className = 'disclaimer';
   form.className = "notification-form";

   p.textContent = "Would you like to accept this collaboration request?";
   p2.textContent = "Contact the company for further information.";
   
   if(needDisclaimer) {
      disclaimer.textContent = 'You don\'t have to decide now, you can close the dialog window and consider the request anytime you want in "Pending Requests" section.';
   }
   else {
      disclaimer.textContent = "";
   }

   accept.textContent = "Accept";
   accept.value = "accept";
   decline.textContent = "Decline";
   decline.value = "decline";

   form.appendChild(p);
   form.appendChild(p2);
   form.appendChild(disclaimer);
   form.appendChild(accept);
   form.appendChild(decline);

   accept.addEventListener("click", acceptCollaboration.bind(undefined, rid));
   decline.addEventListener("click", declineCollaboration.bind(undefined, rid));

   array.push(form);
   return array;
}


function acceptCollaboration(id_collaboration, ev) 
{
   var aHandler = new Ajax("../deltabase/sys/api/accept_decline_collaboration.php", {
      id: id_collaboration,
      action: 'accept'
   });

   aHandler.done(function (response) {
      alert("The request has been accepted. Contact the company for further information!");
   });

   aHandler.fail( function(response) {
      alert("An error occur during the opeartion..." + response.msg);
      ev.preventDefault();
   });      
   aHandler.post();
}


function declineCollaboration(id_collaboration, ev) 
{
   var aHandler = new Ajax("../deltabase/sys/api/accept_decline_collaboration.php", {
      id: id_collaboration,
      action: 'decline'
   });

   aHandler.done(function (response) {
      alert("The request has been declined.");
   });

   aHandler.fail( function(response) {
      alert("An error occur during the opeartion..." + response.msg);
      ev.preventDefault();
   });
   aHandler.post();
   
}

function domToManageCollaboration(cid) {
   var arrayOfDom = [];
   var docFragment = document.createDocumentFragment(); // contains all gathered nodes

   var endcollaborationform = document.createElement('FORM');
   endcollaborationform.setAttribute("id", "end_collaboration_form");
   endcollaborationform.setAttribute("name", "end_collaboration_form");
   endcollaborationform.setAttribute("method", "post");
   docFragment.appendChild(endcollaborationform);

   var br = document.createElement('BR');
   endcollaborationform.appendChild(br);

   var label_1 = document.createElement('LABEL');
   label_1.setAttribute("for", "rate");
   endcollaborationform.appendChild(label_1);
   var text_1 = document.createTextNode("Rate (1-5): ");
   label_1.appendChild(text_1);

   var input_1 = document.createElement('INPUT');
   input_1.setAttribute("class", "input");
   input_1.setAttribute("name", "rate");
   input_1.id = "rate";
   input_1.setAttribute("type", "number");
   input_1.setAttribute("min", "1");
   input_1.setAttribute("max", "5");
   input_1.required = true;
   input_1.setAttribute("placeholder", "rate");

   input_1.style.marginLeft= "130px";
   input_1.style.width = "191px";
   endcollaborationform.appendChild(input_1);

   var br_1 = document.createElement('BR');
   endcollaborationform.appendChild(br_1);

   var button = document.createElement('BUTTON');
   button.setAttribute("name", "end_button");
   button.setAttribute("class", "button blue");

   button.style.margin ="10px auto";
   button.style.display = "block";

   endcollaborationform.appendChild(button);
   var text_2 = document.createTextNode("End collaboration");
   button.appendChild(text_2);

   endcollaborationform.addEventListener("submit", endCollaboration.bind(undefined, cid));


   arrayOfDom.push(docFragment);
   return arrayOfDom;
}

function endCollaboration(cid, ev) {
   var fv = new FormValidation(document.getElementById("end_collaboration_form"));
   var r = document.getElementById("rate").value;

   fv.setShouldBeNumberInRange1_5([0]);
   if(!fv.validate()) {
      return;
   }

   var aHandler = new Ajax("../deltabase/sys/api/end_collaboration.php", {
      id: cid,
      rate: r
   });

   aHandler.done(function (response) {
      alert("The collaboration has been ended. Thank you for using Delta Jobs.");
   });
   
   aHandler.post();
}
