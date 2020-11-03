<?php
/**
 * Email Template and functions.
 *
 * @package WP_Travel_Engine
 */
/**
 * WP Travel Engine Emails.
 */
class WP_Travel_Engine_Emails {

	/**
	 * Class Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Get email template headers.
	 */
	public function get_email_template( $type, $sent_to, $content_only = false, $data = false ) {
		$strings = array(
			'heading'         => __( 'New Booking', 'wp-travel-engine' ),
			'greeting'        => __( 'Dear Admin,', 'wp-travel-engine' ),
			'greeting_byline' => __( 'A new booking has been made on your website. Booking details are listed below.', 'wp-travel-engine' ),
		);
		if ( $sent_to === 'customer' ) {
			$strings = array(
				'heading'         => __( 'Booking Confirmation', 'wp-travel-engine' ),
				'greeting'        => __( 'Dear {name},', 'wp-travel-engine' ),
				'greeting_byline' => __( 'You have successfully made the trip booking. Your booking information is listed below.', 'wp-travel-engine' ),
			);
		}
		$args = array(
			'sent_to' => $sent_to,
			'strings' => $strings,
		);

		if ( $data ) {
			$args['form_data'] = $data;
		}

		ob_start();
		if ( $content_only ) {

			// Email Content.
			wte_get_template( "emails/{$type}.php", $args );

			$template = ob_get_clean();
			return $template;
		}
			// Get email Header.
			wte_get_template( 'emails/email-header.php' );

				// Email Content.
				wte_get_template( "emails/{$type}.php", $args );

			// Get email footer.
			wte_get_template( 'emails/email-footer.php' );
		$template = ob_get_clean();
		return $template;
	}

	/**
	 * Get Email header.
	 *
	 * @return void
	 */
	public function get_email_header() {
		ob_start();
		 // Get email Header.
		wte_get_template( 'emails/email-header.php' );

		$template = ob_get_clean();
		return $template;
	}

	/**
	 * Get Email Footer.
	 *
	 * @return void
	 */
	public function get_email_footer() {
		ob_start();
		// Get email Header.
		wte_get_template( 'emails/email-footer.php' );

		$template = ob_get_clean();
		return $template;
	}

	/**
	 * Send emails.
	 */
	public function send_booking_emails( $order_details, $booking_id ) {

		if ( ! $booking_id ) {
			return false;
		}

		// get cartdata.
		global $wte_cart;

		// cart items array.
		$cart_items     = $wte_cart->getItems();
		$totals         = $wte_cart->get_total();
		$cart_discounts = $wte_cart->get_discounts();
		$total          = $totals['total'];
		$trip_ids       = $wte_cart->get_cart_trip_ids();
		$trip_id        = $trip_ids['0'];
		$trip_name      = get_the_title( $trip_id );
		$trip_link      = '<a href=' . esc_url( get_permalink( $trip_id ) ) . '>' . esc_html( $trip_name ) . '</a>';

		// get settings.
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );

		$obj           = new Wp_Travel_Engine_Functions();
		$code          = isset( $wp_travel_engine_settings['currency_code'] ) ? $wp_travel_engine_settings['currency_code'] : 'USD';
		$currency_sign = $obj->wp_travel_engine_currencies_symbol( $code );

		// Define variables.
		$booking_url      = get_edit_post_link( $booking_id );
		$booking_url_link = '#<a href="' . esc_url( $booking_url ) . '">' . $booking_id . '</a>';
		$fullname         = $order_details['place_order']['booking']['fname'] . ' ' . $order_details['place_order']['booking']['lname'];
		$sitename         = get_bloginfo( 'name' );
		$city             = $order_details['place_order']['booking']['city'];

		foreach ( $cart_items as $key => $cart_item ) :
			$traveller       = array_sum( $cart_item['pax'] );
			$child_traveller = isset( $cart_item['pax']['child'] ) ? $cart_item['pax']['child'] : '';

