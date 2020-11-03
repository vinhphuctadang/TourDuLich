<?php
/**
 * Purchase Receipt template
 */
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
?>
<div class="wpte-field wpte-text wpte-floated">
	<label class="wpte-field-label" for="wp_travel_engine_settings[email][name]"><?php _e( 'From Name', 'wp-travel-engine' ); ?></label>
		<input type="text" name="wp_travel_engine_settings[email][name]" id="wp_travel_engine_settings[email][name]" 
		value="<?php echo ! empty( $wp_travel_engine_settings['email']['name'] ) ? esc_attr( $wp_travel_engine_settings['email']['name'] ) : esc_attr( get_bloginfo( 'name' ) ); ?>" />
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter the name the purchase receipts are sent from. This should probably be your site or shop name.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-email wpte-floated">
	<label class="wpte-field-label" for="wp_travel_engine_settings[email][from]"><?php _e( 'From Email', 'wp-travel-engine' ); ?></label>
		<input type="text" name="wp_travel_engine_settings[email][from]" id="wp_travel_engine_settings[email][from]" 
		value="<?php echo ! empty( $wp_travel_engine_settings['email']['from'] ) ? esc_attr( $wp_travel_engine_settings['email']['from'] ) : esc_attr( get_option( 'admin_email' ) ); ?>" />
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter the mail address from which the purchase receipts will be sent. This will act as as the from and reply-to address.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-text wpte-floated">
	<label class="wpte-field-label" for="wp_travel_engine_settings[email][subject]"><?php _e( 'Purchase Email Subject', 'wp-travel-engine' ); ?></label>
		<input type="text" name="wp_travel_engine_settings[email][subject]" id="wp_travel_engine_settings[email][subject]" 
		value="<?php echo ! empty( $wp_travel_engine_settings['email']['subject'] ) ? esc_attr( $wp_travel_engine_settings['email']['subject'] ) : esc_attr__( 'Booking Confirmation', 'wp-travel-engine' ); ?>" />
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter the subject line for the purchase receipt email.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-textarea wpte-floated">
	<label class="wpte-field-label" for="purchase_wpeditor"><?php _e( 'Message', 'wp-travel-engine' ); ?></label>
		<?php

		require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-emails.php';

		$email_class   = new WP_Travel_Engine_Emails();
		$value_wysiwyg = $email_class->get_email_template( 'booking', 'customer', true );

		if ( isset( $wp_travel_engine_settings['email']['purchase_wpeditor'] ) && $wp_travel_engine_settings['email']['purchase_wpeditor'] != '' ) {
			$value_wysiwyg = $wp_travel_engine_settings['email']['purchase_wpeditor'];
		}
		$editor_id = 'purchase_wpeditor';
		$settings  = array(
			'media_buttons' => true,
			'textarea_name' => 'wp_travel_engine_settings[email][' . $editor_id . ']',
		);
		?>
		<div class="wpte-field wpte-textarea wpte-floated wpte-rich-textarea delay">
			<textarea 
				placeholder="<?php _e( 'Email Message', 'wp-travel-engine' ); ?>"
				name="wp_travel_engine_settings[email][<?php echo esc_attr( $editor_id ); ?>]"
				class="wte-editor-area wp-editor-area" id="<?php echo esc_attr( $editor_id ); ?>"><?php echo wp_kses_post( $value_wysiwyg ); ?></textarea>
		</div>
</div>

<div class="wpte-field wpte-tags">
	<p><?php _e( 'Enter the text that is sent as purchase receipt email to users after completion of a successful purchase. HTML is accepted.', 'wp-travel-engine' ); ?></p>
	<p><b><?php _e( 'Available Template Tags', 'wp-travel-engine' ); ?>-</b></p>
	<ul class="wpte-list">
		<li>
			<b>{trip_url}</b>
			<span><?php _e( 'The trip URL for each booked trip', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{name}</b>
			<span><?php _e( 'The buyer\'s first name', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{fullname}</b>
			<span><?php _e( 'The buyer\'s full name, first and last', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{user_email}</b>
			<span><?php _e( 'The buyer\'s email address', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{billing_address}</b>
			<span><?php _e( 'The buyer\'s billing address', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{city}</b>
			<span><?php _e( 'The buyer\'s city', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{country}</b>
			<span><?php _e( 'The buyer\'s country', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{tdate}</b>
			<span><?php _e( 'The starting date of the trip', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{date}</b>
			<span><?php _e( 'The trip booking date', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{traveler}</b>
			<span><?php _e( 'The total number of traveler(s)', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{child-traveler}</b>
			<span><?php _e( 'The total number of child traveler(s)', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{tprice}</b>
			<span><?php _e( 'The trip price', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{price}</b>
			<span><?php _e( 'The total payment made of the booking', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{total_cost}</b>
			<span><?php _e( 'The total price of the booking', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{due}</b>
			<span><?php _e( 'The due balance', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{sitename}</b>
			<span><?php _e( 'Your site name', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{booking_url}</b>
			<span><?php _e( 'The trip booking link', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{ip_address}</b>
			<span><?php _e( 'The buyer\'s IP Address', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{booking_id}</b>
			<span><?php _e( 'The booking order ID', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{bank_details}</b>
			<span><?php _e( 'Banks Accounts Details. This tag will be replaced with the bank details and sent to the customer receipt email when Bank Transfer method has chosen by the customer.', 'wp-travel-engine' ); ?></span>
		</li>
		<li>
			<b>{check_payment_instruction}</b>
			<span><?php _e( 'Instructions to make check payment.', 'wp-travel-engine' ); ?></span>
		</li>
	</ul>
	<?php
		/**
		 * Hook to add additional e-mail tags by addons.
		 */
		do_action( 'wte_additional_payment_email_tags' );
	?>
</div>
<?php
