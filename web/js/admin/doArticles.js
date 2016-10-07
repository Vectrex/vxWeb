/*jslint browser: true, eqeq: true, plusplus: true, sloppy: true, vars: true, white: true */

if(!this.vxWeb) {
	this.vxWeb = {};
}

this.vxWeb.doArticles = function() {

	"use strict";

	var	route = vxWeb.routes.articles,

		fileManager = vxWeb.fileManager({
			directoryBar:		document.getElementById("directoryBar"),
			filesList:			document.getElementById("filesList"),
			uploadMaxFilesize:	vxWeb.serverConfig.uploadMaxFilesize,
			maxUploadTime:		vxWeb.serverConfig.maxUploadTime
		}),

		articleXhrForm, id,
		sortXhr = vxJS.xhr( { uri: route, command: "sortFiles" }),
		sorTable,
		confirm,
		tabs = vxJS.widget.simpleTabs(null, { setHash: true, shortenLabelsTo: 24 })[0],
		mBox = document.getElementById("messageBox"), timeoutId,
		sortButton = function() {
			var b = "button".setProp({ type: "button", className: "withIcon" }).create("Verlinkte Dateien sortieren");

			b.setAttribute("data-icon", "\ue035");

			vxJS.event.addListener(b, "click", function() {
				if(vxWeb.parameters && vxWeb.parameters.articlesId) {
					sortXhr.use( { command: "getFiles" }, { articlesId: vxWeb.parameters.articlesId } ).submit();
				}
			});
			return b;
		}(), lastButton;

	articleXhrForm = vxJS.widget.xhrForm(document.forms[0], { uri: route, command: "checkForm" });
	articleXhrForm.addSubmit(articleXhrForm.element.elements["submit_article"]);

	var handleFolderClick = function(e) {
		var matches;
		
		if(this.href && (matches = this.href.match(/@folder=(\d+)$/))) {
			vxJS.event.preventDefault(e);
			confirm.hide();
			if(matches[1]) {
				fileManager.gotoFolder(matches[1]);
			}
		}
	};

	var initSorTable = function() {
		var st = "table".setProp("class", "linkedFilesTable").create([
				"thead".create(
					"tr".create(["", "Typ", "Dateiname", "Ordner"].domWrapWithTag("th")
				)),
				"tbody".create(
					"tr".create(["", "", "", ""].domWrapWithTag("td")
				))
			]),
			dragFrom;

		sorTable = vxJS.widget.sorTable(st, { columnFormat: ["manual", "no_sort", "no_sort", "no_sort"] });
		
		vxJS.event.addListener(sorTable, "dragStart", function() {
			dragFrom = this.getDraggedRow().sectionRowIndex;
		});

		vxJS.event.addListener(sorTable, "dragStop", function() {
			var draggedRow = this.getDraggedRow(), to = draggedRow.sectionRowIndex;

			if(dragFrom !== to) {

				sortXhr.use(
					{
						command: "sortFiles"
					}, {
						to:		to,
						file:	parseInt(draggedRow.firstChild.id.match(/(\d+)$/)[1], 10),
						id:		vxWeb.parameters.articlesId
					}
				).submit();
				
				this.initSort();

			}
		});

		vxJS.event.addListener(st, "click", handleFolderClick);
	};

	var parseServerCheck = function(r) {
		var txt;

		if(r.success) {
			txt = r.message || "Daten erfolgreich übernommen!";

			vxJS.dom.removeClassName(mBox, "error");
			vxJS.dom.addClassName(mBox, "success");

			if(r.id) {
				id = r.id;
				if(vxWeb.parameters) {
					vxWeb.parameters.articlesId = id;
				}
				tabs.getTabByNdx(1).enable();
				articleXhrForm.element.elements["submit_article"].firstChild.nodeValue = "Änderungen übernehmen";
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
		}, 3000);
	};
	
	var handleSortResponse = function() {
		if(this.response && this.response.files) {
			sorTable.removeAllRows();
			this.response.files.forEach(function(row) {
				sorTable.insertRow(
					"tr".create([
						"td".setProp("id", "__id__" + row.id).create(),
						"td".create(row.isThumb ? "img".setProp( { src: row.type, className: "thumb" } ).create() : row.type),
						"td".create(row.filename),
						"td".create("a".setProp("href", window.location + "@folder=" + row.folderId).create(row.path))
					])
				);
			});
			confirm = vxJS.widget.confirm( { className: "confirmForm", content: [ { fragment: sorTable.element.parentNode }], buttons: [{label: "Schließen", key: "close"}]}); 
			confirm.show();
		}
	};

	vxJS.event.addListener(articleXhrForm, "beforeSubmit", function() {
		if(id) {
			this.setPayload( { id: id });
		}
		if(CKEDITOR && CKEDITOR.instances.content) {
			this.element.elements['content'].value = CKEDITOR.instances.content.getData();
		}
	});

	vxJS.event.addListener(articleXhrForm, "check", parseServerCheck);
	vxJS.event.addListener(sortXhr, "complete", handleSortResponse);
	
	if(!vxWeb.parameters.articlesId) {
		tabs.getTabByNdx(1).disable();
	}
	initSorTable();

	lastButton = vxJS.collectionToArray(document.querySelectorAll("tr.fileFunctions button")).pop();
	lastButton.parentNode.insertBefore(sortButton, lastButton.nextSibling);
};