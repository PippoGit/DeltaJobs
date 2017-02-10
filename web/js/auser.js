var dialog;
//INIT PAGE 
function initPage() {
	dialog = new DialogCard("","","","");
	search = new Search();
}

//AUSER
function showPortFolioElement(element) {
	 var wrapper = document.getElementById("portfolio-element");
	 var card = document.getElementsByClassName('dialog-card-content')[0];

	 dialog.setPictureWithSrc(element.getElementsByClassName("picture-hidden")[0].firstElementChild.src, true);
	 dialog.setTitleWithText(element.getElementsByClassName("title")[0].textContent);
	 dialog.setDescriptionWithText(element.getElementsByClassName("proj-description")[0].textContent);
	 
	 if(element.getElementsByClassName("proj-link")[0]) {
	 	dialog.setCaptionWithURL("Click to see the website of this project", element.getElementsByClassName("proj-link")[0].textContent);
	 }
	 else {
	 	dialog.setCaptionWithText("Project description");
	 }

	 dialog.changeState();
}

function hidePortFolioElement(element) {
	 dialog.changeState();
}

function asynchAddEntry(ev) {
	 var form1 = document.getElementById("add-entry");
	 var formData = new FormData(form1);
	 
	 var aHandler = new Ajax("../deltabase/sys/api/add_portfolio_entry.php", formData);

	 aHandler.done(function(response){
		console.log("OK");
		dialog.changeState();
		createUIEntry(response);//i, document.getElementById('entry-title').value, category, document.getElementById('entry-desc').value);
		form1.lastChild.disabled=false;
		form1.lastChild.textContent = "add";
		form1.reset();
	 });

	 //Custom fail behaviour
	 aHandler.fail(function(response, status){
	 	form1.lastChild.disabled=false;
		form1.lastChild.textContent = "add";
		form1.reset();

		if(response.error_id === "101") {
			changeLocation("../error.php?e=" + respons.error_id);
		}
		else {
			alert("HTTP Status: " + aHandler.http.status + " \n" + "Error code: " +  response.error_id + " – '" + response.msg + "'");
		}
	 });

	 aHandler.post();

	 form1.lastChild.disabled=true;
	 form1.lastChild.textContent = "wait...";
	 	 ev.preventDefault();

}

function createUIEntry(response) {

	var list = document.getElementById('portfolio-list');
	var sel = document.getElementById('category');
	var category = sel.options[sel.selectedIndex].text;
	var li = document.createElement('li'); // contains all gathered nodes

	var div = document.createElement('DIV');
	div.setAttribute("class", "card-s portfolio-card card-s-list-element");
	li.appendChild(div);

	var img = document.createElement('div');
	img.setAttribute("class", "picture-hidden");

	var cropped = document.createElement('IMG');
	cropped.setAttribute('alt', document.getElementById('entry-title').value);
	cropped.setAttribute('src', response.src);
	var sizeToCrop = [];

	sizeToCrop[0] = response.width;
	sizeToCrop[1] = response.height;

	if(sizeToCrop[0] > sizeToCrop[1]) {
		cropped.style.height = "300px";
		cropped.style.left = "50%";
		cropped.style.marginLeft = -1/2 * (sizeToCrop[0]*300/sizeToCrop[1]) + "px";
	}
	else {
		cropped.style.width = "300px";
		cropped.style.top = "50%";
		cropped.style.marginTop = -1/2 * (sizeToCrop[1]*300/sizeToCrop[0]) + "px";
	}
	
	img.appendChild(cropped);	
	div.appendChild(img);

	var div_0 = document.createElement('DIV');
	div_0.setAttribute("class", "title");
	div.appendChild(div_0);
	var text = document.createTextNode(document.getElementById('entry-title').value);
	div_0.appendChild(text);

	var div_1 = document.createElement('DIV');
	div_1.setAttribute("class", "category");
	div.appendChild(div_1);
	var text_0 = document.createTextNode(" – " + category + " – ");
	div_1.appendChild(text_0);

	var div_2 = document.createElement('DIV');
	div_2.setAttribute("class", "proj-description");
	div.appendChild(div_2);
	var text_1 = document.createTextNode(document.getElementById('entry-desc').value);
	div_2.appendChild(text_1);

	var div_2 = document.createElement('DIV');
	div_2.setAttribute("class", "proj-link");
	div.appendChild(div_2);
	var text_1 = document.createTextNode(document.getElementById('entry-link').value);
	div_2.appendChild(text_1);

	var removeportfoliobutton = document.createElement('DIV');
	removeportfoliobutton.setAttribute("id", "remove-portfolio-button");
	removeportfoliobutton.setAttribute("title", "remove entry");
	removeportfoliobutton.setAttribute("class", "actionbutton px_20 close-button");
	removeportfoliobutton.addEventListener("click", removeEntry.bind(undefined, 'entry-' + response.id));
	div.appendChild(removeportfoliobutton);

	li.setAttribute("id", "entry-"+response.id);
	li.addEventListener("click", showPortFolioElement.bind(undefined, li));
	list.appendChild(li);
}

