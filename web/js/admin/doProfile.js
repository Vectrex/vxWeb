this.vxWeb.doProfile = function() {

	"use strict";

    var mBox = vxWeb.messageToast(), f = vxJS.widget.xhrForm(document.forms[0], { command: "checkForm" }, { namesToHashes: true });

	var parseServerCheck = function(r) {

        if(r.success) {
            mBox.show(r.message || "Daten erfolgreich übernommen!", "toast-success");
        }
        else {
            mBox.show(r.message || "Fehler bei Übernahme der Daten!", "toast-error");
        }

    };

	f.addSubmit(f.element.elements["submit_profile"]);
	vxJS.event.addListener(f, "check", parseServerCheck);
};
