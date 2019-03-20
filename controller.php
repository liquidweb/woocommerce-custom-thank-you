<?php
/**
 * WCTYController
 */
class WCTYController {

	public function __construct() {
		add_shortcode( 'woo_order_details', array( $this, 'woo_order_details_action' ) );
		add_shortcode( 'woo_order_stub', array( $this, 'woo_order_stub_action' ) );
		add_shortcode( 'woo_order_table', array( $this, 'woo_order_table_action' ) );
		add_shortcode( 'woo_customer_details', array( $this, 'woo_customer_details_action' ) );
	}

	public function woo_order_details_action() {
		if ( isset( $_REQUEST['action'] ) && isset( $_REQUEST['order_id'] ) && isset( $_REQUEST['hash'] ) ) {

			if ( 'thank_you_page' === $_REQUEST['action'] && (int) $_REQUEST['order_id'] > 0 ) {
				$hask_key = get_transient( 'wcty_order_' . sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) );
				if ( ! empty( $hask_key ) && $hask_key === $_REQUEST['hash'] ) {
					$order = wc_get_order( sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) );
					return wcty_view( 'thankyou', array( 'order' => $order ) );
				}
			}
		}

	}

	public function woo_order_stub_action() {
		if ( isset( $_REQUEST['action'] ) && isset( $_REQUEST['order_id'] ) && isset( $_REQUEST['hash'] ) ) {

			if ( 'thank_you_page' === $_REQUEST['action'] && (int) $_REQUEST['order_id'] > 0 ) {
				$hask_key = get_transient( 'wcty_order_' . sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) );
				if ( ! empty( $hask_key ) && $hask_key === $_REQUEST['hash'] ) {
					$order = wc_get_order( sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) );
					return wcty_view( 'order-stub', array( 'order' => $order ) );
				}
			}
		}

	}

	public function woo_order_table_action() {
		if ( isset( $_REQUEST['action'] ) && isset( $_REQUEST['order_id'] ) && isset( $_REQUEST['hash'] ) ) {

			if ( 'thank_you_page' === $_REQUEST['action'] && (int) $_REQUEST['order_id'] > 0 ) {
				$hask_key = get_transient( 'wcty_order_' . sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) );
				if ( ! empty( $hask_key ) && $hask_key === $_REQUEST['hash'] ) {
					$order = wc_get_order( sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) );
					return wcty_view( 'order-table', array( 'order' => $order ) );
				}
			}
		}

	}

	public function woo_customer_details_action() {
		if ( isset( $_REQUEST['action'] ) && isset( $_REQUEST['order_id'] ) && isset( $_REQUEST['hash'] ) ) {

			if ( 'thank_you_page' === $_REQUEST['action'] && (int) $_REQUEST['order_id'] > 0 ) {
				$hask_key = get_transient( 'wcty_order_' . sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) );
				if ( ! empty( $hask_key ) && $hask_key === $_REQUEST['hash'] ) {
					$order = wc_get_order( sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) );
					return wcty_view( 'customer-details', array( 'order' => $order ) );
				}
			}
		}

	}
}
new WCTYController();
