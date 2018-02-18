//@todo replace vxJS.widget.confirm() used for file sorting

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
		tabs = vxJS.widget.simpleTabs(null, { setHash: true, shortenLabelsTo: 32 })[0],
		sortButton = document.getElementById("sortFiles"),
        mBox = vxWeb.messageToast();

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
		var st = document.getElementById("linkedFilesTable"), dragFrom;

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

        if(r.success) {
            mBox.show(r.message || "Daten erfolgreich übernommen!", "toast-success");
            if(r.id) {
                id = r.id;
                if(vxWeb.parameters) {
                    vxWeb.parameters.articlesId = id;
                }
                tabs.getTabByNdx(1).enable();
                tabs.getTabByNdx(2).enable();
                articleXhrForm.element.elements["submit_article"].firstChild.nodeValue = "Änderungen übernehmen";
            }
        }
        else {
            mBox.show(r.message || "Fehler bei Übernahme der Daten!", "toast-error");
        }

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
		}
	};

	vxJS.event.addListener(tabs, "afterTabClick", function() {
	   if(this.getLast() && this.getLast().id === "sort_article_files" && vxWeb.parameters.articlesId) {
           sortXhr.use( { command: "getFiles" }, { articlesId: vxWeb.parameters.articlesId } ).submit();
       }
    });

	vxJS.event.addListener(articleXhrForm, "beforeSubmit", function() {
		if(id) {
			this.setPayload( { id: id });
		}
		if(CKEDITOR && CKEDITOR.instances.content) {
			this.element.elements["content"].value = CKEDITOR.instances.content.getData();
		}
	});

	vxJS.event.addListener(articleXhrForm, "check", parseServerCheck);
	vxJS.event.addListener(sortXhr, "complete", handleSortResponse);
	
	if(!vxWeb.parameters.articlesId) {
		tabs.getTabByNdx(1).disable();
        tabs.getTabByNdx(2).disable();
	}
	initSorTable();
};