<!-- { extend: admin/layout_with_menu.php @ content_block } -->
<h1>Artikel &amp; News</h1>

<div class="buttonBar">
	<a class="buttonLink withIcon" data-icon="&#xe018;" href="$articles/new">Artikel anlegen</a>
</div>

<script type="text/javascript">

	this.vxWeb.routes.publish = "<?php echo vxPHP\Routing\Router::getRoute('publishXhr', 'admin.php')->getUrl(); ?>";

	/**
	 * render filtered rows
	 */
	var renderFilteredMatches = function() {

		var i, l, rows, msg, s, data, selectors = document.querySelectorAll("#matchesCountContainer strong");

		// write infobox
		
		vxJS.dom.deleteChildNodes(matchesInfoBox);
		if(this.getQueryInfo()) {
			matchesInfoBox.appendChild(vxJS.dom.parse(this.getQueryInfo()));
		}
		
		// empty table

		matchesTable.removeAllRows();

		// write counts and sample id
		
		selectors[0].innerHTML = this.getFilteredCount() || 0;
		selectors[1].innerHTML = this.getTotalCount() || 0;
		
		// fill table

		if(rows = this.getFilteredRows()) {

			// ensure visible container

			matchesContainer.style.display = "";

			// ensure hidden message box

			matchesMsgBox.style.display = "none";

			// ensure hidden map container

			for(i = 0, l = rows.length; i < l; ++i) {

				s = rows[i];

				data = (s.id ? ["td".create(s.id)] : []).concat(
					s.originTree.split("#").concat(["", "", "", "", ""]).slice(0, 5).domWrapWithTag("td"),
					"td".create(s.mp),
					"td".create(s.ignoredMutations || ""),
					"td".setProp("className", s.q[0]).create([
						"a".setProp({ className: "showHgHeatmap", href: "#" + s.hg[0] }).create(s.hg[0]),
						"a".setProp({ className: "showHoverInfo iconFont", href: "#extendedInfo|matches|r1|" + s.ndx }).create("\ue01a")
					]),
					"td".setProp("className", s.q[1]).create([
						"a".setProp({ className: "showHgHeatmap", href: "#" + s.hg[1] }).create(s.hg[1]),
						"a".setProp({ className: "showHoverInfo iconFont", href: "#extendedInfo|matches|r2|" + s.ndx }).create("\ue01a")
					]),
					"td".create(s.publications ? renderPublications(s.publications) : "")
				);

				matchesTable.insertRow("tr".create(data));
			}

			matchesTable.reSort();
		}

		// no rows found

		else {

			// check for message box, and display message

			if(msg = this.getMessage())  {

				// ensure visible container

				matchesContainer.style.display = "";

				matchesMsgBox.innerHTML = msg;
				matchesMsgBox.style.display = "";
			}

			// get EMMA result

			else {
				emmaXhr.use(null, { calcToken: calcToken }).submit();
			}
		}
	};
		
	var tmp = {

			/**
			 * executes XHR with filter parameters and returns filtered data
			 */
			tableFilter: function(table, filterXhr) {

				var timeoutId,
					inputs = vxJS.collectionToArray(table.tHead.getElementsByTagName("input")), l = inputs.length,
					filter = {},
					that = {},

					filteredRows,
					totalCount,
					filteredCount,
					message,
					queryInfo;

				var applyFilter = function() {
					filterXhr.use(null, vxJS.merge(filterXhr.getParameters(), { filter: filter }) ).submit();
				};

				var handleKeyUp = function() {
					var val = this.value.trim(), col = this.name.split("_")[1];

					window.clearTimeout(timeoutId);

					if(filter[col] !== val) {
						filter[col] = val;
						timeoutId = window.setTimeout(applyFilter, 350);
					}
				};

				var setRows = function() {
					var r = this.response;

					totalCount		= r.totalCount;
					filteredCount	= r.filteredCount;
					filteredRows	= r.rows;
					message			= r.message;
					queryInfo		= r.queryInfo;

					vxJS.event.serve(that, "filterApplied");
				};

				while(l--) {

					vxJS.event.addListener(inputs[l], "keyup", handleKeyUp);

					filter[inputs[l].name.split("_")[1]] = inputs[l].value.trim();
				}

				vxJS.event.addListener(filterXhr, "complete", setRows);

				that.getFilteredCount = function() {
					return filteredCount;
				};
				
				that.getTotalCount = function() {
					return totalCount;
				};

				that.getFilteredRows = function() {
					return filteredRows;
				};

				that.getMessage = function() {
					return message;
				};

				that.getQueryInfo = function() {
					return queryInfo;
				};

				that.clearFilter = function() {
					var l = inputs.length;

					while(l--) {
						inputs[l].value = "";
					}

					filter = {};
				};

				that.applyFilter = applyFilter;

				that.element = table;

				return that;
			}

	}
	
	vxJS.event.addDomReadyListener(function() {

		var lsValue, lsKey = window.location.href + "__sort__",
			t = vxJS.widget.sorTable(
			vxJS.dom.getElementsByClassName("list")[0],	{
				columnFormat: [
					null,
					null,

					// checkbox sort

					function(a, b) {

						var checked1 = a.element.cells[this.ndx].getElementsByTagName("input")[0].checked,
							checked2 = b.element.cells[this.ndx].getElementsByTagName("input")[0].checked;

						if(checked1 === checked2) {
							return 0;
						}
						if(this.asc) {
							return checked2 ? -1 : 1;
						}
						else {
							return checked1 ? -1 : 1;
						}
					},
					"date_iso",
					"date_iso",
					"date_iso",
					"float",
					null,
					"no_sort"
				]
			}),
			publishXhr = vxJS.xhr( { uri: vxWeb.routes.publish } );

		vxJS.event.addListener(
			t,
			"finishSort",
			function() {
				var c = this.getActiveColumn();
				vxJS.widget.shared.shadeTableRows({ element: this.element });
				window.localStorage.setItem(lsKey, JSON.stringify( { ndx: c.ndx, asc: c.asc } ));
			}
		);

		if(window.localStorage) {
			if((lsValue = window.localStorage.getItem(lsKey))) {
				lsValue = JSON.parse(lsValue);
				t.sortBy(lsValue.ndx, lsValue.asc ? "asc" : "desc");
			}
		}

		vxJS.event.addListener(t, "click", function() {
			var matches;

			if(this.type === "checkbox") {
				if(matches = this.name.match(/^publish\[(\d+)\]$/)) {
					publishXhr.use(null, { id: matches[1], state: this.checked ? 1 : 0 }).submit();
					if(t.getActiveColumn().ndx === 2) {
						t.reSort();
					}
				}
			}

		});
	});
