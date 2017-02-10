var dashSlider, messageCard, search;
var CURRENT_COL = -1;

//INIT PAGE 
function initPage() {
   search = new Search();
   messageCard = new DialogCard("", "", "", "");
}

function manageUserPendingRequest(cid) {
	messageCard.setPictureWithSrc("");
	messageCard.setTitleWithText("Collaboration request");
	messageCard.setDescriptionWithDOMElement(parseCollaboration(cid, false));
	messageCard.setCaptionWithText("");
	messageCard.changeState();
}



function manageCompanyCollaboration(cid) {
	messageCard.setPictureWithSrc("");
	messageCard.setTitleWithText("Manage collaboration");
	messageCard.setCaptionWithText("Do you want to end this collaboration?");
	messageCard.setDescriptionWithDOMElement(domToManageCollaboration(cid));	
	messageCard.changeState();
}

window.onload = initPage;