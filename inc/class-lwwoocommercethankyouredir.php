<?php
/**
 * This is where all of the magic happens.
 *
 * @package WC_Custom_Thank_You
 * @author  Liquid Web
 * @license GPL-3.0
 * @since   1.0.0
 */

namespace LWWooCommerceThankYouRedir;

/**
 * Just the one class. Don't bother to looking for any others.
 */
class LWWooCommerceThankYouRedir {

	const META_KEY                  = '_thank_you_redirect';
	const META_KEY_GLOBAL_THANK_YOU = 'woocommerce_custom_thankyou_page_id';
	const LABEL_INPUT_NAME          = 'product-thank-you-label';
	const ID_INPUT_NAME             = 'product-thank-you';
	const PLUGIN_ID                 = 'WooCommerce Custom Thank You';

	private $status_check;

	/**
	 * Kickstart the plugin.
	 *
	 * @return void
	 */
	public static function start() {
		new LWWooCommerceThankYouRedir();
	}

	/**
	 * Add all necessary action hooks.
	 */
	private function __construct() {
		// The field.
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_product_general_tab_field' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_general_tab_field' ) );

		// The auto hinting.
		add_action( 'admin_enqueue_scripts', array( $this, 'include_javascript' ) );
		add_action( 'wp_ajax_wc-thank-you-hint', array( $this, 'hint_thank_you_pages' ) );

		// Custom thank you page handling after checkout.
		add_action( 'woocommerce_thankyou', array( $this, 'redirect_thank_you_page' ) );
		add_filter( 'woocommerce_payment_gateways_settings', array( $this, 'custom_thank_you_page' ) );
	}

	/**
	 * Custom "thank you" page option.
	 * Setting can be found under "WooCommerce > Settings > Checkout".
	 *
	 * @param array $settings Stored settings.
	 */
	public function custom_thank_you_page( $settings ) {

		// Loop our settings to add our own.
		foreach ( $settings as $key => $value ) {

			// Add our key check.
			if ( 10 === $key ) {
				$settings[ $key ] = array(
					'title'    => __( 'Thank you page', 'woocommerce' ),
					'desc'     => __( 'Add a custom, global thank you page to redirect to after the checkout process is complete.', 'woocommerce' ),
					'id'       => 'woocommerce_custom_thankyou_page_id',
					'type'     => 'single_select_page',
					'default'  => '',
					'class'    => 'wc-enhanced-select-nostd',
					'css'      => 'min-width:300px;',
					'desc_tip' => true,
				);
			}

			// If our key is greater, add one to be safe.
			if ( $key >= 10 ) {
				$settings[ $key + 1 ] = $value;
			}
		}

		// Return the settings array.
		return $settings;
	}

	/**
	 * Includes the thank you hinting JS file on single edit pages.
	 * Gives you the unminified version if `SCRIPT_DEBUG` is set to 'true'.
	 *
	 * @access public
	 * @param  string $hook Page hook.
	 */
	public function include_javascript( $hook ) {

		// Bail if not on admin or our function doesnt exist.
		if ( ! is_admin() || ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		// Get my current screen.
		$screen = get_current_screen();

		// Bail without.
		if ( empty( $screen ) || ! is_object( $screen ) ) {
			return;
		}

		// Make sure we are on the single product editor.
		if ( 'post' !== $screen->base || 'product' !== $screen->post_type ) {
			return;
		}

		// Set our minified check and version number.
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$ver = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : WOOTHANKS_VERSION;

		// Load our actual JS file.
		wp_enqueue_script( 'lw_wc_thank_you_redirect-hinting', WOOTHANKS_URL . "/assets/js/thank-you{$min}.js", array( 'jquery', 'jquery-ui-autocomplete' ), $ver, true );
	}

	/**
	 * Prints out the page hints in JSON format.
	 */
	public function hint_thank_you_pages() {

		// Set our search param.
		$search = isset( $_REQUEST['search'] ) ? $_REQUEST['search'] : '';

		// Set our query args.
		$query = new \WP_Query(
			array(
				'post_type'      => 'page',
				'posts_per_page' => 15,
				's'              => $search,
			)
		);

		// Bail if we have no posts.
		if ( is_wp_error( $query ) || empty( $query->posts ) ) {
			return false;
		}

		// Set an empty for the result array.
		$result = array();

		// Now loop my results.
		foreach ( $query->posts as $post ) {
			$result[] = array(
				'label' => $post->post_title,
				'value' => $post->ID,
			);
		}

		// Return our results, JSON encoded.
		echo wp_json_encode(
			array(
				'success' => true,
				'data'    => $result,
			)
		);

		// And die.
		die();
	}

	/**
	 * Sets up the text input field for selecting the thank you page.
	 */
	public function add_product_general_tab_field() {
		global $post;

		$label_field_value = '';
		$id_field_value    = '';

		$meta_value = get_post_meta( $post->ID, self::META_KEY, true );

		if ( ! empty( $meta_value ) ) {
			if ( 0 !== (int) $meta_value ) {
				$id_field_value    = $meta_value;
				$label_field_value = get_the_title( (int) $meta_value );
			} else {
				$label_field_value = $meta_value;
			}
		}

		echo '<div class="options_group">';
			woocommerce_wp_text_input(
				array(
					'placeholder' => __( 'Type to see avaliable pages...', 'lw_wc_thank_you_redirect' ),
					'id'          => self::LABEL_INPUT_NAME,
					'label'       => __( 'Thank you redirect', 'lw_wc_thank_you_redirect' ),
					'value'       => $label_field_value,
				)
			);
			woocommerce_wp_hidden_input(
				array(
					'id'    => self::ID_INPUT_NAME,
					'value' => $id_field_value,
				)
			);

		echo '</div>';
	}

	/**
	 * Saves the contents of the custom thank you page field.
	 *
	 * @param int $id Post ID.
	 */
	public function save_product_general_tab_field( $id ) {

		// Throw an error if the data wasn't saved.
		if ( ! isset( $_REQUEST[ self::ID_INPUT_NAME ] ) || ! isset( $_REQUEST[ self::LABEL_INPUT_NAME ] ) ) {
			new \WP_Error( 'Necessary field values are not present' );
			return;
		}

		// Set our page and label.
		$thank_you_page       = sanitize_text_field( wp_unslash( $_REQUEST[ self::ID_INPUT_NAME ] ) );
		$thank_you_page_label = trim( sanitize_text_field( wp_unslash( $_REQUEST[ self::LABEL_INPUT_NAME ] ) ) );

		if ( 0 === strpos( $thank_you_page_label, 'http' ) ) {
			update_post_meta( $id, self::META_KEY, $thank_you_page_label );
		} elseif ( ! empty( $thank_you_page ) ) {
			update_post_meta( $id, self::META_KEY, $thank_you_page );
		} else {
			update_post_meta( $id, self::META_KEY, '' );
		}
	}

	/**
	 * Redirects to the selected thank you page, if one has been set.
	 *
	 * @param int $order_id Order ID.
	 */
	public function redirect_thank_you_page( $order_id ) {

		// Get our order and the items in it.
		$order = wc_get_order( $order_id );
		$items = $order->get_items();

		// Bail without items (which should not happen).
		if ( empty( $items ) || 0 === count( $items ) ) {
			return;
		}

		// Get our fallback global option.
		$fallback = get_option( self::META_KEY_GLOBAL_THANK_YOU, false );

		// If we have more than 1 item in the order, do the fallback or nothing.
		if ( ! empty( $fallback ) && count( $items ) > 1 ) {

			// Set our page to redirect.
			$page = get_permalink( (int) $fallback );

			// And redirect.
			wp_safe_redirect( $page );
			exit;
		}

		// If we only have 1 item in the order, check for a custom page.
		if ( count( $items ) === 1 ) {

			// Get the array keys to begin checking.
			$keys = array_keys( $items );

			// Check for the meta key.
			$meta = get_post_meta( $items[ $keys[0] ]['product_id'], self::META_KEY, true );

			// If no meta exists, and no fallback was set, bail.
			if ( empty( $meta ) && empty( $fallback ) ) {
				return;
			}

			// Set our page to redirect.
			$page = ! empty( $meta ) ? get_permalink( (int) $meta ) : get_permalink( (int) $fallback );

			// And redirect.
			wp_safe_redirect( $page );
			exit;
		}

		// Nothing left to do, so do nothing.
	}

	// End our class.
}
