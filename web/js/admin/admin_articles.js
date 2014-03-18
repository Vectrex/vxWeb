vxJS.event.addDomReadyListener(function() {

	"use strict";

	var	route = vxWeb.routes.articles,
		articleXhrForm, id,
		filesXhrForm, st,
		sortXhr = vxJS.xhr( { uri: route, command: "sortFiles" }),
		tabs = vxJS.widget.simpleTabs(null, { setHash: true, shortenLabelsTo: 24 })[0],
		mBox = document.getElementById("messageBox"), messageBox = vxJS.element.register(mBox);

	articleXhrForm = vxJS.widget.xhrForm(document.forms[0], { uri: route, command: "checkForm" });
	articleXhrForm.addSubmit(articleXhrForm.element.elements["submit_article"]);

	var initFilesFunc = function() {
		var form = document.forms[1];

		form.action = route;

		filesXhrForm = vxJS.widget.xhrForm(form, { uri: route, command: "handleFiles" });
		filesXhrForm.addSubmit(filesXhrForm.element.elements["submit_file"]);
		filesXhrForm.enableIframeUpload();
		filesXhrForm.enableImmediateSubmit();
		vxJS.event.addListener(filesXhrForm, "ifuResponse", handleUploadResponse);

		st = vxJS.widget.sorTable(vxJS.dom.getElementsByClassName("imageTable")[0], { columnFormat: ["manual", "no_sort", "no_sort", "no_sort"] });

		vxJS.event.addListener(st, "dragStop", function() {
			sortXhr.use(null, { sortOrder: this.getCurrentOrder(), id: filesXhrForm.element.elements["id"].value });
			sortXhr.submit();
			this.initSort();
		});

		tabs.enable();
	};

	var parseServerCheck = function(r) {
		var txt, height;

		if(r.success) {
			txt = r.message || "Daten erfolgreich übernommen!";

			vxJS.dom.removeClassName(mBox, "messageBoxError");
			vxJS.dom.addClassName(mBox, "messageBoxSuccess");

			if(r.markup) {
				vxJS.dom.deleteChildNodes(tabs.tabs[1].page);
				tabs.tabs[1].page.appendChild(vxJS.dom.parse(r.markup));
				initFilesFunc();
			}

			if(r.id) {
				id = r.id;
				articleXhrForm.element.elements["submit_article"].firstChild.nodeValue = "Änderungen übernehmen";
			}
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

	var handleUploadResponse = function(r) {
		var l, fi;

		if(!r.success) {
			parseServerCheck(r);
		}

		while((l = st.element.rows.length)) {
			st.removeRow(st.element.rows[l - 1]);
		}

		if(r.files && r.files.length) {
			r.files.forEach(function(fileData){
				st.insertRow("tr".create([
					"td".setProp("class", "dndColumn").create(),
					"td".create(fileData.isThumb ? "img".setProp({ src: fileData.type, className: "thumb"}).create() : fileData.type),
					"td".create(fileData.filename),
					"td".create(fileData.metadata.Description),
					"td".create("input".setProp( { type: "checkbox", name: "delete_file[" + fileData.id + "]", value: 1 } ).create())
				]));
			});
		}

		// IE7/8 doesn't allow to set value to an empty string

		fi = this.element.elements["upload_file"];
		fi.parentNode.replaceChild("input".setProp({name: "upload_file", type: "file" }).create(), fi);
		this.element.elements["file_description"].value = "";
	};

	if(document.forms[1]) {
		initFilesFunc();
	}
	else {
		tabs.disable();
	}

	vxJS.event.addListener(articleXhrForm, "beforeSubmit", function() {
		if(id) {
			this.setPayload( { id: id });
		}
		if(typeof CKEDITOR != "undefined" && CKEDITOR.instances['Content']) {
			this.element.elements['Content'].value = CKEDITOR.instances['Content'].getData();
		}
	});

	vxJS.event.addListener(articleXhrForm, "check", parseServerCheck);
});