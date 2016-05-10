<?php
/**
 * Plugin Name: W3CM - Countdown Widget
 * Plugin URI: http://w3craftsman.com
 * Description: Countdown widget plugin.
 * Version: 1.0.0
 * Author: Aleksandr Levashov
 * Author URI: http://me.w3craftsman.com
 * Text Domain: w3cm
 * License: Proprietary software
 */
namespace W3CM;


class Countdown_Widget extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'w3cm-countdown',
			'W3CM - Countdown Widget'
		);
		wp_register_script(
			$this->id_base,
			sprintf( '%sjs/%s.js', plugin_dir_url( __FILE__ ), $this->id_base )
		);
		wp_register_style(
			$this->id_base,
			sprintf( '%scss/%s.css', plugin_dir_url( __FILE__ ), $this->id_base )
		);
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}


	public function enqueue_scripts() {
		wp_enqueue_style( $this->id_base );
		wp_enqueue_script( $this->id_base );
	}


	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		?>
			<span><?php echo $instance['title']; ?></span>
			<div></div><div></div><div></div>
			<script>
				CountdownTimer.locale = '<?php echo get_locale(); ?>';
				CountdownTimer(
					'<?php echo $this->id; ?>',
					'<?php echo strftime( '%Y/%m/%d 00:00:00', $instance['date'] ); ?>'
				);
			</script>
		<?php
		echo $args['after_widget'];
	}


	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'Some text here!';
		$date = $instance['date'] ? strftime( '%Y-%m-%d', $instance['date'] ) : date('Y-m-d');
		?>
			<p>
				<label>
					<?php _e( 'Title:' ); ?>
					<input
						class="widefat"
						id="<?php echo $this->get_field_id( 'title' ); ?>"
						name="<?php echo $this->get_field_name( 'title' ); ?>"
						type="text"
						value="<?php echo esc_attr( $title ); ?>"
					>
				</label> 
				<label>
					<?php _e( 'Date:' ); ?>
					<input
						class="widefat"
						id="<?php echo $this->get_field_id( 'date' ); ?>"
						name="<?php echo $this->get_field_name( 'date' ); ?>"
						type="date"
						value="<?php echo esc_attr( $date ); ?>"
					>
				</label>
			</p>
		<?php
	}


	public function update( $new_instance, $old_instance ) {
		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'title' => array()
			)
		);
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_kses( $new_instance['title'], $allowed_html ) : '';
		$instance['date'] = strtotime( $new_instance['date'] );

		return $instance;
	}
}


add_action(
	'widgets_init',
	function() {
		register_widget( 'W3CM\Countdown_Widget' );
	}
);