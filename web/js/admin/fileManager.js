/*jslint browser: true, ass: true, continue: true, eqeq: true, plusplus: true, todo: true, vars: true, white: true */

if(!this.vxWeb) {
	this.vxWeb = {};
}
if(!this.vxWeb.parameters) {
	this.vxWeb.parameters = {};
}

this.vxWeb.fileManager = function(config) {

	"use strict";

	var filesTable = config.filesList.getElementsByTagName("table")[0],

		uri = (function() {
			var path = vxWeb.routes.files, search = window.location.search;

			// add query parameter (needed for CKEditor integration)

			if(search) {
				path = path + (path.indexOf("?") !== -1 ? "&" : "?") + search.substring(1);
			}
			return path;
		}()),
		
		bytesToSize = function(bytes) {
		   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		   return Math.round(bytes / Math.pow(1024, i), 2) + ['Bytes', 'KB', 'MB', 'GB', 'TB'][i];
		},

		breadCrumbs = [],
		folderInput		= "input".setProp([["type", "hidden"], ["name", "folder"]]).create(),
		articlesIdInput	= "input".setProp([["type", "hidden"], ["name", "articlesId"]]).create(),
		xhr = vxJS.xhr( { uri: uri, echo: true, timeout: 10000 }),
		form, xhrForm,
		formInitValues = {}, filesTableListeners = [],

		lsValue, lsKey = window.location.origin + "/admin/files" + "__sort__",

		icons = (function() {
			var elements = {};
			[
			 	{ key: "edit",		title: "Bearbeiten",	icon: "\ue002", className: "btn-primary" },
			 	{ key: "move",		title: "Verschieben",	icon: "\ue004", className: "btn-primary" },
			 	{ key: "del",		title: "Löschen",		icon: "\ue011", className: "btn-primary tooltip-left" },
			 	{ key: "forward",	title: "Übernehmen",	icon: "\ue02a", className: "btn-primary tooltip-left" },
			 	{ key: "rename",	title: "Umbenennen",	icon: "\ue001", className: "display-only-on-hover ml-2" },
			 	{ key: "delFolder",	title: "Ordner leeren und löschen", icon: "\ue008", className: "btn-primary" },
			 	{ key: "locked",	title: "Gesperrt", icon: "\ue00f", element: "span" }
			].forEach(function(props) {
				var cN = "btn webfont-icon-only tooltip mr-1 " + props.key + (props.className ? (" " + props.className) : ""),
				e = (props.element || "button").setProp("class", cN).create(props.icon);
				e.setAttribute("data-tooltip", props.title);
				elements[props.key] = e;
			});

			return elements;
		}()),

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
            var folderRex = /(^| )folder-row( |$)/;
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

		addFileButton = document.getElementById("addFile"),
		addFolderButton = document.getElementById("addFolder"),
		addFolderInput = document.getElementById("addFolderInput"),
        mBox = vxWeb.messageToast();

		//@todo: xhr request assumes that file id is still in request parameters from previous request
    /**
     * wrapped modal used for file operation dialogs
     * @type {{element, setHeader, setContent, setSize, show, hide}}
     */
    var fileModal = (function() {

        var d = "div".create(), elem, header, content;

        d.innerHTML = '<div class="modal modal-sm" id="modal"><a href="#" class="modal-overlay" aria-label="Close"></a><div class="modal-container"><div class="modal-header"><strong></strong><button class="btn btn-clear float-right" aria-label="Close"></button></div><div class="modal-body"><div class="content"></div></div></div></div>';

        elem = d.firstElementChild;
        header = elem.querySelector(".modal-header");
        content = elem.querySelector(".content");

        var hide = function() {
            elem.classList.remove("active");
        };

        var show = function() {
            elem.classList.add("active");
        };

        header = elem.querySelector(".modal-header > strong");
        document.body.appendChild(elem);

        vxJS.event.addListener(elem.querySelector(".modal-header button.btn-clear"), "click", function(e) { hide(); vxJS.event.cancelBubbling(e); });
        vxJS.event.addListener(elem.querySelector("a"), "click", function(e) { hide(); vxJS.event.preventDefault(e); } );

        return {
            element: elem,

            setHeader: function(markup) {
                header.innerHTML = markup;
                return this;
            },

            setContent: function(markup) {
                if(markup instanceof Node) {
                    content.innerHTML = "";
                    content.appendChild(markup);
                }
                else {
                    content.innerHTML = markup;
                }
                return this;
            },

            setSize: function(size) {

                var l = elem.classList;

                switch(size) {
                    case "sm":
                        l.remove("modal-lg");
                        l.add("modal-sm");
                        break;
                    case "lg":
                        l.remove("modal-sm");
                        l.add("modal-lg");
                        break;
                    default:
                        l.remove("modal-lg");
                        l.remove("modal-sm");
                }

                return this;
            },

            show: function() { show(); return this; },
            hide: function() { hide(); return this; }
        };

    }());

	/**
	 * container for a simple CSS based tree widget
	 * @type {{expandToCurrent, element}}
	 */
	var	treeContainer = (function() {
		var  d = "div".create();

		vxJS.event.addListener(d, "click", function(e) {
			var matches, parent = this, path = [];

			if(this.nodeName.toLowerCase() === "span" && (matches = this.id.match(/^folder_([0-9]+)$/i))) {

				vxJS.event.cancelBubbling(e);

				while((parent = parent.parentNode) !== d) {
					if(parent.nodeName.toLowerCase() === "li") {
						path.unshift(parent.getElementsByTagName("span").item(0).innerText);
					}
				}

				if(window.confirm("Datei nach '" + path.join("/") + "' verschieben?")) {
					xhr.use({ command: "moveFile" }, vxJS.merge({ destination: matches[1] }, vxWeb.parameters)).submit();
				}

			}

		});

		return {
			expandToCurrent: function() {
				var c = d.querySelector(".current");

				while((c = c.parentNode) !== d) {
					if(c.nodeName.toLowerCase() === "li") {
						c.getElementsByTagName("input").item(0).checked = true;
					}
				}
			},
			element: d
		};
	}());

	/**
	 * the activity indicator shown when file operations are in progress
	 * @type {{element, setActivity, unsetActivity}}
	 */
	var activityIndicator = (function() {
		var e = document.getElementById("activityIndicator"), queueLength = 0;

		var incQL = function() {
			++queueLength;
			vxJS.dom.addClassName(e, "loading");
		};
		var decQL = function() {
			if(queueLength) {
				--queueLength;
				if(!queueLength) {
					vxJS.dom.removeClassName(e, "loading");
				}
			}
		};
		return {
			element: e,
			setActivity: incQL,
			unsetActivity: decQL
		};
	}());

    /**
	 * the progress bar to indicate file upload progress
     * @type {{element, setPercentage, show, hide}}
     */
    var progressBar = (function() {
        var bar = document.getElementById("uploadProgress"),
            progress = bar.querySelector(".bar-item");

        return {
            element: bar,
            setPercentage: function(p) {
                progress.style.width = p + "%";
                this.element.setAttribute("data-tooltip", this.label + " - " + p + "%");
                return this;
            },
            show: function() {
                vxJS.dom.addClassName(bar, "shown");
                return this;
            },
            hide: function() {
                vxJS.dom.removeClassName(bar, "shown");
                this.element.removeAttribute("data-tooltip");
                return this;
            }
        };
    }());

    var getFiles = function(folderId) {
		if(folderId) {
			vxWeb.parameters.folder = folderId;
		}
		xhr.use({ command: "getFiles" }, vxJS.merge({}, vxWeb.parameters)).submit();
	};

	var prepareAddForm = function() {
		var i = 0, e;
		while(e = form.elements[i++]) {
			formInitValues[e.name] = e.value;
		}
		form.appendChild(folderInput);
		form.appendChild(articlesIdInput);

		xhrForm = vxJS.widget.xhrForm(form, { command: "checkUpload", uri: uri, echo: true } );
		xhrForm.addSubmit(form.elements["submit_add"]);

		/*
		 * add additional data required for server side handling of upload 
		 */
		vxJS.event.addListener(
			xhrForm,
			"beforeSubmit",
			function() {
				folderInput.value = vxWeb.parameters.folder || "";
				articlesIdInput.value = vxWeb.parameters.articlesId || "";
				this.setPayload(vxWeb.parameters);
			}

			// todo check max upload filesize
		);

        vxJS.event.addListener(xhrForm, "check", function(response) {

            var r = response.response;

            if(r.success) {

                fileModal.hide();

                // reload file list

                getFiles(vxWeb.parameters.folder);

                // @todo empty form

                // @todo scoping of timeoutId

                mBox.show(r.message || "Daten erfolgreich übernommen!", "toast-success");
            }
            else {
                mBox.show(r.message || "Fehler bei Übernahme der Daten!", "toast-error");
            }

        });

	};

	var buildDirectoryBar = function(pathSegs) {
		var i, l, p, b, add, elem;

		for(i = 0, l = pathSegs.length; i < l; ++i) {

			p = pathSegs[i];
			b = breadCrumbs[i];

			if(b && b.name === p.name) {
				continue;
			}

			while(b = breadCrumbs[i]) {
				vxJS.event.removeListener(b.listener);
				breadCrumbs.splice(i, 1);
				b.element.parentNode.removeChild(b.element);
			}
			break;
		}

		while(i < l) {

			p = pathSegs[i++];
			elem = "button".setProp("class", "btn").create(p.name);

			add =  {
				name:		p.name,
				id:			p.id,
				element:	elem,
				listener:	vxJS.event.addListener(
					elem,
					"click",
					(function(id) {
						return function(e) {
							getFiles(id);
							vxJS.event.preventDefault(e);
						};
					}(p.id))
				)
			};
			breadCrumbs.push(add);

			config.directoryBar.appendChild(add.element);
		}

		if((p = vxJS.dom.getElementsByClassName("active", config.directoryBar)[0])) {
			vxJS.dom.removeClassName(p, "active");
		}
		vxJS.dom.addClassName(breadCrumbs[l - 1].element, "active");
	};

	var appendFolderRow = function(folderData) {
		var a = "a".setProp("href", window.location + "@folder=" + folderData.id).create(folderData.name),
			td = "td".create(folderData.locked ? icons.locked.cloneNode(true) : icons.delFolder.cloneNode(true)),
			cells = ["td".create(a)], i = colNum - 2;

		while(i--) {
			cells.push("td".create());
		}
		cells.push(td);

		t.insertRow("tr".setProp("class", "folder-row").create(cells));

		filesTableListeners.push(vxJS.event.addListener(a, "click", function(e) {
			getFiles(folderData.id);
			vxJS.event.preventDefault(e);
		}));

		filesTableListeners.push(vxJS.event.addListener(td, "click", (function(data) {
			return function() {
				if(this.nodeName.toLowerCase() !== "button") {
					return;
				}
				if(window.confirm("Ordner und Inhalt wirklich löschen?")) {
					xhr.use({ command: "delFolder" }, vxJS.merge({ del: data.id }, vxWeb.parameters)).submit();
				}
			};
		}(folderData))));
	};

	var appendFileRow = function(fileData, funcs) {

		var td = "td".create(fileData.locked ?
				icons.locked.cloneNode(true) :
				funcs.map(function(func) { return icons[func] && func !== "rename" ? icons[func].cloneNode(true) : null; })
			), cells = [], i, l, d;

		for(i = 0, l = colNum - 1; i < l; ++i) {
			d = fileData.columns[i];
			if(typeof d === "undefined") {
				cells.push("td".create());
			}
			else {
				cells.push("td".create(typeof d === "object" ? vxJS.dom.parse(d) : d));
			}
			if(!i && funcs.indexOf("rename") !== -1) {
				cells[0].appendChild(icons.rename.cloneNode(true));
			}
		}

		cells.push(td);
		fileData.tr = "tr".setProp("class", "fileRow").create(cells);
		t.insertRow(fileData.tr);

		filesTableListeners.push(vxJS.event.addListener(fileData.tr, "click", (function(data) {
			return function() {
				var storedNodes, cell, commands = "link,del,move,edit,forward,rename".split(","), l = commands.length, cmd;

				if(["input", "button"].indexOf(this.nodeName.toLowerCase()) === -1) {
					return;
				}

				while(l--) {
					if(vxJS.dom.hasClassName(this, commands[l])) {
						cmd = commands[l];
						break;
					}
				}

				if(!cmd) {
					return;
				}

				switch(cmd) {
					case "link":
						if(vxWeb.parameters && vxWeb.parameters.articlesId) {
							xhr.use( { command: this.checked ? "linkToArticle" : "unlinkFromArticle" }, vxJS.merge({ file: data.id }, vxWeb.parameters)).submit();
						}
						break;

					case "del":
						if(window.confirm("Datei wirklich löschen?")) {
							xhr.use({ command: "delFile" }, vxJS.merge({ file: data.id }, vxWeb.parameters)).submit();
						}
						break;

					case "move":
						xhr.use({ command: "getFolderTree" }, vxJS.merge({ file: data.id }, vxWeb.parameters)).submit();
						break;

					case "edit":
						xhr.use({ command: "requestEditForm" }, vxJS.merge({ file: data.id }, vxWeb.parameters)).submit();
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
								var elem = "input".setProp({ className: "form-input", value: fileData.filename }).create(), keydownListenerId, blurListenerId;

								// remove input element

								var removeInput = function() {
									vxJS.event.removeListener(keydownListenerId);
									vxJS.event.removeListener(blurListenerId);
									vxJS.dom.removeClassName(elem, "error");
									elem.disabled = false;
									cell.removeChild(elem);
								};

								var keydownListener = function(e) {
									if(e.type === "blur" || e.keyCode === 13) {

										if(elem.value !== fileData.filename) {

											// xhr submit

											elem.disabled = true;
											vxJS.xhr(
												{
													uri:		uri,
													command:	"renameFile"
												}, {
													file:		fileData.id,
													filename:	elem.value
												}, {
												}, {
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

									else if(e.keyCode === 27) {

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

	var buildFilesTable = function(fileData) {

		var l = filesTableListeners.length, i,
			folders = fileData.folders || [],
			files = fileData.files || [],
			funcs = fileData.fileFunctions || [];

		while(l--) {
			vxJS.event.removeListener(filesTableListeners[l]);
		}
		while((l = t.element.rows.length)) {
			t.removeRow(t.element.rows[l - 1]);
		}

		if(!files.length && !folders.length) {
			t.insertRow(
				"tr".setProp("class", "fileRow").create(["td".create("em".create("Dieser Ordner ist leer.")), "td".create(), "td".create(), "td".create(), "td".create()])
			);
		}
		else {
			for(i = 0, l = folders.length; i < l; ++i) {
				appendFolderRow(folders[i]);
			}
			for(i = 0, l = files.length; i < l; ++i) {
				appendFileRow(files[i], funcs);
			}
			t.reSort();
		}
	};

	var handleXhrResponse = function(r) {
		var e = r.echo, f, xForm, i = 0, b;

		// hide throbber

		activityIndicator.unsetActivity();

		// response is only evaluated when echo property is set

		if(e) {

			switch(e.httpRequest) {

				case "delFolder":
                    if(r.response.error) {
                        mBox.show(r.response.error, "toast-error");
                    }
                    else {
                        buildFilesTable(r.response);
                        while ((b = breadCrumbs[i])) {
                            if (b.id === e.id) {
                                while ((b = breadCrumbs[i])) {
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
					fileModal.hide();

				case "getFiles":
				case "addFolder":
				case "delFile":
					if(r.response.error) {
                        mBox.show(r.response.error, "toast-error");
                    }
					else {
                        if (r.response.pathSegments) {
                            buildDirectoryBar(r.response.pathSegments);
                        }
                        buildFilesTable(r.response);
                    }
					break;

				case "requestAddForm":
					fileModal.setHeader("Neue Datei übertragen/anlegen").setContent(r.response).setSize();
					form = fileModal.element.getElementsByTagName("form")[0];
					prepareAddForm();
					fileModal.show();
					form.elements[0].focus();
					break;

				case "requestEditForm":
                    fileModal.setHeader("Datei bearbeiten").setContent(r.response).setSize();
					f = fileModal.element.getElementsByTagName("form")[0];

					xForm = vxJS.widget.xhrForm(f, { command: "checkEditForm", uri: uri });
					xForm.	addSubmit(f.elements["submit_edit"]).setPayload(vxJS.merge({ file: e.file }, vxWeb.parameters));

					vxJS.event.addListener(
						xForm,
						"check",
						function() {
							var r = this.getLastXhrResponse().response;

							if(r.elements) {
								//@todo: possible error handling
							}
							else {
								buildFilesTable(r);
								xForm = null;
								fileModal.hide();
							}
						}
					);

					fileModal.show();
					break;

				case "getFolderTree":
					treeContainer.element.innerHTML = r.response;
					treeContainer.expandToCurrent();
					fileModal.setHeader("Zielordner wählen").setContent(treeContainer.element).setSize("sm").show();
					break;
			}
		}
	};

	// everything prepared, get things going

    vxJS.event.addListener(addFileButton, "click", function() {
        if (!form) {
            xhr.use({command: "requestAddForm"}).submit();
        }
        else {
        	fileModal.setHeader("Neue Datei übertragen/anlegen").setContent(form).show();
        }
    });

    vxJS.event.addListener(addFolderButton, "click", function(e) {
        this.style.display = "none";
        addFolderInput.style.display = "";
        addFolderInput.value = "";
        addFolderInput.focus();
    });

    vxJS.event.addListener(addFolderInput, "blur", function() {
        this.style.display = "none";
        addFolderButton.style.display = "";
    });

    vxJS.event.addListener(addFolderInput, "keydown", function(e) {
        if(e.keyCode === 27 || e.keyCode === 13) {
            this.style.display = "none";
            addFolderButton.style.display = "";
        }
        if(e.keyCode === 13 && this.value.trim()) {
            xhr.use({ command: "addFolder" }, vxJS.merge({ folderName: this.value }, vxWeb.parameters)).submit();
        }
    });

    vxJS.event.addListener(xhr, "timeout", function() { mBox.show("Dateioperation dauert zu lange. Bitte erneut versuchen.", "toast-error"); });
	vxJS.event.addListener(xhr, "complete", function() { activityIndicator.setActivity(); handleXhrResponse(this.response); });
	vxJS.event.addListener(
		t,
		"finishSort",
		function() {
			var c = this.getActiveColumn();
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

	getFiles();

	// add drag and drop file upload, when support sufficient

	if((new XMLHttpRequest()).upload && window.File && window.FileList && window.FileReader) {

		// outer scope variables: filesTable, buildFilesTable()

		(function() {
			var uploadXhr = vxJS.xhr( { timeout: config.maxUploadTime, raw: true } ), uploadQuery, uploadActive, filesQueue = [];

			var finishUpload = function() {
				uploadActive = false;
				activityIndicator.unsetActivity();
				progressBar.hide();
			};

			var startUpload = function() {
				uploadActive = true;
				activityIndicator.setActivity();
				progressBar.show();
			};

			vxJS.event.addListener(config.filesList, "dragover", function(e) {
				vxJS.dom.addClassName(config.filesList, "dragged-over");
				vxJS.event.preventDefault(e);
			});

			vxJS.event.addListener(config.filesList, "dragleave", function(e) {
				vxJS.dom.removeClassName(config.filesList, "dragged-over");
				vxJS.event.preventDefault(e);
			});

			vxJS.event.addListener(config.filesList, "drop", function(e) {
				var i, l, f, files = e.target.files || e.dataTransfer.files, unpackZips;

				for(i = 0, l = files.length; i < l; ++i) {
					if(files[i].size > config.uploadMaxFilesize) {
						mBox.show("'" + files[i].name + "' übersteigt die maximale Größe eines Uploads (" + bytesToSize(config.uploadMaxFilesize) + ") und wird nicht hochgeladen.", "toast-error");
					}
					else {
						if(unpackZips === undefined && /application\/.*?zip.*?/.test(files[i].type)) {
							unpackZips = window.confirm("ZIP Datei(en) entpacken (Verzeichnisstruktur wird beibehalten)?");
						}
						filesQueue.push(files[i]);
					}
				}

				if(!uploadActive) {
					if(f = filesQueue.shift()) {
						startUpload();
						progressBar.label = f.name;

						uploadQuery = ['unpack=' + (unpackZips ? 1 : 0)];
						if(vxWeb.parameters.folder) {
							uploadQuery.push("folder=" + vxWeb.parameters.folder);
						}
						if(vxWeb.parameters.articlesId) {
							uploadQuery.push("articlesId=" + vxWeb.parameters.articlesId);
						}
						uploadQuery = (vxWeb.routes.upload.indexOf("?") === -1 ? "?" : "&") + uploadQuery.join("&");

                        uploadXhr.setHeaders({
                            "X-File-Name": f.name.replace(/[^\x00-\x7F]/g, function (c) { return encodeURIComponent(c); }),
                            "X-File-Size": f.size,
                            "X-File-Type": f.type
                        });

                        uploadXhr.use({ uri: vxWeb.routes.upload + uploadQuery }, f).submit();
					}
				}

				vxJS.dom.removeClassName(config.filesList, "dragged-over");

				vxJS.event.preventDefault(e);
				vxJS.event.cancelBubbling(e);
			});

			vxJS.event.addListener(uploadXhr, "timeout", function() {
				finishUpload();
				mBox.show("Maximale Zeitdauer für Uploads von " + Math.floor(vxWeb.serverConfig.maxUploadTime / 1000) +  "Sekunden überschritten.", "toast-error");
			});

			vxJS.event.addListener(uploadXhr, "complete", function() {
				var f, r = this.response, e = r.echo;

				if(r.response.error) {
					filesQueue = [];
					finishUpload();
					mBox.show(r.response.message || "Upload Error!", "toast-error");
				}

				else {
					if(f = filesQueue.shift()) {
						progressBar.label = f.name;
                        uploadXhr.setHeaders({
                            "X-File-Name": f.name.replace(/[^\x00-\x7F]/g, function (c) { return encodeURIComponent(c); }),
                            "X-File-Size": f.size,
                            "X-File-Type": f.type
                        });

                        uploadXhr.use(null, f).submit();
					}
					else {
						finishUpload();
					}
				}
				
				// refresh folder, in case folder was not changed by user (weak comparison in case folder[Id] is undefined)
				// response determines when update of file list happens
				
				if(r.response.files && e.folder == vxWeb.parameters.folder) {
					buildFilesTable(r.response);
				}

			});

			// vxJS.event.addListener won't detect XHR.upload as host object

			uploadXhr.xhrObj.upload.addEventListener("progress", function(e) {
				progressBar.setPercentage(Math.round(e.loaded / e.total * 100));
			}, false);
		}());
	}
	
	// expose methods
	
	return {
		gotoFolder: getFiles
	};
};
