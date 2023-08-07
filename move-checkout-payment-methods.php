<?php
/* -- REARANGE CHECKOUT PAYMENT METHODS BLOCK -- */
add_action( 'woocommerce_checkout_order_review', 'custom_checkout_payment', 8 );
function custom_checkout_payment() {
    if ( WC()->cart->needs_payment() ) {
        $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
        WC()->payment_gateways()->set_current_gateway( $available_gateways );
      } else {
        $available_gateways = array();
      }
      ?>
      <div id="checkout_payments">
        <?php if ( WC()->cart->needs_payment() ) : ?>
        <h4><?php esc_html_e( 'Choose Payment Method', 'porto-child' ); ?></h4>
        <ul class="wc_payment_methods payment_methods methods">
        <?php
        if ( ! empty( $available_gateways ) ) {
          foreach ( $available_gateways as $gateway ) {
            wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
          }
        } else {
          echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
        }
        ?>
        </ul>
      <?php endif; ?>
      </div>
    <?php
}

add_filter( 'woocommerce_update_order_review_fragments', 'my_custom_payment_fragment' );
function my_custom_payment_fragment( $fragments ) {
	ob_start();

	custom_checkout_payment();

	$html = ob_get_clean();

	$fragments['#checkout_payments'] = $html;

	return $fragments;
}
/* -- END REARANGE CHECKOUT PAYMENT METHODS BLOCK -- */