<?php defined( 'ABSPATH' ) || die();
/**
 * Facebook SDK module.
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?php echo esc_js( get_locale() ); ?>/sdk.js#xfbml=1&version=v2.5&appId=1613892592185308";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
