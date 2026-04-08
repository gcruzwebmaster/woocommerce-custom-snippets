<?php
/**
 * WooCommerce Custom Snippets
 * By Grace Cruz — gracecruz.net
 */

// 1. Remove WooCommerce bloat from non-shop pages
function remove_wc_scripts_non_shop() {
    if (!is_woocommerce() && !is_cart() && !is_checkout()) {
        wp_dequeue_style('woocommerce-general');
        wp_dequeue_style('woocommerce-layout');
        wp_dequeue_style('woocommerce-smallscreen');
    }
}
add_action('wp_enqueue_scripts', 'remove_wc_scripts_non_shop', 99);

// 2. Change number of products per row
add_filter('loop_shop_columns', function() { return 4; });

// 3. Change number of products per page
add_filter('loop_shop_per_page', function() { return 12; }, 20);

// 4. Add custom text after Add to Cart button
add_action('woocommerce_after_add_to_cart_button', function() {
    echo '<p class="secure-checkout">🔒 Secure Checkout</p>';
});

// 5. Auto-complete virtual/downloadable orders
add_action('woocommerce_payment_complete', function($order_id) {
    $order = wc_get_order($order_id);
    $virtual = true;
    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        if (!$product->is_virtual() && !$product->is_downloadable()) {
            $virtual = false;
            break;
        }
    }
    if ($virtual) $order->update_status('completed');
});
