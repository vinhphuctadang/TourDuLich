<?php
global $wte_cart;

$booking_metas  = get_post_meta( $booking, 'wp_travel_engine_booking_setting', true );
$booking_meta = booking_meta_details($booking);
$global_settings = wp_travel_engine_get_settings();
$default_payment_gateway = isset( $global_settings['default_gateway'] ) && ! empty( $global_settings['default_gateway'] ) ? $global_settings['default_gateway'] : 'booking_only';
$user_account_page_id = wp_travel_engine_get_dashboard_page_id();

if($booking_meta['remaining_payment'] <= 0){
	wp_safe_redirect(get_permalink( $user_account_page_id ));
}else{
?>
<div class = "wpte-bf-checkout">
	<table class="wpte-lrf-tables">
		<tr>
			<th><span class="wpte-bf-trip-name"><?php echo $booking_meta['trip_name']; ?></span></th>
			<td>
				<span class="lrf-td-title"><?php _e('Departure', 'wp-travel-engine'); ?></span>
				<span class="lrf-td-desc"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($booking_meta['trip_start_date']))); ?></span>
			</td>
			<td>
				<span class="lrf-td-title"><?php _e('Total', 'wp-travel-engine'); ?></span>
				<span class="lrf-td-desc"><?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol($booking_meta['total_cost']); ?></span>
			</td>
			<td>
				<span class="lrf-td-title"><?php _e('Paid', 'wp-travel-engine'); ?></span>
				<span class="lrf-td-desc"><?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol($booking_meta['total_paid']); ?></span>
			</td>
			<td>
				<span class="lrf-td-title"><?php _e('Due', 'wp-travel-engine'); ?></span>
				<span class="lrf-td-desc"><?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol($booking_meta['remaining_payment']); ?></span>
			</td>
		</tr>
	</table>

	<?php
		$active_payment_methods = wp_travel_engine_get_active_payment_gateways();
		if ( ! empty( $active_payment_methods ) ) :
		?>
		<form id="wp-travel-engine-new-checkout-form" method="POST" name="wp_travel_engine_new_checkout_form" action="" enctype="multipart/form-data" novalidate=""
		class="">
			<div class="wpte-bf-field wpte-bf-radio">
				<label for="" class="wpte-bf-label">
					<?php esc_html_e( 'Payment Method', 'wp-travel-engine' ); ?>
				</label>
				<?php
				foreach( $active_payment_methods as $key => $payment_method ) :
					if( $key == 'booking_only'):
						continue;
					endif;
					?>
						<div class="wpte-bf-radio-wrap">
							<input <?php checked( $default_payment_gateway, $key ); ?> type="radio" name="wpte_checkout_paymnet_method" value="<?php echo esc_attr( $key ); ?>" id="wpte-checkout-paymnet-method-<?php echo esc_attr( $key ); ?>">
							<label for="wpte-checkout-paymnet-method-<?php echo esc_attr( $key ); ?>">
								<?php
									if ( isset( $payment_method['icon_url'] ) && ! empty( $payment_method['icon_url'] ) ) :
								?>
									<img src="<?php echo esc_url( $payment_method['icon_url'] ); ?>" alt="<?php echo esc_attr( $payment_method['label'] ); ?>">
								<?php else :
									echo esc_html( $payment_method['label'] );
								endif; ?>
							</label>
						</div>
				<?php endforeach; ?>
			</div>
		<div class="wpte-bf-field wpte-bf-submit">
			<input type="submit" name="wp_travel_engine_nw_bkg_submit" value="<?php esc_attr_e( 'Pay Now', 'wp-travel-engine' ); ?>">
		</div>
		<?php wp_nonce_field('nonce_checkout_partial_payment_remaining_action', 'nonce_checkout_partial_payment_remaining_field'); ?>
		<input type="hidden" name="currency" value="<?php echo wp_travel_engine_get_currency_code(); ?> ">
		<?php do_action('wte_before_remaining_payment_form_close');?>
	</form>
	<?php do_action('wte_booking_after_checkout_form_close'); ?>
	<?php else: ?>
		<span class="wte-none-available-message wte-error-message">
			<?php echo __('None of the payment method seems to have been setup or enabled at the moment. Please contact the site owner for assist.','wp-travel-engine'); ?>
		</span>
	<?php endif; ?>
</div>
<?php
}
