<?php
/**
 * Footer Template
 *
 * Here we setup all logic and XHTML that is required for the footer section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

	global $woo_options;

	canvas_child_instagram_feed();

	woo_footer_top();
	woo_footer_before();
?>
	<footer id="footer" class="col-full">

		<?php woo_footer_inside(); ?>

		<div id="copyright" class="col-left">
			<?php woo_footer_left(); ?>
		</div>

		<div id="credit" class="col-right">
			<?php woo_footer_right(); ?>
		</div>

	</footer>

	<?php woo_footer_after(); ?>

	</div><!-- /#inner-wrapper -->

</div><!-- /#wrapper -->

<div class="fix"></div><!--/.fix-->

<?php wp_footer(); ?>
<?php woo_foot(); ?>
<script type="text/javascript">
	function buttons(){
		var kCanonical = document.querySelector("link[rel='canonical']").href;
		window.kCompositeSlug = kCanonical.replace('https://','http://');
		return;
	}
	buttons();
</script>
</body>
</html>