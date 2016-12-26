<?php defined( 'ABSPATH' ) || die();
/**
 * Facebook comments module.
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */

global $st_options;

$fb_comments_number = esc_attr((int)$st_options['fb_comments_number']);
$fb_comments_number = $fb_comments_number ? $fb_comments_number : 2;

?>
<div class="fb-comments" data-href="<?php echo esc_attr( get_permalink() ); ?>" data-numposts="<?php echo $fb_comments_number; ?>" data-colorscheme="light" data-width="100%"></div>
