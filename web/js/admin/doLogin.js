this.vxWeb.doLogin = function() {

	"use strict";

	var mBox = vxWeb.messageToast(), f = vxJS.widget.xhrForm(document.forms[0]);

	var parseServerCheck = function(r) {
        mBox.show(r.message || "Benutzername oder Passwort falsch!", "toast-error");
	};

	f.addSubmit(f.element.elements["submit_login"]);

	vxJS.event.addListener(f, "check", parseServerCheck);

};
