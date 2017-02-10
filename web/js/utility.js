function removeAllChildrenOf(myNode) {
   while (myNode.firstChild) {
       myNode.removeChild(myNode.firstChild);
   }
}

//Evaluate a generic counter used in the website
function evaluateCounter(id, value) {
   var counter = document.getElementById(id);
   if (counter.textContent.trim() === "") {
      return;
   }
   var c = parseInt(counter.textContent) + value;
   if (c === 0) {
      counter.textContent = "";
   } else {
      counter.textContent = "+" + c;
   }
}

function changeLocation(url) {
   window.location = url;
}