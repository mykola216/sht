<?php

$post_id = get_the_ID();

$form_id = get_post_meta( $post_id, '_pronamic_pay_gf_form_id', true );

$form_meta = RGFormsModel::get_form_meta( $form_id );

$feed = new stdClass();
$feed->conditionFieldId       = get_post_meta( $post_id, '_pronamic_pay_gf_condition_field_id', true );
$feed->conditionOperator      = get_post_meta( $post_id, '_pronamic_pay_gf_condition_operator', true );
$feed->conditionValue         = get_post_meta( $post_id, '_pronamic_pay_gf_condition_value', true );
$feed->delayNotificationIds   = get_post_meta( $post_id, '_pronamic_pay_gf_delay_notification_ids', true );
$feed->fields                 = get_post_meta( $post_id, '_pronamic_pay_gf_fields', true );
$feed->userRoleFieldId        = get_post_meta( $post_id, '_pronamic_pay_gf_user_role_field_id', true );

?>

<div id="gf-ideal-feed-editor">
	<?php wp_nonce_field( 'pronamic_pay_save_pay_gf', 'pronamic_pay_nonce' ); ?>

	<input id="gf_ideal_gravity_form" name="gf_ideal_gravity_form" value="<?php echo esc_attr( json_encode( $form_meta ) ); ?>" type="hidden" />
	<input id="gf_ideal_feed" name="gf_ideal_feed" value="<?php echo esc_attr( json_encode( $feed ) ); ?>" type="hidden" />

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="_pronamic_pay_gf_form_id">
					<?php esc_html_e( 'Gravity Form', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<select id="_pronamic_pay_gf_form_id" name="_pronamic_pay_gf_form_id">
					<option value=""><?php esc_html_e( '— Select a form —', 'pronamic_ideal' ); ?></option>

					<?php foreach ( RGFormsModel::get_forms() as $form ) : ?>

						<option value="<?php echo esc_attr( $form->id ); ?>" <?php selected( $form_id, $form->id ); ?>>
							<?php echo esc_html( $form->title ); ?>
						</option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="_pronamic_pay_gf_config_id">
					<?php esc_html_e( 'Configuration', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<?php

				$config_id = get_post_meta( $post_id, '_pronamic_pay_gf_config_id', true );

				if ( '' === $config_id ) {
					$config_id = get_option( 'pronamic_pay_config_id' );
				}

				Pronamic_WP_Pay_Admin::dropdown_configs( array(
					'name'     => '_pronamic_pay_gf_config_id',
					'selected' => $config_id,
				) );

				?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="_pronamic_pay_gf_order_id">
					<?php esc_html_e( 'Entry ID Prefix', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<?php

				$entry_id_prefix = get_post_meta( $post_id, '_pronamic_pay_gf_entry_id_prefix', true );

				?>
				<input id="_pronamic_pay_gf_entry_id_prefix" name="_pronamic_pay_gf_entry_id_prefix" value="<?php echo esc_attr( $entry_id_prefix ); ?>" type="text" class="input-text regular-input" maxlength="8" />

				<span class="description">
					<br />
					<?php esc_html_e( 'Please enter a prefix for your entry ID\'s. If you use an gateway for multiple websites ensure this prefix is unique as not all gateways will allow payments with the same ID.', 'pronamic_ideal' ); ?>
				</span>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="_pronamic_pay_gf_transaction_description">
					<?php esc_html_e( 'Transaction Description', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<?php

				$transaction_description = get_post_meta( $post_id, '_pronamic_pay_gf_transaction_description', true );

				?>
				<input id="_pronamic_pay_gf_transaction_description" name="_pronamic_pay_gf_transaction_description" value="<?php echo esc_attr( $transaction_description ); ?>" type="text" class="regular-text" />

				<span class="description">
					<br />
					<?php esc_html_e( 'Maximum number of charachters is 32, you should also consider the use of variables Gravity Forms. An generated description that is longer than 32 characters will be automatically truncated.', 'pronamic_ideal' ); ?>
					<br />
					<?php

					echo wp_kses(
						sprintf(
							__( 'Merge Tag Examples: Entry Id = %s, Form Id = %s, Form Title = %s', 'pronamic_ideal' ),
							'<code>{entry_id}</code>',
							'<code>{form_id}</code>',
							'<code>{form_title}</code>'
						),
						array(
							'code' => array(),
						)
					);

					?>
				</span>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="gf_ideal_condition_enabled">
					<?php esc_html_e( 'Condition', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<?php

				$condition_enabled  = get_post_meta( $post_id, '_pronamic_pay_gf_condition_enabled', true );
				$condition_field_id = get_post_meta( $post_id, '_pronamic_pay_gf_condition_field_id', true );
				$condition_operator = get_post_meta( $post_id, '_pronamic_pay_gf_condition_operator', true );
				$condition_value    = get_post_meta( $post_id, '_pronamic_pay_gf_condition_value', true );

				?>
				<div>
					<input id="gf_ideal_condition_enabled" name="_pronamic_pay_gf_condition_enabled" value="true" type="checkbox" <?php checked( $condition_enabled ); ?> />

					<label for="gf_ideal_condition_enabled">
						<?php esc_html_e( 'Enable', 'pronamic_ideal' ); ?>
					</label>
				</div>

				<div id="gf_ideal_condition_config">
					<?php

					// Select field
					$select_field = '<select id="gf_ideal_condition_field_id" name="_pronamic_pay_gf_condition_field_id"></select>';

					// Select operator
					$select_operator = '<select id="gf_ideal_condition_operator" name="_pronamic_pay_gf_condition_operator">';

					$operators = array(
						'' => '',
						Pronamic_WP_Pay_Extensions_GravityForms_GravityForms::OPERATOR_IS     => __( 'is', 'pronamic_ideal' ),
						Pronamic_WP_Pay_Extensions_GravityForms_GravityForms::OPERATOR_IS_NOT => __( 'is not', 'pronamic_ideal' ),
					);

					foreach ( $operators as $value => $label ) {
						$select_operator .= sprintf(
							'<option value="%s" %s>%s</option>',
							esc_attr( $value ),
							selected( $condition_operator, $value, false ),
							esc_html( $label )
						);
					}

					$select_operator .= '</select>';

					// Select value
					$select_value = '<select id="gf_ideal_condition_value" name="_pronamic_pay_gf_condition_value"></select>';

					// Print
					// @codingStandardsIgnoreStart
					printf(
						__( 'Send to gateway if %s %s %s', 'pronamic_ideal' ),
						$select_field,
						$select_operator,
						$select_value
					);
					// @codingStandardsIgnoreEnd

					?>
				</div>

				<div id="gf_ideal_condition_message">
					<span class="description"><?php esc_html_e( 'To create a condition, your form must have a drop down, checkbox or multiple choice field.', 'pronamic_ideal' ); ?></span>
				</div>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Delay', 'pronamic_ideal' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Send Notifications Delay', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				$delay_notification_ids   = get_post_meta( $post_id, '_pronamic_pay_gf_delay_notification_ids', true );
				if ( ! is_array( $delay_notification_ids ) ) {
					$delay_notification_ids = array();
				}

				$delay_admin_notification = get_post_meta( $post_id, '_pronamic_pay_gf_delay_admin_notification', true );
				$delay_user_notification = get_post_meta( $post_id, '_pronamic_pay_gf_delay_user_notification', true );

				?>

				<?php if ( version_compare( GFCommon::$version, '1.7', '>=' ) ) : ?>

					<input name="gf_ideal_selected_notifications_parent" type="checkbox" class="gf_ideal_delay_notifications" value="1" id="gf_ideal_delay_notifications" <?php checked( ! empty( $delay_notification_ids ) ); ?>/>

					<label for="gf_ideal_delay_notifications">
						<?php esc_html_e( 'Send notifications only when payment is received.', 'pronamic_ideal' ); ?>
					</label>

					<div class="pronamic-pay-gf-notifications">

						<?php

						$notifications = array();
						if ( isset( $form_meta['notifications'] ) && is_array( $form_meta['notifications'] ) ) {
							$notifications = $form_meta['notifications'];
						}

						if ( ! empty( $notifications ) ) {
							printf( '<ul>' );

							foreach ( $notifications as $notification ) {
								$id = $notification['id'];

								printf( '<li>' );

								printf(
									'<input id="%s" type="checkbox" value="%s" name="_pronamic_pay_gf_delay_notification_ids[]" %s />',
									esc_attr( 'pronamic-pay-gf-notification-' . $id ),
									esc_attr( $id ),
									checked( in_array( $id, $delay_notification_ids ), true, false )
								);

								printf( ' ' );

								printf(
									'<label class="inline" for="%s">%s</label>',
									esc_attr( 'pronamic-pay-gf-notification-' . $id ),
									esc_html( $notification['name'] )
								);

								printf( '</li>' );
							}

							printf( '</ul>' );
						}

						?>
					</div>

				<?php else : ?>

					<ul>
						<li id="gf_ideal_delay_admin_notification_item">
							<input type="checkbox" name="_pronamic_pay_gf_delay_admin_notification" id="gf_ideal_delay_admin_notification" value="true" <?php checked( $delay_admin_notification ); ?> />

							<label for="gf_ideal_delay_admin_notification">
								<?php esc_html_e( 'Send admin notification only when payment is received.', 'pronamic_ideal' ); ?>
							</label>
						</li>
						<li id="gf_ideal_delay_user_notification_item">
							<input type="checkbox" name="_pronamic_pay_gf_delay_user_notification" id="gf_ideal_delay_user_notification" value="true" <?php checked( $delay_user_notification ); ?> />

							<label for="gf_ideal_delay_user_notification">
								<?php esc_html_e( 'Send user notification only when payment is received.', 'pronamic_ideal' ); ?>
							</label>
						</li>
						<li id="gf_ideal_delay_post_creation_item">

						</li>
					</ul>

				<?php endif; ?>

			</td>
		</tr>
		<tr>
			<?php

			$delay_post_creation = get_post_meta( $post_id, '_pronamic_pay_gf_delay_post_creation', true );

			?>
			<th scope="row">
				<?php esc_html_e( 'Create Post Delay', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<input type="checkbox" name="_pronamic_pay_gf_delay_post_creation" id="_pronamic_pay_gf_delay_post_creation" value="true" <?php checked( $delay_post_creation ); ?> />

				<label for="_pronamic_pay_gf_delay_post_creation">
					<?php esc_html_e( 'Create post only when payment is received.', 'pronamic_ideal' ); ?>
				</label>
			</td>
		</tr>

		<?php if ( class_exists( 'GFAWeber' ) ) : ?>

			<tr>
				<?php

				$delay_aweber_subscription = get_post_meta( $post_id, '_pronamic_pay_gf_delay_aweber_subscription', true );

				?>
				<th scope="row">
					<?php esc_html_e( 'AWeber Subscription Delay', 'pronamic_ideal' ); ?>
				</th>
				<td>
					<input type="checkbox" name="_pronamic_pay_gf_delay_aweber_subscription" id="_pronamic_pay_gf_delay_aweber_subscription" value="true" <?php checked( $delay_aweber_subscription ); ?> />

					<label for="_pronamic_pay_gf_delay_aweber_subscription">
						<?php esc_html_e( 'Subscribe user to AWeber only when payment is received.', 'pronamic_ideal' ); ?>
					</label>
				</td>
			</tr>

		<?php endif; ?>

		<?php if ( class_exists( 'GFCampaignMonitor' ) ) : ?>

			<tr>
				<?php

				$delay_campaignmonitor_subscription = get_post_meta( $post_id, '_pronamic_pay_gf_delay_campaignmonitor_subscription', true );

				?>
				<th scope="row">
					<?php esc_html_e( 'Campaign Monitor Subscription Delay', 'pronamic_ideal' ); ?>
				</th>
				<td>
					<input type="checkbox" name="_pronamic_pay_gf_delay_campaignmonitor_subscription" id="_pronamic_pay_gf_delay_campaignmonitor_subscription" value="true" <?php checked( $delay_campaignmonitor_subscription ); ?> />

					<label for="_pronamic_pay_gf_delay_campaignmonitor_subscription">
						<?php esc_html_e( 'Subscribe user to Campaign Monitor only when payment is received.', 'pronamic_ideal' ); ?>
					</label>
				</td>
			</tr>

		<?php endif; ?>

		<?php if ( class_exists( 'GFMailChimp' ) ) : ?>

			<tr>
				<?php

				$delay_mailchimp_subscription = get_post_meta( $post_id, '_pronamic_pay_gf_delay_mailchimp_subscription', true );

				?>
				<th scope="row">
					<?php esc_html_e( 'MailChimp Subscription Delay', 'pronamic_ideal' ); ?>
				</th>
				<td>
					<input type="checkbox" name="_pronamic_pay_gf_delay_mailchimp_subscription" id="_pronamic_pay_gf_delay_mailchimp_subscription" value="true" <?php checked( $delay_mailchimp_subscription ); ?> />

					<label for="_pronamic_pay_gf_delay_mailchimp_subscription">
						<?php esc_html_e( 'Subscribe user to MailChimp only when payment is received.', 'pronamic_ideal' ); ?>
					</label>
				</td>
			</tr>

		<?php endif; ?>

		<?php if ( class_exists( 'GFZapier' ) ) : ?>

			<tr>
				<?php

				$delay_zapier = get_post_meta( $post_id, '_pronamic_pay_gf_delay_zapier', true );

				?>
				<th scope="row">
					<?php esc_html_e( 'Zapier Delay', 'pronamic_ideal' ); ?>
				</th>
				<td>
					<input type="checkbox" name="_pronamic_pay_gf_delay_zapier" id="_pronamic_pay_gf_delay_zapier" value="true" <?php checked( $delay_zapier ); ?> />

					<label for="_pronamic_pay_gf_delay_zapier">
						<?php esc_html_e( 'Send data to Zapier only when payment is received.', 'pronamic_ideal' ); ?>
					</label>
				</td>
			</tr>

		<?php endif; ?>

		<?php if ( class_exists( 'GFUser' ) ) : ?>

			<tr>
				<?php

				$delay_user_registration = get_post_meta( $post_id, '_pronamic_pay_gf_delay_user_registration', true );

				?>
				<th scope="row">
					<?php esc_html_e( 'User Registration Delay', 'pronamic_ideal' ); ?>
				</th>
				<td>
					<input type="checkbox" name="_pronamic_pay_gf_delay_user_registration" id="_pronamic_pay_gf_delay_user_registration" value="true" <?php checked( $delay_user_registration ); ?> />

					<label for="_pronamic_pay_gf_delay_user_registration">
						<?php esc_html_e( 'Register user only when a payment is received.', 'pronamic_ideal' ); ?>
					</label>
				</td>
			</tr>

		<?php endif; ?>

	</table>

	<h4>
		<?php esc_html_e( 'Fields', 'pronamic_ideal' ); ?>
	</h4>

	<?php

	$fields = array(
		'first_name'       => __( 'First Name', 'pronamic_ideal' ),
		'last_name'        => __( 'Last Name', 'pronamic_ideal' ),
		'email'            => __( 'Email', 'pronamic_ideal' ),
		'address1'         => __( 'Address', 'pronamic_ideal' ),
		'address2'         => __( 'Address 2', 'pronamic_ideal' ),
		'city'             => __( 'City', 'pronamic_ideal' ),
		'state'            => __( 'State', 'pronamic_ideal' ),
		'zip'              => __( 'Zip', 'pronamic_ideal' ),
		'country'          => __( 'Country', 'pronamic_ideal' ),
		'telephone_number' => __( 'Telephone Nnumber', 'pronamic_ideal' ),
	);

	?>

	<table class="form-table">

		<?php foreach ( $fields as $name => $label ) : ?>

			<tr>
				<th scope="row">
					<?php echo esc_html( $label ); ?>
				</th>
				<td>
					<?php

					printf(
						'<select id="%s" name="%s" data-gateway-field-name="%s" class="field-select"><select>',
						esc_attr( 'gf_ideal_fields_' . $name ),
						esc_attr( '_pronamic_pay_gf_fields[' . $name . ']' ),
						esc_attr( $name )
					);

					?>
				</td>
			</tr>

		<?php endforeach; ?>

	</table>

	<h4>
		<?php esc_html_e( 'Status Links', 'pronamic_ideal' ); ?>
	</h4>

	<table class="form-table">
		<?php

		$links = get_post_meta( $post_id, '_pronamic_pay_gf_links', true );
		$links = is_array( $links ) ? $links : array();

		$fields = array(
			Pronamic_WP_Pay_Extensions_GravityForms_Links::OPEN    => __( 'Open', 'pronamic_ideal' ),
			Pronamic_WP_Pay_Extensions_GravityForms_Links::SUCCESS => __( 'Success', 'pronamic_ideal' ),
			Pronamic_WP_Pay_Extensions_GravityForms_Links::CANCEL  => __( 'Cancel', 'pronamic_ideal' ),
			Pronamic_WP_Pay_Extensions_GravityForms_Links::ERROR   => __( 'Error', 'pronamic_ideal' ),
			Pronamic_WP_Pay_Extensions_GravityForms_Links::EXPIRED => __( 'Expired', 'pronamic_ideal' ),
		);

		foreach ( $fields as $name => $label ) : ?>

			<tr>
				<?php

				$type    = null;
				$page_id = null;
				$url     = null;

				if ( isset( $links[ $name ] ) ) {
					$link = $links[ $name ];

					$type            = isset( $link['type'] ) ? $link['type'] : null;
					$confirmation_id = isset( $link['confirmation_id'] ) ? $link['confirmation_id'] : null;
					$page_id         = isset( $link['page_id'] ) ? $link['page_id'] : null;
					$url             = isset( $link['url'] ) ? $link['url'] : null;
				}

				?>
				<th scope="row">
					<label for="gf_ideal_link_<?php echo esc_attr( $name ); ?>_page">
						<?php echo esc_html( $label ); ?>
					</label>
				</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php echo esc_html( $label ); ?></span>
						</legend>

						<label>
							<input type="radio" name="_pronamic_pay_gf_links[<?php echo esc_attr( $name ); ?>][type]" id="gf_ideal_link_<?php echo esc_attr( $name ); ?>_confirmation" value="confirmation" <?php checked( $type, 'confirmation' ); ?> />
							<?php esc_html_e( 'Confirmation:', 'pronamic_ideal' ); ?>
						</label>

						<select name="_pronamic_pay_gf_links[<?php echo esc_attr( $name ); ?>][confirmation_id]" id="gf_ideal_link_<?php echo esc_attr( $name ); ?>_confirmation_id">
							<option><?php esc_html_e( '— Select —', 'pronamic_ideal' ); ?></option>
							<?php

							if ( '' !== $form_id ) {
								$form = GFAPI::get_form( $form_id );

								foreach ( $form['confirmations'] as $key => $confirmation ) {
									printf(
										'<option value="%s" %s>%s</option>',
										esc_attr( $key ),
										selected( $key, $confirmation_id ),
										esc_html( $confirmation['name'] )
									);
								}
							}

							?>
						</select>

						<br />

						<label>
							<input type="radio" name="_pronamic_pay_gf_links[<?php echo esc_attr( $name ); ?>][type]" id="gf_ideal_link_<?php echo esc_attr( $name ); ?>_page" value="page" <?php checked( $type, 'page' ); ?> />
							<?php esc_html_e( 'Page:', 'pronamic_ideal' ); ?>
						</label>

						<?php

						wp_dropdown_pages( array(
							'selected'         => esc_attr( $page_id ),
							'name'             => esc_attr( '_pronamic_pay_gf_links[' . $name . '][page_id]' ),
							'show_option_none' => esc_html__( '— Select —', 'pronamic_ideal' ),
						) );

						?>

						<br />

						<label>
							<input type="radio" name="_pronamic_pay_gf_links[<?php echo esc_attr( $name ); ?>][type]" id="gf_ideal_link_<?php echo esc_attr( $name ); ?>_url" value="url" <?php checked( $type, 'url' ); ?> />
							<?php esc_html_e( 'URL:', 'pronamic_ideal' ); ?>
						</label> <input type="text" name="_pronamic_pay_gf_links[<?php echo esc_attr( $name ); ?>][url]" value="<?php echo esc_attr( $url ); ?>" class="regular-text" />
					</fieldset>
				<td>
			</tr>

		<?php endforeach; ?>
	</table>

	<h4>
		<?php esc_html_e( 'Advanced', 'pronamic_ideal' ); ?>
	</h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="gf_ideal_user_role_field_id">
					<?php esc_html_e( 'Update user role', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<select id="gf_ideal_user_role_field_id" name="_pronamic_pay_gf_user_role_field_id">

				</select>
			</td>
		</tr>
	</table>
</div>
