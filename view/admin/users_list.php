<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<h1>User</h1>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(function() {
		var lsValue, lsKey = window.location.origin + "/admin/users__sort__",
			t = vxJS.widget.sorTable(
			vxJS.dom.getElementsByClassName("list")[0],	{
				columnFormat: [
					null,
					null,
					null,
					null,
					"no_sort"
				]
			});

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
			else {
				vxJS.widget.shared.shadeTableRows({ element: t.element });
			}
		}

		else {
			vxJS.widget.shared.shadeTableRows({ element: t.element });
		}
	});
</script>

<div class="buttonBar">
	<a class="buttonLink withIcon" data-icon="&#xe018;" href="$users/new">User anlegen</a>
</div>

<table class="list pct_100">
	<tr>
		<th class="ml">Username</th>
		<th class="l">Name</th>
		<th>Email</th>
		<th class="m">Gruppe</th>
		<th class="ssm">&nbsp;</th>
	</tr>

	<?php $rowNdx = 0; ?>

	<?php foreach($this->users as $this->user): ?>
		<?php $this->colorNdx = $rowNdx ++ % 2; ?>
		<?php $this->includeFile('admin/snippets/user_row.php'); ?>
	<?php endforeach; ?>

</table>
