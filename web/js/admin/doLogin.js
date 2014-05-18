if(!this.vxWeb) {
	this.vxWeb = {};
}

this.vxWeb.doLogin = function() {

	"use strict";

	var mBox = document.getElementById("messageBox"), timeoutId;

	var f = vxJS.widget.xhrForm(document.forms[0]);

	var parseServerCheck = function(r) {

		var txt = r.message || "Benutzername oder Passwort falsch!";

		vxJS.dom.addClassName(mBox, "messageBoxError");

		mBox.firstChild.nodeValue = txt;

		vxJS.dom.removeClassName(mBox, "fadeOutUp");
		vxJS.dom.addClassName(mBox, "fadeInDown");

		if(timeoutId) {
			window.clearTimeout(timeoutId);
		}
		timeoutId = window.setTimeout(function() {
			vxJS.dom.removeClassName(mBox, "fadeInDown");
			vxJS.dom.addClassName(mBox, "fadeOutUp");
		}, 3000);
	};

	f.addSubmit(f.element.elements["submit_login"]);

	vxJS.event.addListener(f, "check", parseServerCheck);

	// file upload via drag and drop

	if(vxJS.xhrObj().upload && window.File && window.FileList && window.FileReader) {
		var xhr = vxJS.xhr( { upload: true, uri: location.origin + "/upload.php", timeout: 10000 } ),
			filesQueue = [], uploadActive;
	
		vxJS.event.addListener(document.getElementById("adminLogin"), "dragover", function(e) { console.log("over - highlight box"); e.preventDefault(); });
		vxJS.event.addListener(document.getElementById("adminLogin"), "dragleave", function(e) { console.log("leave - un-highlight box"); e.preventDefault(); });
		vxJS.event.addListener(document.getElementById("adminLogin"), "drop", function(e) {
			var i, l, f, files = e.target.files || e.dataTransfer.files;

			for(i = 0, l = files.length; i < l; ++i) {
				filesQueue.push(files[i]);
			}
	
			if(!uploadActive) {
				if(f = filesQueue.shift()) {
					uploadActive = true;
					xhr.use(null, { filename: f.name, file: f }).submit();
				}
			}

			vxJS.event.preventDefault(e);
			vxJS.event.cancelBubbling(e);
		});

		vxJS.event.addListener(xhr, "timeout", function() {
			uploadActive = false;
			window.alert("Upload time exceeds 10s.");
		});

		vxJS.event.addListener(xhr, "complete", function() {

			var f;
			
			console.log(this.response);

			if(f = filesQueue.shift()) {
				this.use(null, { filename: f.name, file: f }).submit();
			}
			
			else {
				uploadActive = false;
			}
			
		});
	}
};
