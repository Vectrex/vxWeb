vxJS.event.addDomReadyListener(function() {

	"use strict";

	var	route = vxWeb.routes.articles,
		articleXhrForm, id,
		filesXhrForm, st,
		sortXhr = vxJS.xhr( { uri: route, command: "sortFiles" }),
		tabs = vxJS.widget.simpleTabs(null, { setHash: true, shortenLabelsTo: 24 })[0],
		mBox = document.getElementById("messageBox"), timeoutId;

	articleXhrForm = vxJS.widget.xhrForm(document.forms[0], { uri: route, command: "checkForm" });
	articleXhrForm.addSubmit(articleXhrForm.element.elements["submit_article"]);

	var initFilesForm = function() {
		var form = document.forms[1], dragFrom;

		form.action = route;

		filesXhrForm = vxJS.widget.xhrForm(form, { uri: route, command: "handleFiles" });
		filesXhrForm.addSubmit(filesXhrForm.element.elements["submit_file"]);
		filesXhrForm.enableIframeUpload();
		filesXhrForm.enableImmediateSubmit();
		vxJS.event.addListener(filesXhrForm, "ifuResponse", handleUploadResponse);

		st = vxJS.widget.sorTable(vxJS.dom.getElementsByClassName("imageTable")[0], { columnFormat: ["manual", "no_sort", "no_sort", "no_sort"] });

		vxJS.event.addListener(st, "dragStart", function() {
			dragFrom = this.getDraggedRow().sectionRowIndex;
		});

		vxJS.event.addListener(st, "dragStop", function() {
			var draggedRow = this.getDraggedRow(), to = draggedRow.sectionRowIndex;

			if(dragFrom !== to) {

				sortXhr.use(null, {
					to:		to,
					file:	parseInt(draggedRow.querySelector("input[type='checkbox']").name.match(/\[(\d+)\]$/)[1], 10),
					id:		parseInt(filesXhrForm.element.elements["id"].value, 10)
				}).submit();
				
				this.initSort();

			}
		});

		tabs.enable();
	};

	var parseServerCheck = function(r) {
		var txt;

		if(r.success) {
			txt = r.message || "Daten erfolgreich übernommen!";

			vxJS.dom.removeClassName(mBox, "messageBoxError");
			vxJS.dom.addClassName(mBox, "messageBoxSuccess");

			if(r.markup) {
				vxJS.dom.deleteChildNodes(tabs.tabs[1].page);
				tabs.tabs[1].page.appendChild(vxJS.dom.parse(r.markup));
				initFilesForm();
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

		vxJS.dom.removeClassName(mBox, "fadeOutUp");
		vxJS.dom.addClassName(mBox, "fadeInDown");

		if(timeoutId) {
			window.clearTimeout(timeoutId);
		}
		timeoutId = window.setTimeout(function() {
			vxJS.dom.removeClassName(mBox, "fadeInDown");
			vxJS.dom.addClassName(mBox, "fadeOutUp");
		}, 3000);
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
		initFilesForm();
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