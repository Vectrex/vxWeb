/*jslint browser: true, eqeq: true, plusplus: true, sloppy: true, vars: true, white: true */

if(!this.vxWeb) {
	this.vxWeb = {};
}

this.vxWeb.doArticles = function() {

	"use strict";

	var	route = vxWeb.routes.articles,
		articleXhrForm, id,
		sortXhr = vxJS.xhr( { uri: route, command: "sortFiles" }),
		sorTable,
		tabs = vxJS.widget.simpleTabs(null, { setHash: true, shortenLabelsTo: 24 })[0],
		mBox = document.getElementById("messageBox"), timeoutId;

	articleXhrForm = vxJS.widget.xhrForm(document.forms[0], { uri: route, command: "checkForm" });
	articleXhrForm.addSubmit(articleXhrForm.element.elements["submit_article"]);

	var initSorTable = function() {
		var st = "table".setProp("class", "linkedFilesTable").create([
				"thead".create(
					"tr".create(["", "Typ", "Dateiname"].domWrapWithTag("th")
				)),
				"tbody".create(
					"tr".create(["", "", ""].domWrapWithTag("td")
				))
			]),
			dragFrom;

		sorTable = vxJS.widget.sorTable(st, { columnFormat: ["manual", "no_sort", "no_sort"] });
		
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
				if(vxWeb.parameters) {
					vxWeb.parameters.articlesId = id;
				}

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

	if(vxWeb.parameters.articlesId) {
		initSorTable();
	}
	else {
		tabs.disable();
	}
	
	var handleSortResponse = function() {
		var confirm;

		if(this.response && this.response.files) {
			sorTable.removeAllRows();
			this.response.files.forEach(function(row) {
				sorTable.insertRow(
					"tr".create([
						"td".setProp("id", "__id__" + row.id).create(),
						"td".create(row.isThumb ? "img".setProp( { src: row.type, className: "thumb" } ).create() : row.type),
						"td".create(row.filename)
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
		if(typeof CKEDITOR != "undefined" && CKEDITOR.instances['Content']) {
			this.element.elements['Content'].value = CKEDITOR.instances['Content'].getData();
		}
	});

	vxJS.event.addListener(articleXhrForm, "check", parseServerCheck);
	vxJS.event.addListener(sortXhr, "complete", handleSortResponse);
	vxJS.event.addListener(document.getElementById("showSort"), "click", function() {
		if(vxWeb.parameters && vxWeb.parameters.articlesId) {
			sortXhr.use( { command: "getFiles" }, { articlesId: vxWeb.parameters.articlesId } ).submit();
		}
	});
};