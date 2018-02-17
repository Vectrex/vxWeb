this.vxWeb.doUsers = function() {

	"use strict";

    var mBox = vxWeb.messageToast(), f = vxJS.widget.xhrForm(document.forms[0], { uri: vxWeb.routes.users });

	var parseServerCheck = function(r) {

        if(r.success) {
            mBox.show(r.message || "Daten erfolgreich übernommen!", "toast-success");
            if(r.id) {
                vxWeb.parameters.id = r.id;
            }
        }
        else {
            mBox.show(r.message || "Fehler bei Übernahme der Daten!", "toast-error");
        }

	};

	f.addSubmit(f.element.elements["submit_user"]);

	vxJS.event.addListener(f, "check", parseServerCheck);
	vxJS.event.addListener(f, "beforeSubmit", function() {
		if(vxWeb.parameters.id) {
			this.setPayload( { id: vxWeb.parameters.id });
		}
	});

};
