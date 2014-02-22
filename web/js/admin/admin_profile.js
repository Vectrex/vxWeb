vxJS.event.addDomReadyListener(function() {
	"use strict";
	
	var	mBox = document.getElementById("messageBox"), messageBox = vxJS.element.register(mBox);

	var f = vxJS.widget.xhrForm(document.forms[0], { command: "checkForm" });

	var parseServerCheck = function(r) {
		var txt, height;

		if(r.success) {
			txt = r.message || "Daten erfolgreich übernommen!";

			vxJS.dom.removeClassName(mBox, "messageBoxError");
			vxJS.dom.addClassName(mBox, "messageBoxSuccess");
		}
		
		else {
			txt = r.message || "Fehler bei Übernahme der Daten!";

			vxJS.dom.removeClassName(mBox, "messageBoxSuccess");
			vxJS.dom.addClassName(mBox, "messageBoxError");
		}

		mBox.firstChild.nodeValue = txt;

		height = vxJS.dom.getElementSize(mBox).y;

		mBox.style.top = -height + "px";
		messageBox.
			clearFxQueue().
			fx("moveRelative", { to: new Coord(0, height), duration: 0.5, transition: "decel" }).
			pause(3).
			fx("moveRelative", { to: new Coord(0, -height), duration: 0.5, transition: "accel" });
	};

	f.addSubmit(f.element.elements["submit_profile"]);
	vxJS.event.addListener(f, "check", parseServerCheck);
	vxJS.event.addListener(messageBox, "finishFxQueue", function() { this.element.style.top = "-100em"; });
});
