if(!this.vxWeb) {
	this.vxWeb = {};
}

this.vxWeb.doLogin = function() {

	"use strict";

	var mBox = document.getElementById("messageBox"), timeoutId;

	var f = vxJS.widget.xhrForm(document.forms[0]);

	var parseServerCheck = function(r) {

		var txt = r.message || "Benutzername oder Passwort falsch!";

        vxJS.dom.addClassName(mBox, "toast-error");

		mBox.firstChild.nodeValue = txt;

        vxJS.dom.addClassName(mBox, "display");

        if(timeoutId) {
            window.clearTimeout(timeoutId);
        }
        timeoutId = window.setTimeout(function() {
            vxJS.dom.removeClassName(mBox, "display");
        }, 5000);
	};

	f.addSubmit(f.element.elements["submit_login"]);

	vxJS.event.addListener(f, "check", parseServerCheck);

};
