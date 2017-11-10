# WooCommerce Custom Thank You

The **WooCommerce Custom Thank You** extension enables you to do the following things:

* Set a global "Thank You" page to redirect customers to after checkout.
* Set a custom "Thank You" page on a per-product basis.

This is handy when you want to:

* Send a customer to a page outlining your fulfillment process.
* Prompt a customer to sign up for you mailing list.
* Give a customer a list of next steps (installation instructions, course reading, etc.) post-purchase.

## Installation

1. Upload the `wc-custom-thank-you-redirect` directory into the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Set a global "Thank You" page under "WooCommerce -> Settings -> Checkout -> Checkout options -> Checkout pages".

## Setting a custom "Thank You" page

1. Edit an individual product.
2. In the "Product data — Simple product" meta box, enter a page into the "Thank you redirect" field.
3. Update the product.

## Notes

* Setting a *global* or *custom* "Thank You" page overrides the normal "Order received" display post-checkout.
* If a customer has multiple products with multiple *custom* thank you pages in their cart, the *global* default is used.
