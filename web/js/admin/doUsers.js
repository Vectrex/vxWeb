this.vxWeb.doUsers = function() {

	"use strict";

	var	mBox = document.getElementById("messageBox"), timeoutId,
		f = vxJS.widget.xhrForm(document.forms[0], { uri: vxWeb.routes.users });

	var parseServerCheck = function(r) {
		var txt;

		if(r.success) {
			txt = r.message || "Daten erfolgreich übernommen!";

			vxJS.dom.removeClassName(mBox, "error");
			vxJS.dom.addClassName(mBox, "success");
			
			if(r.id) {
				vxWeb.parameters.id = r.id;
			}
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

	f.addSubmit(f.element.elements["submit_user"]);

	vxJS.event.addListener(f, "check", parseServerCheck);
	vxJS.event.addListener(f, "beforeSubmit", function() {
		if(vxWeb.parameters.id) {
			this.setPayload( { id: vxWeb.parameters.id });
		}
	});

};
