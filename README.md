# Woo-Domain-Coupons
Initial test release of plugin to extend Coupons in WooCommerce to restrict them to a specific domain's email addresses


The plugin adds a panel to the WooCommerce Coupon panel to restrict the coupon to a specific domain and provide a Customer label.
Typical purposes for the plugin is to offer special offers to staff of a specific company or organization. The plugin checks the
coupon being used against both the user's registered email addres and the billing email address entered in the checkout form.
Validation of the email address occurs after the checkout to ensure that an email address exists. If the coupon proves to be invalid, 
an error message displayed indicating the coupon has been removed and the cart is updated to recalculate all totals so that displayed 
cart totals can be adjusted through the standard WooCommerce jQuery and AJAX calls.
