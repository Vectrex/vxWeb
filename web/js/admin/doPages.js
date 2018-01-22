this.vxWeb.doPages = function() {

	"use strict";

	var	form = document.forms[0],
		xhrForm = vxJS.widget.xhrForm(form, { uri: vxWeb.routes.page }),
		revisionsContainer = document.getElementById("revisions").tBodies[0],
		delElem = (function() {
			var e = "button".setProp( { type: "button", title: "Löschen", className: "btn btn-primary webfont-icon-only" }).create();
			e.setAttribute("data-icon", "\ue011");
			return e;
		}()),
		needsFormData = true, revisionId, timeoutId;

	var markRevisionRow = function(id) {

		var previous = revisionsContainer.querySelector("tr.active");

		if(previous) {
			vxJS.dom.removeClassName(previous, "active");
		}
		vxJS.dom.addClassName(revisionsContainer.querySelector("tr#rev" + id), "active");

	};

	var insertDelElements = function() {

		var rows = revisionsContainer.querySelectorAll("table > tbody tr"), l = rows.length, row, checked, del;
		
		while(l--) {
			row = rows[l];
			checked = row.querySelector("input[type='checkbox']").checked;

			if(row.lastChild.lastChild && checked) {
				row.lastChild.removeChild(row.lastChild.lastChild);
			}
			if(!row.lastChild.lastChild && !checked) {
				del = delElem.cloneNode(true);
				del.name = "del" + row.id.match(/^rev([1-9][0-9]*)$/)[1];
				row.lastChild.appendChild(del);
			};
		}
	};

	var fillRevisions = function(data) {
	
		var revs = data.revisions, rev, i, l;
	
		vxJS.dom.deleteChildNodes(revisionsContainer);
	
		if(revs) {

			revs.sort(function(a, b) {return a.firstCreated < b.firstCreated ? 1 : -1; });
	
			for(i = 0, l = revs.length; i < l; ++i) {
				rev = revs[i];

				revisionsContainer.appendChild(
					"tr".setProp("id", "rev" + rev.id).create([
						"td".create(rev.locale || "-"),
						"td".create(
							"a".setProp("href", "#rev" + rev.id).create((new Date(rev.firstCreated)).format("%Y-%M-%D %H:%I:%S"))
						),
						"td".create(
							"label".setProp( { className: "form-switch" } ).create([
								"input".setProp( { value: "rev" + rev.id, type: "checkbox", checked: rev.active, disabled: rev.active } ).create(),
								"i".setProp( { className: "form-icon" } ).create()
                            ])
						),
						"td".create()
						
					])
				);
			}

			rev = revs[0];
	
			for(i = 0; i < l; ++i) {
				if(revs[i].active) {
					rev = revs[i];
					break;
				}
			}
			if(needsFormData) {
				revisionDataXhr.use(null, { id: rev.id } ).submit();
			}
			else {
				markRevisionRow(rev.id);
			}
			
			insertDelElements();
		}
	};

	var setRevisionData = function(data) {
	
		needsFormData = false;
	
		if(data.id) {

			revisionId = data.id;

			CKEDITOR.instances.markup.setData(
				data.markup,
				{
					callback: function() {
						this.checkDirty();
						this.resetUndo();
					}
				}
			);
	
			form.alias.value		= data.alias;
			form.title.value		= data.title;
			form.keywords.value		= data.keywords;
			form.description.value	= data.description;
			
			markRevisionRow(data.id);
	
		}	
	};

	var activationXhr = vxJS.xhr(
		{
			uri: vxWeb.routes.page,
			command: "changeActivationOfRevision"
		},
		null,
		null,
		{
			complete: function() {
				insertDelElements()
			}
		}
	);

	var deletionXhr = vxJS.xhr(
		{
			uri: vxWeb.routes.page,
			command: "deleteRevision"
		},
		null,
		null,
		{
			complete: function() { revisionsXhr.submit(); }
		}
	);

	var revisionsXhr = vxJS.xhr(
		{
			uri: vxWeb.routes.page,
			command: "getRevisions"
		},
		null,
		null,
		{
			complete: function() { fillRevisions(this.response); }
		}
	);

	var revisionDataXhr = vxJS.xhr(
		{
			uri: vxWeb.routes.page,
			command: "getRevisionData"
		},
		null,
		null,
		{
			complete: function() { setRevisionData(this.response); }
		}
	);

	var handleRevisionsClick = function(e) {
	
		var id, checked;
	
		switch(this.nodeName.toLowerCase()) {
			case "a":
				if(!needsFormData) {
					vxJS.event.cancelBubbling(e);
					vxJS.event.preventDefault(e);
					id = this.hash.match(/^#rev([1-9][0-9]*$)/)[1];
					revisionDataXhr.use(null, { id: id } ).submit();
				}
				break;

			case "input":
				id		= this.value.match(/^rev([1-9][0-9]*)$/)[1];
				checked	= this.checked;
				
				vxJS.collectionToArray(revisionsContainer.querySelectorAll("input[type='checkbox']")).forEach(function(elem) {
					elem.checked = false;
					elem.disabled = false;
				});
				this.checked = checked;
				this.disabled = true;

				activationXhr.use(null, { id: id, activate: checked } ).submit();
				break;
				
			case "button":
				if(window.confirm("Revision wirklich löschen?")) {
					id = this.name.match(/^del([1-9][0-9]*)$/)[1];
					deletionXhr.use(null, { id: id} ).submit();
				}
				break;
		}
	};

	var parseServerCheck = function(r) {

		var mBox = document.getElementById("messageBox"), timeoutId, txt;

		if(r.success) {
			txt = r.message || "Daten erfolgreich übernommen!";

			vxJS.dom.removeClassName(mBox, "toast-error");
			vxJS.dom.addClassName(mBox, "toast-success");

		}

		else {
			txt = r.message || "Fehler bei Übernahme der Daten!";

			vxJS.dom.removeClassName(mBox, "toast-success");
			vxJS.dom.addClassName(mBox, "toast-error");
		}

		mBox.firstChild.nodeValue = txt;

		vxJS.dom.addClassName(mBox, "display");

		if(timeoutId) {
			window.clearTimeout(timeoutId);
		}
		timeoutId = window.setTimeout(function() {
			vxJS.dom.removeClassName(mBox, "display");
		}, 5000);

	};

	revisionsXhr.submit();
	vxJS.event.addListener(revisionsContainer, "click", handleRevisionsClick);

	xhrForm.addSubmit(form.elements.submit_page);
	vxJS.event.addListener(xhrForm, "check", parseServerCheck);
	vxJS.event.addListener(xhrForm, "beforeSubmit", function() {
		this.setPayload( { revisionId: revisionId } );
		if(CKEDITOR && CKEDITOR.instances.markup) {
			this.element.elements.markup.value = CKEDITOR.instances.markup.getData();
		}
	});
};
