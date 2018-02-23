<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<h1>User</h1>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(function() {
		var lsValue, lsKey = window.location.origin + "/admin/users__sort__",
			t = vxJS.widget.sorTable(
			document.querySelector(".table"),	{
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
				window.localStorage.setItem(lsKey, JSON.stringify( { ndx: c.ndx, asc: c.asc } ));
			}
		);

		if(window.localStorage) {
            if ((lsValue = window.localStorage.getItem(lsKey))) {
                lsValue = JSON.parse(lsValue);
                t.sortBy(lsValue.ndx, lsValue.asc ? "asc" : "desc");
            }
        }
	});
</script>

<div class="vx-button-bar">
    <a class="btn with-webfont-icon-right btn-primary" data-icon="&#xe018;" href="$users/new">User anlegen</a>
</div>

<table class="table table-striped">
	<tr>
		<th class="col-3 vx-sortable-header">Username</th>
		<th class="col-2 vx-sortable-header">Name</th>
		<th>Email</th>
		<th class="col-2 vx-sortable-header">Gruppe</th>
		<th class="col-1">&nbsp;</th>
	</tr>

	<?php foreach($this->users as $this->user): ?>
		<?php $this->includeFile('admin/snippets/user_row.php'); ?>
	<?php endforeach; ?>

</table>
