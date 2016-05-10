<?php defined( 'ABSPATH' ) || die(); ?>

<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = "<?php echo esc_js( $st_options['conversion_id'] ); ?>";
	var google_custom_params = window.google_tag_params;
	var google_remarketing_only = true;
	/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
	<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/<?php echo esc_js( $st_options['conversion_id'] ); ?>/?value=0&guid=ON&script=0"/>
	</div>
</noscript>