</script>

<table class="list pct_100">
	<thead>
		<tr>
			<th><input class="pct_100" name="categoryFilter" placeholder="Kategorie filtern..."></th>
			<th><input class="pct_100" name="titleFilter" placeholder="Titel filtern..."></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		<tr>
			<th class="m">Kategorie</th>
			<th>Titel</th>
			<th class="xss center">Pub</th>
			<th class="m right">Artikeldatum</th>
			<th class="m right">Anzeige von</th>
			<th class="m right">Anzeige bis</th>
			<th class="sm centered">Sortierziffer</th>
			<th class="ml right">Angelegt/aktualisiert</th>
			<th class="ssm">&nbsp;</th>
		</tr>
	</thead>
<?php if(!empty($tpl->articles)): ?>
	<?php $color = 0; ?>
	<?php foreach($tpl->articles as $article): ?>
		<tr class="row<?php echo $color++ % 2; ?>">
			<td><?php echo $article->getCategory()->getTitle(); ?></td>
			<td><?php echo $article->getHeadline(); ?></td>
			<td class="centered">
				<label class="switch">
					<input type="checkbox" name="publish[<?php echo $article->getId(); ?>]"
						<?php if ($article->isPublished()): ?>checked="checked"<?php endif; ?>
						<?php if(!$this->can_publish): ?> disabled="disabled"<?php endif; ?>
					>
					<span class="trough" data-on="&#xe01e;" data-off="&#xe01d;"></span>
					<span class="handle"></span>  
				</label>
			</td>
			<td class="right"><?php echo is_null($article->getDate()) ? '' : $article->getDate()->format('Y-m-d'); ?></td>
			<td class="right"><?php echo is_null($article->getDisplayFrom()) ? '' : $article->getDisplayFrom()->format('Y-m-d'); ?></td>
			<td class="right"><?php echo is_null($article->getDisplayUntil()) ? '' : $article->getDisplayUntil()->format('Y-m-d'); ?></td>
			<td class="centered"><?php echo $article->getCustomSort(); ?></td>
			<td class="right"><?php echo is_null($article->getLastUpdated()) ? '' : $article->getLastUpdated()->format('Y-m-d H:i:s'); ?></td>
			<td>
				<a class="buttonLink iconOnly" data-icon="&#xe002;" href="$articles?id=<?php echo $article->getId(); ?>" title="Bearbeiten"></a>
				<a class="buttonLink iconOnly" data-icon="&#xe011;" href="$articles/del?id=<?php echo $article->getId(); ?>" onclick="return window.confirm('Wirklich löschen?');" title="Löschen"></a>
			</td>
		</tr>
	<?php endforeach; ?>

<?php else: ?>
	<tr><td colspan="8">Keine angelegt.</td></tr>
<?php endif;?>

</table>