			if ( isset( $cart_item['multi_pricing_used'] ) && $cart_item['multi_pricing_used'] ) :
				$multiple_pricing_html = '';
				foreach ( $cart_item['pax'] as $pax_key => $pax ) :
					if ( '0' == $pax || empty( $pax ) ) {
						continue;
					}
					$pax_label              = wte_get_pricing_label_by_key_invoices( $cart_item['trip_id'], $pax_key, $pax );
					$per_pricing_price      = ( $cart_item['pax_cost'][ $pax_key ] / $pax );
					$multiple_pricing_html .= '<p>' . $pax_label . ': ' . $pax . 'X ' . wp_travel_engine_get_formated_price_with_currency( $per_pricing_price, null, true ) . '</p>';
				endforeach;
				$traveller = $multiple_pricing_html;
			endif;
		endforeach;

		// Mapping Mail tags with values.
		$default_mail_tags = array(
			'{trip_url}'                  => $trip_link,
			'{name}'                      => $order_details['place_order']['booking']['fname'],
			'{fullname}'                  => $fullname,
			'{user_email}'                => $order_details['place_order']['booking']['email'],
			'{billing_address}'           => $order_details['place_order']['booking']['address'],
			'{city}'                      => $city,
			'{country}'                   => $order_details['place_order']['booking']['country'],
			'{tdate}'                     => $order_details['place_order']['datetime'],
			'{traveler}'                  => $traveller,
			'{child-traveler}'            => $child_traveller,
			'{tprice}'                    => wp_travel_engine_get_formated_price_with_currency( wp_travel_engine_get_actual_trip_price( $trip_id, true ), null, true ),
			'{price}'                     => wp_travel_engine_get_formated_price_with_currency( $order_details['place_order']['cost'], null, true ),
			'{total_cost}'                => wp_travel_engine_get_formated_price_with_currency( $total, null, true ),
			'{due}'                       => wp_travel_engine_get_formated_price_with_currency( $order_details['place_order']['due'], null, true ),
			'{sitename}'                  => $sitename,
			'{booking_url}'               => $booking_url,
			'{ip_address}'                => '',
			'{date}'                      => date( 'Y-m-d H:i:s' ),
			'{booking_id}'                => sprintf( __( 'Booking id: #%1$s', 'wp-travel-engine' ), $booking_id ),
			'{bank_details}'              => '',
			'{check_payment_instruction}' => '',
		);

