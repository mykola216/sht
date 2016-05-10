<h3><?php esc_html_e( 'Congratulations', 'pronamic_ideal' ); ?></h3>

<p>
	<?php esc_html_e( 'You’ve just installed the Pronamic iDEAL plugin. Click “Start Tour” to view a quick introduction of this plugin’s core functionality.', 'pronamic_ideal' ); ?>
</p>

<div class="wp-pointer-buttons pp-pointer-buttons">
	<span class="pp-pointer-buttons-right">
		<a href="<?php echo esc_attr( add_query_arg( 'page', 'pronamic_ideal', admin_url( 'admin.php' ) ) ); ?>" class="button-primary pp-pointer-button-next"><?php esc_html_e( 'Start Tour', 'pronamic_ideal' ); ?></a>

		<a href="<?php echo esc_attr( $this->get_close_url() ); ?>" class="button-secondary pp-pointer-button-close"><?php esc_html_e( 'Close', 'pronamic_ideal' ); ?></a>
	</span>
</div>