function addEntry (){
	var elem = [];

	var docFragment = document.createDocumentFragment(); // contains all gathered nodes
	var formwrapper = document.createElement('DIV');
	formwrapper.setAttribute("id", "form-wrapper");
	formwrapper.className="form-addentry";

	docFragment.appendChild(formwrapper);

	var addentry = document.createElement('FORM');
	addentry.addEventListener("submit", asynchAddEntry);

	addentry.setAttribute("id", "add-entry");
	addentry.setAttribute("method", "post");
	addentry.setAttribute("enctype", "multipart/form-data");
	addentry.className = "addentry";
	formwrapper.appendChild(addentry);

	var entrytitle = document.createElement('INPUT');
	entrytitle.setAttribute("placeholder", "Entry title");
	entrytitle.setAttribute("name", "entry-title");
	entrytitle.setAttribute("id", "entry-title");
	entrytitle.required = true;
	entrytitle.className = "input alert px_413";
	addentry.appendChild(entrytitle);

	var entrylink = document.createElement('INPUT');
	entrylink.className= "input alert";
	entrylink.setAttribute("placeholder", "Website link");
	entrylink.setAttribute("name", "entry-link");
	entrylink.setAttribute("id", "entry-link");
	entrylink.setAttribute("type", "url");
	entrylink.className = "input alert px_413";
	entrylink.required = true;

	addentry.appendChild(entrylink);

	var category = document.createElement('SELECT');
	category.setAttribute("name", "category");
	category.setAttribute("id", "category");
	category.className= "select select-category";

	addentry.appendChild(category);


	var option = document.createElement('OPTION');
	option.setAttribute("value", "1");
	category.appendChild(option);
	var text = document.createTextNode("Web Design");
	option.appendChild(text);

	var option_0 = document.createElement('OPTION');
	option_0.setAttribute("value", "2");
	category.appendChild(option_0);
	var text_0 = document.createTextNode("Programming");
	option_0.appendChild(text_0);

	var option_1 = document.createElement('OPTION');
	option_1.setAttribute("value", "3");
	category.appendChild(option_1);
	var text_1 = document.createTextNode("Design");
	option_1.appendChild(text_1);

	var entrydesc = document.createElement('TEXTAREA');
	entrydesc.setAttribute("placeholder", "description");
	entrydesc.setAttribute("name", "entry-desc");
	entrydesc.setAttribute("id", "entry-desc");
	entrydesc.className = "input textarea entry-desc";


	entrydesc.required = true;

	addentry.appendChild(entrydesc);
	var uploadDiv = document.createElement('DIV');
	uploadDiv.className = "upload uploadentry";

	var label = document.createElement('LABEL');
	label.setAttribute("for", "src_picture");
	addentry.appendChild(label);
	var text_2 = document.createTextNode("Add an image of your project:");
	label.appendChild(text_2);

	var srcpicture = document.createElement('INPUT');
	srcpicture.setAttribute("name", "src_picture");
	srcpicture.setAttribute("id", "src_picture");
	srcpicture.setAttribute("type", "file");

	uploadDiv.appendChild(label);
	uploadDiv.appendChild(srcpicture);

	addentry.appendChild(uploadDiv);

	var button = document.createElement('BUTTON');
	button.setAttribute("type", "submit");
	button.className = "button blue"
	addentry.appendChild(button);
	var text_3 = document.createTextNode("Add entry");
	button.appendChild(text_3);

	elem.push(docFragment);

	dialog.setPictureWithSrc("");
	dialog.setCaptionWithText("");
	dialog.setTitleWithText("Add new entry");
	dialog.setDescriptionWithDOMElement(elem);
	dialog.changeState();
}

function removeEntry(id, ev)
{
	var numberId = id.split('-')[1];

	ev.stopPropagation();
		if(confirm("Do you really want to delete this entry?")) {

			var aHandler = new Ajax("../deltabase/sys/api/remove_portfolio_entry.php", {id: numberId});
			aHandler.done(function(response){
				var element = document.getElementById(id);
				element.outerHTML = "";
				delete element;
			});
		 	aHandler.post();
		} 
 }
function sendMessage(id_from, id_to) {
	var obj = document.getElementsByClassName("msg-object")[0].value;
	var txt = document.getElementById("msg-text").value;

	var aHandler = new Ajax("../deltabase/sys/api/write_new_message.php", {idf: id_from, idt:id_to, o: obj, m:txt});

	aHandler.done(function(response){
		dialog.changeState();
	});
 	aHandler.post();
}
function writeMessage(id_from, id_to, name_to) {
   var content = [];

   var bubble = document.createElement('input');
   bubble.className = "msg-object";
   bubble.setAttribute("placeholder", "No object");

   var replyDiv = document.createElement('div');
   var bubbleReply = document.createElement('div');
   var textArea = document.createElement('textarea');

   bubbleReply.className = "msg-bubble reply write";
   bubbleReply.setAttribute("data-idr", id_to);
   replyDiv.className = "reply-div";
   textArea.placeholder = "Message...";
   textArea.id = "msg-text";
   textArea.required = true;
   bubbleReply.appendChild(textArea);

   replyDiv.appendChild(bubble);
   replyDiv.appendChild(bubbleReply);

   var sendButton = document.createElement('div');
   sendButton.className = "actionbutton px_32 send-button";
   sendButton.addEventListener("click", sendMessage.bind(undefined, id_from, id_to));
   replyDiv.appendChild(sendButton);

   content.push(bubble);
   content.push(replyDiv);

	dialog.setPictureWithSrc("");
	dialog.setTitleWithText("Message to " + name_to);
	dialog.setDescriptionWithDOMElement(content);
	dialog.setCaptionWithText("");
	dialog.changeState();
}

function newCollaborationRequest(logged, destination) {
	var aHandler = new Ajax("../deltabase/sys/api/new_collaboration_request.php", {me: logged, to:destination});

	aHandler.done(function(response){
		alert("Request was succesfully sent.");
	});
	aHandler.fail(function(response){
		alert("ERROR: It seems like there is still a pending request for this user...");
	});
    aHandler.post();
}


window.onload = initPage;