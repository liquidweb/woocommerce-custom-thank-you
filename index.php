<?php
/**
 * WooCommerce Custom Thank You
 *
 * This WooCommerce extension enables you to set a custom "Thank You" page on a
 * per-product basis. This is handy when you want to send a customer to a page
 * outlining next steps, prompt a customer to sign up for a mailing list, etc.
 *
 * @link                  https://www.liquidweb.com/
 * @package               WC_Custom_Thank_You
 * @author                Liquid Web
 * @license               GPL-3.0
 * @wordpress-plugin
 *
 * Plugin Name:           WooCommerce Custom Thank You
 * Plugin URI:            https://www.liquidweb.com/
 * Description:           A WooCommerce extension that allows you to set a custom "Thank You" page on a per-product basis.
 * Author:                Liquid Web
 * Author URI:            https://www.liquidweb.com/
 * Version:               2.0
 * Text Domain:           wc-custom-thank-you
 * Domain Path:           /languages
 * WC requires at least:  3.0.0
 * WC tested up to:       3.6.0
 */

// Define our constants.
define( 'WOOTHANKS_VERSION', '2.0' );
define( 'WOOTHANKS_URL', plugins_url( '', __FILE__ ) );

// Include only the basics.
require __DIR__ . '/inc/class-lwwoocommercethankyouredir.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/controller.php';

// Start the plugin.
\LWWooCommerceThankYouRedir\LWWooCommerceThankYouRedir::start();
