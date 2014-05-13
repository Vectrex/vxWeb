<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<script type="text/javascript" src="/js/admin/doProfile.js"></script>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(this.vxWeb.doProfile);
</script>

<?php echo $tpl->form; ?>

<div class="inputGroup xl" style="margin-bottom: 1em"><span class="s">Submit</span><input class="pct_100" name="xxx" placeholder="test"></div>
<div class="inputGroup xl" style="margin-bottom: 1em"><span class="inputGroupBtn"><button>Submit</button></span><input class="pct_100" name="xxx" placeholder="test"></div>
<div class="inputGroup xl" style="margin-bottom: 1em"><span class="s">Ok</span><input class="pct_100" name="xxx" placeholder="test"><span class="s">here</span></div>
<div class="inputGroup xl" style="margin-bottom: 1em"><input class="pct_100" name="xxx" placeholder="test"><span class="inputGroupBtn"><button>Ok</button></span></div>
<div class="inputGroup xl" style="margin-bottom: 1em"><input class="pct_100" name="xxx" placeholder="test"><span class="inputGroupBtn"><button class="withIcon" data-icon="&#xe02e;">Login</button></span></div>
