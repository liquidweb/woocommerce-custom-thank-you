<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $attr_arr['order'] ) ) {
		$order             = $attr_arr['order'];
	$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
	if ( $show_customer_details ) {
		wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
	}
} else { ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo esc_attr( apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ) ); ?></p>

	<?php } ?>
