if(!this.vxWeb) {
	this.vxWeb = {};
}

this.vxWeb.doProfile = function() {

	"use strict";

	var	mBox = document.getElementById("messageBox"), timeoutId,
		f = vxJS.widget.xhrForm(document.forms[0], { command: "checkForm" });

	var parseServerCheck = function(r) {
		var txt;

		if(r.success) {
			txt = r.message || "Daten erfolgreich übernommen!";

			vxJS.dom.removeClassName(mBox, "error");
			vxJS.dom.addClassName(mBox, "success");
		}

		else {
			txt = r.message || "Fehler bei Übernahme der Daten!";

			vxJS.dom.removeClassName(mBox, "success");
			vxJS.dom.addClassName(mBox, "error");
		}

		mBox.firstChild.nodeValue = txt;

		vxJS.dom.removeClassName(mBox, "fadeOutUp");
		vxJS.dom.addClassName(mBox, "fadeInDown");

		if(timeoutId) {
			window.clearTimeout(timeoutId);
		}
		timeoutId = window.setTimeout(function() {
			vxJS.dom.removeClassName(mBox, "fadeInDown");
			vxJS.dom.addClassName(mBox, "fadeOutUp");
		}, 2000);
	};

	f.addSubmit(f.element.elements["submit_profile"]);
	vxJS.event.addListener(f, "check", parseServerCheck);
};
