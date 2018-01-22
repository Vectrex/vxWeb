if(!this.vxWeb) {
	this.vxWeb = {};
}

this.vxWeb.doProfile = function() {

	"use strict";

	var	f = vxJS.widget.xhrForm(document.forms[0], { command: "checkForm" });

	var parseServerCheck = function(r) {
        var mBox = document.getElementById("messageBox"), timeoutId, txt;

        if(r.success) {
            txt = r.message || "Daten erfolgreich übernommen!";

            vxJS.dom.removeClassName(mBox, "toast-error");
            vxJS.dom.addClassName(mBox, "toast-success");

        }

        else {
            txt = r.message || "Fehler bei Übernahme der Daten!";

            vxJS.dom.removeClassName(mBox, "toast-success");
            vxJS.dom.addClassName(mBox, "toast-error");
        }

        mBox.firstChild.nodeValue = txt;

        vxJS.dom.addClassName(mBox, "display");

        if(timeoutId) {
            window.clearTimeout(timeoutId);
        }
        timeoutId = window.setTimeout(function() {
            vxJS.dom.removeClassName(mBox, "display");
        }, 5000);

    };

	f.addSubmit(f.element.elements["submit_profile"]);
	vxJS.event.addListener(f, "check", parseServerCheck);
};