		// Bank Details.
		if ( isset( $_REQUEST['wpte_checkout_paymnet_method'] ) && 'direct_bank_transfer' === $_REQUEST['wpte_checkout_paymnet_method'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended 
			$bank_details_labels = array(
				// 'account_name',
				'account_number' => __( 'Account Number', 'wp-travel-engine' ),
				'bank_name'      => __( 'Bank Name', 'wp-travel-engine' ),
				'sort_code'      => __( 'Sort Code', 'wp-travel-engine' ),
				'iban'           => __( 'IBAN', 'wp-travel-engine' ),
				'swift'          => __( 'BIC/Swift', 'wp-travel-engine' ),
			);

			$bank_accounts = isset( $wp_travel_engine_settings['bank_transfer']['accounts'] ) && is_array( $wp_travel_engine_settings['bank_transfer']['accounts'] ) ? $wp_travel_engine_settings['bank_transfer']['accounts'] : array();
			ob_start();
			echo '<table class="invoice-items">';
			echo '<tr>';
			echo '<td colspan="2">';
			echo '<h3>' . esc_html__( 'Bank Details:', 'wp-travel-engine' ) . '</h3>';
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td colspan="2">';
			echo isset( $wp_travel_engine_settings['bank_transfer']['instruction'] ) ? wp_kses_post( $wp_travel_engine_settings['bank_transfer']['instruction'] ) : '';
			echo '</td>';
			echo '</tr>';
			foreach ( $bank_accounts as $account ) {
				echo '<tr>';
				echo '<td colspan="2">';
				echo '<h5>' . esc_html( $account['account_name'] ) . '</h5>';
				echo '</td>';
				echo '</tr>';
				foreach ( $bank_details_labels as $key => $label ) {
					?>
					<tr>
						<td><?php echo esc_html( $label ); ?></td>
						<td class="alignright"><?php echo isset( $account[ $key ] ) ? esc_html( $account[ $key ] ) : ''; ?></td>
					</tr>
					<?php
				}
			}
			echo '</table>';
			$default_mail_tags['{bank_details}'] = ob_get_clean();
		}

		// Check Payment Instructions.
		if ( isset( $_REQUEST['wpte_checkout_paymnet_method'] ) && 'check_payments' === $_REQUEST['wpte_checkout_paymnet_method'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended 
			ob_start();
			?>
			<table class="invoice-items">
				<tr>
					<td colspan="2">
						<h3><?php echo esc_html__( 'Check Payment Instructions:', 'wp-travel-engine' ); ?></h3>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php echo isset( $wp_travel_engine_settings['check_payment']['instruction'] ) ? wp_kses_post( $wp_travel_engine_settings['check_payment']['instruction'] ) : ''; ?>
					</td>
				</tr>
			</table>
			<?php
			$default_mail_tags['{check_payment_instruction}'] = ob_get_clean();
		}

		// Prepare client emails emails.
		$customer_email_template_content = $this->get_email_template( 'booking', 'customer', true );
		$admin_email_template_content    = $this->get_email_template( 'booking', 'admin', true );

		if ( isset( $wp_travel_engine_settings['email']['purchase_wpeditor'] ) && $wp_travel_engine_settings['email']['purchase_wpeditor'] != '' ) {
			$customer_email_template_content = wp_kses_post( $wp_travel_engine_settings['email']['purchase_wpeditor'] );
		}

		if ( isset( $wp_travel_engine_settings['email']['sales_wpeditor'] ) && $wp_travel_engine_settings['email']['sales_wpeditor'] != '' ) {
			$admin_email_template_content = wp_kses_post( $wp_travel_engine_settings['email']['sales_wpeditor'] );
		}

		// Prepare customer email template.
		$customer_email_template  = $this->get_email_header();
		$customer_email_template .= $customer_email_template_content;
		$customer_email_template .= $this->get_email_footer();

		// Prepare admin email template.
		$admin_email_template  = $this->get_email_header();
		$admin_email_template .= $admin_email_template_content;
		$admin_email_template .= $this->get_email_footer();

		$customer_email_template = str_replace( array_keys( $default_mail_tags ), $default_mail_tags, $customer_email_template );
		$admin_email_template    = str_replace( array_keys( $default_mail_tags ), $default_mail_tags, $admin_email_template );

		$booking_extra_fields = isset( $order_details['additional_fields'] ) && is_array( $order_details['additional_fields'] ) ? $order_details['additional_fields'] : array();

		if ( ! empty( $booking_extra_fields ) ) {
			$booking_mappable_array = array();
			foreach ( $booking_extra_fields as $key => $value ) {
				$new_key                            = '{' . $key . '}';
				$booking_mappable_array[ $new_key ] = $value;
			}

			$customer_email_template = str_replace( array_keys( $booking_mappable_array ), $booking_mappable_array, $customer_email_template );

			$admin_email_template = str_replace( array_keys( $booking_mappable_array ), $booking_mappable_array, $admin_email_template );
		}

		/** For Discount */
		$cart_discounts_mappable_array = array();
		if ( ! empty( $cart_discounts ) ) {
			foreach ( $cart_discounts as $key => $value ) {
				foreach ( $value as $k => $v ) {
					$new_key                                   = '{discount_' . $k . '}';
					$cart_discounts_mappable_array[ $new_key ] = $v;
				}
				/** Actual discount Amount */
				if ( $value['type'] == 'percentage' ) {
					$percentage_discount_amount                         = number_format( ( ( wp_travel_engine_get_actual_trip_price( $trip_id ) * $value['value'] ) / 100 ), '2', '.', '' );
					$cart_discounts_mappable_array['{discount_amount}'] = wp_travel_engine_get_formated_price_with_currency( $percentage_discount_amount );
					$cart_discounts_mappable_array['{discount_sign}']   = '%';
				} else {
					 $cart_discounts_mappable_array['{discount_amount}'] = wp_travel_engine_get_formated_price_with_currency( $value['value'] );
					 $cart_discounts_mappable_array['{discount_sign}']   = $currency_sign;
				}
			}
		} else {
			$cart_discounts_mappable_array = array(
				'{discount_name}'   => '',
				'{discount_amount}' => '',
				'{discount_sign}'   => '',
				'{discount_value}'  => '',
			);
		}

		$customer_email_template = str_replace( array_keys( $cart_discounts_mappable_array ), $cart_discounts_mappable_array, $customer_email_template );
		$admin_email_template    = str_replace( array_keys( $cart_discounts_mappable_array ), $cart_discounts_mappable_array, $admin_email_template );

		/** End For Discount */

		$customer_from_name = get_bloginfo( 'name' );
		if ( isset( $wp_travel_engine_settings['email']['name'] ) && $wp_travel_engine_settings['email']['name'] != '' ) {
			$customer_from_name = $wp_travel_engine_settings['email']['name'];
		}

		$customer_from_email = get_option( 'admin_email' );
		if ( isset( $wp_travel_engine_settings['email']['from'] ) && $wp_travel_engine_settings['email']['from'] != '' ) {
			$customer_from_email = $wp_travel_engine_settings['email']['from'];
		}

		$subject_receipt = __( 'Booking Confirmation', 'wp-travel-engine' );
		if ( isset( $wp_travel_engine_settings['email']['subject'] ) && $wp_travel_engine_settings['email']['subject'] != '' ) {
			$subject_receipt = $wp_travel_engine_settings['email']['subject'];
		}

		$customer_headers  = 'MIME-Version: 1.0' . "\r\n";
		$charset           = apply_filters( 'wp_travel_engine_mail_charset', 'Content-type: text/html; charset=UTF-8' );
		$customer_headers .= $charset . "\r\n";
		$from_receipt      = $customer_from_name . ' <' . $customer_from_email . '>';
		$customer_headers .= 'From:' . $from_receipt . "\r\n" .
			'Reply-To: ' . $from_receipt . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		/**
		 * Purchase reciept contents filter.
		 */
		$customer_email_template = apply_filters( 'wte_purchase_reciept_email_content', $customer_email_template, $booking_id );

		// Send email to customer.
		wp_mail( $order_details['place_order']['booking']['email'], $subject_receipt, $customer_email_template, $customer_headers );

		// Prepare emails to Admin.

		// Add support for Attachments.
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$uploadedfile = $_FILES;
		$attachments  = array();
		foreach ( $uploadedfile as $key => $file ) {
			$upload_file = wp_handle_upload( $file, array( 'test_form' => false ) );
			if ( $upload_file && ! isset( $upload_file['error'] ) ) {
				$attachments[ $key ] = $upload_file['file'];
			}
		}

		// Mail for Admin
		if ( isset( $wp_travel_engine_settings['email']['sale_subject'] ) && $wp_travel_engine_settings['email']['sale_subject'] != '' ) {
			$subject_book = esc_attr( $wp_travel_engine_settings['email']['sale_subject'] );
		}
		$subject_book = 'New Booking Order #' . $booking_id;
		$from_book    = $customer_from_name . ' <' . $customer_from_email . '>';

		// To send HTML mail, the Content-type header must be set
		$headers_book  = 'MIME-Version: 1.0' . "\r\n";
		$charset       = apply_filters( 'wp_travel_engine_mail_charset', 'Content-type: text/html; charset=UTF-8' );
		$headers_book .= $charset . "\r\n";

		// Create email headers
		$headers_book .= 'From: ' . $from_book . "\r\n" .
			'Reply-To: ' . $from_receipt . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		/**
		 * Book reciept contents filter.
		 */
		$admin_email_template = apply_filters( 'wte_booking_reciept_email_content', $admin_email_template, $booking_id );

		if ( ! isset( $wp_travel_engine_settings['email']['disable_notif'] ) || $wp_travel_engine_settings['email']['disable_notif'] != '1' ) {
			if ( strpos( $wp_travel_engine_settings['email']['emails'], ',' ) !== false ) {
				$wp_travel_engine_settings['email']['emails'] = str_replace( ' ', '', $wp_travel_engine_settings['email']['emails'] );
				$admin_emails                                 = explode( ',', $wp_travel_engine_settings['email']['emails'] );
				foreach ( $admin_emails as $key => $value ) {
					$a = 1;
					wp_mail( $value, $subject_book, $admin_email_template, $headers_book, $attachments );
				}
			} else {
				$wp_travel_engine_settings['email']['emails'] = str_replace( ' ', '', $wp_travel_engine_settings['email']['emails'] );
				wp_mail( $wp_travel_engine_settings['email']['emails'], $subject_book, $admin_email_template, $headers_book, $attachments );
			}
		}
	}
}
