if(!this.vxWeb) {
	this.vxWeb = {};
}

this.vxWeb.doFiles = function() {

	"use strict";

	var uri = function() {
			var path = vxWeb.routes.files, search = window.location.search;

			// add query parameter (needed for CKEditor integration)

			if(search) {
				path = path + (path.indexOf("?") !== -1 ? "&" : "?") + search.substring(1);
			}
			return path;
		}(),
		folderId,
		directoryBar = document.getElementById("directoryBar"), breadCrumbs = [],
		folderInput = "input".setProp([["type", "hidden"], ["name", "folder"]]).create(),
		throbberElement = function() {
			return "div".setProp("class", "vxJS_xhrThrobberFileOperation").create();
		}(),
		xhr = vxJS.xhr(
			{ uri: uri, echo: true, timeout: 10000 },
			{ columns: ["name", "size", "mime", "mTime"] },
			{ node: throbberElement }
		),
		form, xhrForm,
		formInitValues = {}, filesTableListeners = [],
		dnd, dndPossible = !!vxJS.dnd,
		confirm = vxJS.widget.confirm( { overlay: true, className: "confirmForm" } ),
		filesTable = document.getElementById("filesList").getElementsByTagName("table")[0],
		confirmPayload = "div".setProp("class", "confirmPayload").create(),
		lsValue, lsKey = window.location.href + "__sort__",
		folderRex = /(^| )folderRow( |$)/,
		icons = {
			edit:		"button".setProp( { type: "button", href: "", className: "edit", title: "Bearbeiten" } ).create(),
			del:		"button".setProp( { type: "button", href: "", className: "del", title: "Löschen" } ).create(),
			move:		"button".setProp( { type: "button", href: "", className: "move", title: "Verschieben" } ).create(),
			delFolder:	"button".setProp( { type: "button", href: "", className: "delFolder", title: "Ordner leeren und löschen" } ).create(),
			forward:	"button".setProp( { type: "button", href: "", className: "forward", title: "Übernehmen" } ).create(),
			rename:		"button".setProp( { type: "button", href: "", className: "rename", title: "Umbenennen" } ).create(),
			locked:		"span".setProp( { href: "", className: "locked", title: "Gesperrt" } ).create()
		},

		simpleFileRowSort = function(a, b) {
			var s = this.asc ? 1 : -1;
			if(a.value === "" || a.value === b.value) { return 0; }
			return a.value < b.value ? -s : s;
		},

		fileSizeSort = function(a, b) {
			var s = this.asc ? 1 : -1, intA, intB;
			if(a.value === "") { return 0; }
			intA = parseInt(a.value.replace(/\./g, ""), 10);
			intB = parseInt(b.value.replace(/\./g, ""), 10);
			if(intA === intB) { return 0; }
			return intA < intB ? -s : s;
		},

		fancyFileRowSort = function(a, b) {
			var s = this.asc ? 1 : -1, fra = folderRex.test(a.element.className), frb = folderRex.test(b.element.className);
			if((fra && frb) || (!fra && !frb)) {
				if(a.value === b.value) { return 0; }
				return a.value < b.value ? -s : s;
			}
			if(fra) { return -1; }
			if(frb) { return 1; }
		},

		t = vxJS.widget.sorTable(
			filesTable, {
			columnFormat: [
				fancyFileRowSort,
				fileSizeSort,
				simpleFileRowSort,
				simpleFileRowSort,
				"no_sort"
			]
		}),

		colNum = filesTable.tHead.rows[0].cells.length,

		addFileButton = (function() {
			var e = "button".setProp({ type: "button", className: "addFileButton" }).create("Datei hinzufügen");
			vxJS.event.addListener(e, "click", function(e) {
				if(!form) {
					xhr.use({ command: "requestAddForm" }).submit();
				}
				else {
					vxJS.dom.deleteChildNodes(confirmPayload);
					confirmPayload.appendChild(form);
					confirm.show();
				}
				vxJS.event.cancelBubbling(e);
			});
			return e;
		}()),

		addFolderButton = (function() {
			var elem = "button".setProp({ type: "button", className: "addFolderButton" }).create("Neues Verzeichnis anlegen");
			vxJS.event.addListener(elem, "click", function(e) {
				this.style.display = "none";
				addFolderInput.style.display = "";
				addFolderInput.value = "";
				addFolderInput.focus();
				vxJS.event.cancelBubbling(e);
			});
			return elem;
		}()),

		addFolderInput = (function() {
			var elem = "input".setProp("class", "ml addFolderInput").create();
			elem.style.display = "none";
			vxJS.event.addListener(elem, "blur", function(e) {
				this.style.display = "none";
				addFolderButton.style.display = "";
			});
			vxJS.event.addListener(elem, "keydown", function(e) {
				if(e.keyCode === 27 || e.keyCode === 13) {
					this.style.display = "none";
					addFolderButton.style.display = "";
				}
				if(e.keyCode === 13 && this.value.trim()) {
					xhr.use({command: "addFolder"}, { folder: folderId, folderName: this.value }).submit();
				}
			});
			return elem;
		}()),

		folderTree = (function() {
			var t = vxJS.widget.tree();
			vxJS.event.addListener(t, "labelClick", function(b) {
				if(!b.branch.current) {
					if(window.confirm("Datei nach " + b.branch.path + " verschieben?")) {
						xhr.use( { command: "moveFile" }, { destination: b.branch.key } ).submit();
					}
				}
			});
			return t;
		}()),
		folderTreeContainer = (function() {
			var b = "button".create("Abbrechen");
			vxJS.event.addListener(b, "click", confirm.hide);
			return "div".setProp("id", "folderTreeContainer").create([folderTree.element, "div".setProp("class", "formBase").create(b)]);
		}());

	var prepareAddForm = function() {
		var i = 0, e;
		while((e = form.elements[i++])) {
			formInitValues[e.name] = e.value;
		}
		form.appendChild(folderInput);

		xhrForm = vxJS.widget.xhrForm(form, { command: "checkUpload", uri: uri } );
		xhrForm.addSubmit(form.elements["submit_add"]);
		xhrForm.addMessageBox(vxJS.dom.getElementsByClassName("errorContainer", form)[0], "general");
		xhrForm.enableIframeUpload();

		vxJS.event.addListener(
			form.elements["submit_cancel"],
			"click",
			function(e) {
				confirm.hide();
				vxJS.event.preventDefault(e);
			}
		);

		vxJS.event.addListener(
			xhrForm,
			"ifuResponse",
			function(response) {
				var p;

				if(response.success) {
					confirm.hide();
					for(p in formInitValues) {
						if(formInitValues.hasOwnProperty(p)) {
							form.elements[p].value = formInitValues[p];
						}
					}
					xhr.use({command: "getFiles"}, { folder: folderId }).submit();
				}
			}
		);
	};

	var focusForm = function() {
		var forms = this.element.getElementsByTagName("form");
		if(forms[0] && forms[0].elements[0]) {
			forms[0].elements[0].focus();
		}
	};

	var buildDirectoryBar = function(pathSegs) {
		var i, l, p, b, add, elem;

		for(i = 0, l = pathSegs.length; i < l; ++i) {

			p = pathSegs[i];
			b = breadCrumbs[i];

			if(b && b.name == p.name) {
				continue;
			}
			else {
				while((b = breadCrumbs[i])) {
					vxJS.event.removeListener(b.listener);
					breadCrumbs.splice(i, 1);
					b.element.parentNode.removeChild(b.element);
				}
				break;
			}
		}

		for(; i < l; ++i) {

			p = pathSegs[i];
			elem = "a".setProp("href", "").create(p.name);

			add =  {
				name:		p.name,
				id:			p.id,
				element:	elem,
				listener:	vxJS.event.addListener(
					elem,
					"click",
					(function(id) {
						return function(e) {
							xhr.use({ command: "getFiles" }, { folder: id }).submit();
							vxJS.event.preventDefault(e);
						};
					}(p.id))
				)
			};
			breadCrumbs.push(add);

			directoryBar.appendChild(add.element);
		}

		if((p = vxJS.dom.getElementsByClassName("currentFolder", directoryBar)[0])) {
			vxJS.dom.removeClassName(p, "currentFolder");
		}
		vxJS.dom.addClassName(breadCrumbs[l - 1].element, "currentFolder");
	};

	var appendFolderRow = function(folderData) {
		var a = "a".setProp("href", window.location + "/folder/" + folderData.id).create(folderData.name),
			td = "td".create(folderData.locked ? icons.locked.cloneNode(true) : icons.delFolder.cloneNode(true)),
			cells = ["td".create(a)], i = colNum - 2;

		while(i--) {
			cells.push("td".create());
		}
		cells.push(td);

		t.insertRow("tr".setProp("class", "folderRow").create(cells));

		filesTableListeners.push(vxJS.event.addListener(a, "click", function(e) {
			xhr.use({command: "getFiles"}, { folder: folderData.id }).submit();
			vxJS.event.preventDefault(e);
		}));

		filesTableListeners.push(vxJS.event.addListener(td, "click", (function(data) {
			return function() {
				if(this.nodeName.toLowerCase() !== "button") {
					return;
				}
				if(window.confirm("Ordner und Inhalt wirklich löschen?")) {
					xhr.use({ command: "delFolder" }, { id: data.id }).submit();
				}
			};
		}(folderData))));
	};

	var appendFileRow = function(fileData) {
		var td = "td".create(fileData.locked ?
				icons.locked.cloneNode(true) :
				[
				 	icons.edit.cloneNode(true),
				 	icons.move.cloneNode(true),
				 	icons.del.cloneNode(true),
				 	fileData.forward ? icons.forward.cloneNode(true) : null
				]
			), cells = [], i, l, d;

		for(i = 0, l = colNum - 1; i < l; ++i) {
			d = fileData.columns[i];
			if(typeof d === "undefined") {
				cells.push("td".create());
			}
			else {
				cells.push("td".create(typeof d === "object" ? vxJS.dom.parse(d) : d));
			}
			if(!i) {
				cells[0].appendChild(icons.rename.cloneNode(true))
			}
		}

		cells.push(td);
		fileData.tr = "tr".setProp("class", "fileRow").create(cells);
		t.insertRow(fileData.tr);

		filesTableListeners.push(vxJS.event.addListener(fileData.tr, "click", (function(data) {
			return function() {
				var storedNodes, cell;

				if(this.nodeName.toLowerCase() !== "button") {
					return;
				}

				switch(this.className) {
					case "del":
						if(window.confirm("Datei wirklich löschen?")) {
							xhr.use({ command: "delFile" }, { id: data.id }).submit();
						}
						break;

					case "move":
						xhr.use({ command: "getFolderTree" }, { id: data.id }).submit();
						break;

					case "edit":
						xhr.use({ command: "requestEditForm" }, { id: data.id }).submit();
						break;

					case "forward":
						window.opener.CKEDITOR.tools.callFunction(data.forward.ckEditorFuncNum, data.forward.filename);
						window.close();
						break;

					case "rename":
						storedNodes = document.createDocumentFragment();
						cell = fileData.tr.cells[0];
						while(cell.firstChild) {
							storedNodes.appendChild(cell.firstChild);
						}
						cell.appendChild(
							function() {
								var elem = "input".setProp({ className: "renameInput", value: fileData.filename }).create(), keydownListenerId, blurListenerId;

								// remove input element

								var removeInput = function() {
									vxJS.event.removeListener(keydownListenerId);
									vxJS.event.removeListener(blurListenerId);
									vxJS.dom.removeClassName(elem, "error");
									elem.disabled = false;
									cell.removeChild(elem);
								};

								var keydownListener = function(e) {
									if(e.type == "blur" || e.keyCode == 13) {

										if(elem.value !== fileData.filename) {

											// xhr submit

											elem.disabled = true;
											vxJS.xhr(
												{
													uri:		uri,
													command:	"renameFile"
												},
												{
													id:			fileData.id,
													filename:	elem.value
												},
												{},
												{
													complete: function() {
														var r = this.response;

														if(r.error !== false) {
															elem.disabled = false;
															vxJS.dom.addClassName(elem, "error");
														}
														else {
															fileData.filename = r.filename;

															// fill cell with new content

															removeInput();
															cell.appendChild(vxJS.dom.parse(r.elements));
															cell.appendChild(icons.rename.cloneNode(true));
															t.reSort();
														}
													},

													timeout: function() {
														elem.disabled = false;
														vxJS.dom.addClassName(elem, "error");
													}
												}
											).submit();
										}

										else {

											// restore node(s)

											removeInput();
											cell.appendChild(storedNodes);
										}
									}

									else if(e.keyCode == 27) {

										// restore node(s)

										removeInput();
										cell.appendChild(storedNodes);
									}
								};

								keydownListenerId = vxJS.event.addListener(elem, "keydown", keydownListener);
								blurListenerId = vxJS.event.addListener(elem, "blur", keydownListener);

								window.setTimeout(function() { vxJS.selection.set(elem); elem.focus(); }, 0);
								return elem;
							}()
						);
						break;
				}
			};
		}(fileData))));
	};

	var buildFilesTable = function(folders, files) {
		var l = filesTableListeners.length;

		while(l--) {
			vxJS.event.removeListener(filesTableListeners[l]);
		}

		while((l = t.element.rows.length)) {
			t.removeRow(t.element.rows[l - 1]);
		}

		if(!files.length && !folders.length) {
			t.insertRow(
				"tr".setProp("class", "fileRow").create(["td".create("em".create("Dieser Ordner ist leer.")), "td".create(), "td".create(), "td".create(), "td".create(), "td".create()])
			);
		}
		else {
			folders.forEach(appendFolderRow);
			files.forEach(appendFileRow);
			t.reSort();
		}
	};

	var handleXhrResponse = function() {
		var r = this.response, e = r.echo, f, xForm, i = 0, b, tree;

		// response is only evaluated when echo property is set

		if(e) {
			switch(e.httpRequest) {

				case "delFolder":
					if(!r.response.error) {
						buildFilesTable(r.response.folders, r.response.files);
						while((b = breadCrumbs[i])) {
							if(b.id === e.id) {
								while((b = breadCrumbs[i])) {
									vxJS.event.removeListener(b.listener);
									b.element.parentNode.removeChild(b.element);
									breadCrumbs.splice(i, 1);
								}
							}
							++i;
						}
					}
					break;

				case "moveFile":
					confirm.hide();
				case "getFiles":
				case "addFolder":
				case "delFile":
					if(!r.response.error) {
						if(r.response.pathSegments) {
							buildDirectoryBar(r.response.pathSegments);
						}
						buildFilesTable(r.response.folders, r.response.files);

						if(e.folder) {
							folderId = e.folder;
							folderInput.value = e.folder;
						}
					}
					break;

				case "requestAddForm":
					vxJS.dom.deleteChildNodes(confirmPayload);
					confirmPayload.appendChild(vxJS.dom.parse(r.response));
					form = confirmPayload.getElementsByTagName("form")[0];

					prepareAddForm();

					confirm.show();
					form.elements[0].focus();

					if(dnd) {
						dnd.addDraggable(confirm.element);
					}
					break;

				case "requestEditForm":
					vxJS.dom.deleteChildNodes(confirmPayload);
					confirmPayload.appendChild(vxJS.dom.parse(r.response));

					// prepare edit form
					f = confirmPayload.getElementsByTagName("form")[0];

					xForm = vxJS.widget.xhrForm(f, { command: "checkEditForm", uri: uri });
					xForm.addSubmit(f.elements["submit_edit"]);
					xForm.addMessageBox(vxJS.dom.getElementsByClassName("errorContainer", f)[0], "general");
					xForm.setPayload( { id: e.id } );

					vxJS.event.addListener(
						xForm,
						"check",
						function() {
							var r = this.getLastXhrResponse();

							if(r.elements) {
								// possible error handling
							}
							else {
								buildFilesTable(r.folders, r.files);
								f = null;
								xForm = null;
								confirm.hide();
							}
						}
					);

					vxJS.event.addListener(
						f.elements["submit_cancel"],
						"click",
						function(e) {
							f = null;
							xForm = null;
							confirm.hide();
							vxJS.event.preventDefault(e);
						}
					);

					confirm.show();
					if(dnd) {
						dnd.addDraggable(confirm.element);
					}
					break;

				case "getFolderTree":
					tree = folderTree.getRootTree();
					tree.truncate();
					tree.addBranches(r.response.branches);

					vxJS.dom.deleteChildNodes(confirmPayload);
					confirmPayload.appendChild(folderTreeContainer);
					confirm.show();
					if(dnd) {
						dnd.addDraggable(confirm.element);
					}
					break;
			}
		}
	};

	// everything prepared, get things going

	vxJS.event.addListener(xhr,		"timeout", function() { window.alert('Dateioperation dauert zu lange. Bitte erneut versuchen.'); });
	vxJS.event.addListener(xhr,		"complete", handleXhrResponse);
	vxJS.event.addListener(confirm,	"focusLost", focusForm);
	vxJS.event.addListener(
		t,
		"finishSort",
		function() {
			var c = this.getActiveColumn();
			vxJS.widget.shared.shadeTableRows({ element: this.element });
			if(window.localStorage) {
				window.localStorage.setItem(lsKey, JSON.stringify( { ndx: c.ndx, asc: c.asc } ));
			}
		}
	);

	if(window.localStorage && (lsValue = window.localStorage.getItem(lsKey))) {
		lsValue = JSON.parse(lsValue);
		t.sortBy(lsValue.ndx, lsValue.asc ? "asc" : "desc");
	}
	else {
		t.sortBy(0, "asc");
	}

	filesTable.tHead.appendChild("tr".setProp("class", "addFolderRow").create("td".setProp("colSpan", 6).create([addFolderButton, addFolderInput, addFileButton, throbberElement])));

	if(dndPossible) {
		confirm.element.appendChild("div".setProp("class", "vxJS_dragBar").create());
		dnd = vxJS.dnd.create();
	}
	confirm.element.appendChild(confirmPayload);

	xhr.use({command: "getFiles"}).submit();
};