/////////////////////////////// Classe DialogCard
function DialogCard(title, picture, caption, description) {
   this._element = document.getElementById("dialog-window-wrapper");

   this._caption = this._element.getElementsByClassName("caption")[0];
   this._title = this._element.getElementsByClassName("title")[0];
   this._header = this._element.getElementsByTagName("header")[0];
   this._description = this._element.getElementsByClassName("description")[0];
   this._picture = this._element.getElementsByClassName("picture")[0];

   this.setTitleWithText(title);
   this.setCaptionWithText(caption);
   this.setDescriptionWithText(description);
   this.setPictureWithSrc(picture);


   this._currentState = 0; // 0 closed, 1 ACTIVE

   document.getElementById("close-dialog").addEventListener("click", this.changeState.bind(this));
}

DialogCard.prototype.setTitleWithText = function (title) {
   if (title === "") {
      this._title.style.display = "none";
      this._header.style.paddingTop = "30px";

   } else {
      this._title.style.display = "block";
      this._title.textContent = title;
      this._header.style.paddingTop = "0px";
   }
};
DialogCard.prototype.setCaptionWithText = function (caption) {
   if (caption === "") {
      this._caption.style.display = "none";
   } else {
      this._caption.style.display = "block";
      this._caption.textContent = caption;
   }
};
DialogCard.prototype.setCaptionWithURL = function (caption, url) {
   removeAllChildrenOf(this._caption);

   this._caption.style.display = "block";
   var link = document.createElement("a");
   link.target = "_blank";
   var prefix = 'http://';
   var prefixS = 'https://';

   if (url.substr(0, prefix.length) !== prefix || url.substr(0, prefixS.length) !== prefixS)
   {
       url = prefix + url.trim();
   }
   link.href = url;

   link.textContent = caption;
   this._caption.appendChild(link);
}
DialogCard.prototype.setDescriptionWithText = function (description) {
   if (description === "") {
      this._description.style.display = "none";
   } else {
      this._description.style.display = "block";
      this._description.textContent = description;
   }
};
DialogCard.prototype.setDescriptionWithDOMElement = function (element) {
      if (element === undefined) {
         this._description.style.display = "none";
      }
       else {
         this._description.style.display = "block";
         
         var myNode = this._description;
         removeAllChildrenOf(myNode);
         
         for(var i=0; i<element.length; i++) {
            myNode.appendChild(element[i]);             
         }
      }
};
DialogCard.prototype.setPictureWithSrc = function (picture) {
   if (picture === "") {
      this._picture.style.display = "none";
   } else {
      var p = new Image();
      p.src = picture;
      this._picture.style.display = "block";
      this._picture.style.height = 500 * p.height / p.width + "px";
      this._picture.style.backgroundImage = "url(" + picture + ")";
      //this._element.getElementsByClassName("dialog-card")[0].style.top = "20%";
   }
};

DialogCard.prototype.changeState = function () {
   if (this._currentState) {
      this._element.style.display = "none";
   } else {
      this._element.style.display = "block";
            this._element.firstElementChild.style.marginTop = -1 * parseInt(this._element.firstElementChild.offsetHeight)/2 +"px";

   }
   this._currentState = !this._currentState;
};