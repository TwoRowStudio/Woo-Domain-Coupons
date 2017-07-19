=== Woo Domain Coupons ===
Contributors: eric.groft-tworow
Tags: woocommerce,coupons,restricted offers
Requires at least: 3.0
Tested up to: 4.8
Stable tag: 1.00.00
License: GNU GENERAL PUBLIC LICENSE
License URI: http://www.gnu.org/licenses/gpl.html


# Woo-Domain-Coupons
Simple plugin to extend Coupons in WooCommerce to restrict them to a specific domain's email addresses


The plugin adds a panel to the WooCommerce Coupon panel to restrict the coupon to a specific domain and provide a Customer label.
Typical purposes for the plugin is to offer special offers to staff of a specific company or organization. The plugin checks the
coupon being used against both the user's registered email address and the billing email address entered in the checkout form.
Validation of the email address occurs after the checkout to ensure that an email address exists. If the coupon proves to be invalid,
an error message displayed indicating the coupon has been removed and the cart is updated to recalculate all totals so that displayed
cart totals can be adjusted through the standard WooCommerce jQuery and AJAX calls.

== Changelog ==
Version 1.00.00 - approved and released on WordPress.org

Version 0.01.03 - corrects bug that disabled non-domain coupons

== Screenshots ==

1. A view of the modified Coupons Edit screen showing the Domain Coupons section
2. A successful Coupon Application - this will always show initially but will be checked upon submission of payment
3. An example of a rejected domain restricted coupon upon submission
