if(!this.vxWeb) {
	this.vxWeb = {};
}

this.vxWeb.doLogin = function() {

	"use strict";

	var mBox = document.getElementById("messageBox");

	var f = vxJS.widget.xhrForm(document.forms[0]);

	var parseServerCheck = function(r) {

		var txt = r.message || "Benutzername oder Passwort falsch!";

		vxJS.dom.removeClassName(mBox, "d-none");

		mBox.firstChild.nodeValue = txt;

	};

	f.addSubmit(f.element.elements["submit_login"]);

	vxJS.event.addListener(f, "check", parseServerCheck);

};
