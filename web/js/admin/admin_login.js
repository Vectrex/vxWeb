vxJS.event.addDomReadyListener(function() {
	"use strict";

	var mBox = document.getElementById("messageBox"), messageBox = vxJS.element.register(mBox);

	var f = vxJS.widget.xhrForm(document.forms[0]);

	var parseServerCheck = function(r) {
		var txt = r.message || "Benutzername oder Passwort falsch!",  height;

		vxJS.dom.addClassName(mBox, "messageBoxError");

		mBox.firstChild.nodeValue = txt;

		height = vxJS.dom.getElementSize(mBox).y;

		mBox.style.top = -height + "px";
		messageBox.
			clearFxQueue().
			fx("moveRelative", { to: new Coord(0, height), duration: 0.5, transition: "decel" }).
			pause(3).
			fx("moveRelative", { to: new Coord(0, -height), duration: 0.5, transition: "accel" });
	};

	f.addSubmit(f.element.elements["submit_login"]);

	vxJS.event.addListener(f, "check", parseServerCheck);
	vxJS.event.addListener(messageBox, "finishFxQueue", function() { this.element.style.top = "-100em"; });
});
