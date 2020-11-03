<?php
	$wrapper_classes = apply_filters( 'wpte_bf_outer_wrapper_classes', '' );
?>
<div class="wpte-bf-outer <?php echo esc_attr( $wrapper_classes ); ?>">
	<!-- Prices List -->
	<?php do_action( 'wte_before_price_info' ); ?>
	<div class="wpte-bf-price-wrap">
		<?php do_action( 'wte_before_price_info_title' ); ?>
		<div class="wpte-bf-ptitle"><?php _e( 'Price From', 'wp-travel-engine' ); ?></div>
		<?php do_action( 'wte_after_price_info_title' ); ?>

		<div class="wpte-bf-price">
		<?php if ( $is_sale_price_enabled ) : ?>
			<del>
				<?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $regular_price ); ?>
			</del>
		<?php endif; ?>
			<ins>
				<?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $price ); ?></b>
			</ins>
			<?php
				$per_person_txt_out = 'per-person' === $price_per_text ? __( 'Per Person', 'wp-travel-engine' ) : __( 'Per Group', 'wp-travel-engine' );
			?>
			<span class="wpte-bf-pqty"><?php echo apply_filters( 'wte_default_traveller_unit', $per_person_txt_out ); ?></span>
		</div>

		<?php do_action( 'wte_after_price_info_list' ); ?>
	</div>
	<?php do_action( 'wte_after_price_info' ); ?>
	<!-- ./ Prices List -->


	<!-- Booking Form -->
	<?php do_action( 'wte_before_tip_booking_form' ); ?>
	<form id="wpte-booking-form" method="POST" class="price-holder" autocomplete="off" action="<?php echo esc_url( get_permalink( $wte_placeholder ) ); ?>">
		<?php wp_nonce_field( 'wp_travel_engine_booking_nonce', 'nonce' ); ?>

		<!-- Booking steps -->
		<div class="wpte-bf-booking-steps">
			<div class="wpte-bf-step-wrap">
			<?php
			$first_el = 0;
			foreach ( $booking_steps as $index => $booking_step ) :
				?>
				<button data-step-name="wpte-bf-step-<?php echo esc_attr( $index ); ?>" class="wpte-bf-step<?php echo esc_attr( 0 === $first_el ? ' active' : '' ); ?>">
					<?php echo esc_html( $booking_step ); ?>
				</button>
				<span class="wpte-bf-step-arrow">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M24.707 38.101L4.908 57.899c-4.686 4.686-4.686 12.284 0 16.971L185.607 256 4.908 437.13c-4.686 4.686-4.686 12.284 0 16.971L24.707 473.9c4.686 4.686 12.284 4.686 16.971 0l209.414-209.414c4.686-4.686 4.686-12.284 0-16.971L41.678 38.101c-4.687-4.687-12.285-4.687-16.971 0z"></path></svg>
				</span>
				<?php
				$first_el++;
			endforeach;
			?>
			</div>


			<?php do_action( 'wte_before_booking_steps_content' ); ?>
			<div class="wpte-bf-step-content-wrap">
				<!-- Calender -->
				<div class="wpte-bf-step-content active">
					<div class="wpte-bf-datepicker"></div>
				</div>
				<!-- ./ Calender -->

				<!-- Travellers -->
				<?php
				do_action( 'wte_before_travellers_booking_step' );

					$min_pax = isset( $post_meta['trip_minimum_pax'] ) && ! empty( $post_meta['trip_minimum_pax'] ) ? $post_meta['trip_minimum_pax'] : 1;

					$max_pax = isset( $post_meta['trip_maximum_pax'] ) && ! empty( $post_meta['trip_maximum_pax'] ) ? $post_meta['trip_maximum_pax'] : 99999999999999;
				?>
				<div class="wpte-bf-step-content wpte-bf-content-travellers" data-mintravellers="<?php echo esc_attr( $min_pax ); ?>" data-maxtravellers="<?php echo esc_attr( $max_pax ); ?>">
					<div class="wpte-bf-traveler-block-wrap">
						<div class="wpte-bf-block-title"><?php _e( 'Add Travelers', 'wp-travel-engine' ); ?></div>
						<div class="wpte-bf-traveler-member">
							<?php do_action( 'wte_bf_travellers_input_fields' ); ?>
						</div>
					</div>
				</div>
				<?php do_action( 'wte_after_travellers_booking_step' ); ?>

				<div class="wte-bf-price-detail" style="display: none">
					<div class="wpte-bf-total-price">
						<span class="wpte-bf-total-txt"><?php _e( 'Total', 'wp-travel-engine' ); ?> :</span>
						<!-- <span class="wpte-bf-currency">
							<?php echo wp_travel_engine_get_currency_code(); ?>
						</span>
						<span class="wpte-bf-price-amt">
							<?php echo wp_travel_engine_get_formated_price_separator( $price ); ?>
						</span> -->
						<?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $price ); ?>
					</div>
					<div class="wpte-bf-toggle-wrap wpte-bf-animate-toggle">
						<button class="wpte-bf-toggle-title">
							<span><?php _e( 'View Cost Detail', 'wp-travel-engine' ); ?></span>
						</button>
						<div class="wpte-bf-toggle-content">
							<table class="wpte-bf-travellers-price-table">
								<caption><?php _e( 'Travellers', 'wp-travel-engine' ); ?></caption>
								<tbody>
									<tr>
										<td>1 <?php echo apply_filters( 'wte_default_traveller_type', __( 'Person', 'wp-travel-engine' ) ); ?> <span class="wpte-bf-info">(<?php echo wp_travel_engine_get_formated_price_with_currency_symbol( $price ); ?>/<?php echo apply_filters( 'wte_default_traveller_type', __( 'Person', 'wp-travel-engine' ) ); ?>)</span></td>
										<td><?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $price ); ?></td>
									</tr>
								</tbody>
							</table>
							<?php do_action( 'wte_before_trip_price_total' ); ?>
							<div class="wpte-bf-total">
								<?php _e( 'Total', 'wp-travel-engine' ); ?>: <b><?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $price ); ?></b>
							</div>
							<?php do_action( 'wte_after_trip_price_total' ); ?>
						</div><!-- .wpte-bf-toggle-content -->
					</div><!-- .wpte-bf-toggle-wrap -->
					<div class="wpte-bf-btn-wrap">
						<input type="button" name=""
							value="<?php _e( 'Continue', 'wp-travel-engine' ); ?>" class="wpte-bf-btn" />
					</div>
				</div>
			</div>
		</div>
		<?php
			$global_settings   = wp_travel_engine_get_settings();
			$hide_enquiry_form = isset( $global_settings['enquiry'] ) && $global_settings['enquiry'] != '' ? true : false;

		if ( ! $hide_enquiry_form ) :
			?>
				<div class="wpte-bf-help-block">
				<?php esc_html_e( 'Need Help With Booking?', 'wp-travel-engine' ); ?>
					<a href="#wte_enquiry_contact_form" id="wte-send-enquiry-message">
					<?php esc_html_e( 'Send Us A Message', 'wp-travel-engine' ); ?>
					</a>
				</div>
			<?php
			endif;
		?>
		<!-- ./ Travellers -->
		<?php do_action( 'wte_after_booking_steps_content' ); ?>

	</form>
	<?php do_action( 'wte_after_tip_booking_form' ); ?>
	<!-- ./ Booking Form -->
</div>
<?php
