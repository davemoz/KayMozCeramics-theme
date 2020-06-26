<?php
/**
 * Sort shipping options by cost
 */
add_filter( 'woocommerce_package_rates' , 'kmc_sort_shipping_services_by_cost', 10, 2 );
function kmc_sort_shipping_services_by_cost( $rates, $package ) {
	if ( ! $rates )  return;
	
	$rate_cost = array();
	foreach( $rates as $rate ) {
		$rate_cost[] = $rate->cost;
	}
	
	// using rate_cost, sort rates.
	array_multisort( $rate_cost, $rates );
	
	return $rates;
}

/**
 * Move Single Product tabs below info and pictures
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 18 );

/**
 * Ship to a different address closed by default
 */
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );

/**
 * @snippet       Upsell (Additional donations) Area - WooCommerce Checkout
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.6.1
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
add_action( 'woocommerce_review_order_before_submit', 'kmc_checkout_add_donation', 9999 );
function kmc_checkout_add_donation() {
	$product_ids = array( 442, 443, 444, 445 );
	$in_cart = false;
	foreach( WC()->cart->get_cart() as $cart_item ) {
		$product_in_cart = $cart_item['product_id'];
		if ( in_array( $product_in_cart, $product_ids ) ) {
			$in_cart = true;
			break;
		}
	}
	if ( ! $in_cart ) {
		echo '<div id="donate-more">';
		echo '<h3>Want to donate more?</h3>';
		echo '<p>We donate 15% of all proceeds to <a href="https://www.facebook.com/BethanyHouseNY/" title="Bethany House NY" target="_blank">Bethany House NY</a>, but we want to give you the opportunity to help even more!</p><p><strong>Click a button below if you would like to add an additional donation to your cart.</strong></p><p>100% of the additional amount goes to Bethany House.</p>';
		echo '<div class="donate-buttons"><a class="button" href="?add-to-cart=442"> +$5 </a><a class="button" href="?add-to-cart=443"> +$10 </a><a class="button" href="?add-to-cart=444"> +$20 </a><a class="button" href="?add-to-cart=445"> +$50 </a></div>';
		echo '</div>';
	}
}

/**
 * Update cart contents count on Ajax refresh
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'kmc_header_add_to_cart_fragment' );
function kmc_header_add_to_cart_fragment( $fragments ) {
	ob_start();

	?>
	<span id="cart-count"><?php echo WC()->cart->cart_contents_count;?></span>
	<?php
	$fragments['#cart-count'] = ob_get_clean();
	return $fragments;
}

/**
 * Update free shipping min-amount on Ajax refresh
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'kmc_min_shipping_fragment' );
if( !function_exists( 'kmc_min_shipping_fragment' ) ){
	function kmc_min_shipping_fragment( $fragments ) {

		ob_start();
	
		$cart_count = WC()->cart->cart_contents_count;
		
		if( $cart_count > 0 ) { // we have something in the cart, so let's show the popup

			kmc_messages();

		} else { // empty cart, so no need to show anything
			
			// Maybe a placeholder?
			// kmc_messages_placeholder();
			
		}
		
		$fragments['#shipping-discount'] = ob_get_clean();
		return $fragments;
	
	}
}

/**
 * Adds a "Back to **category**" link on single product pages
 */
function kmc_add_category_link_to_single_products(){
	$terms = get_the_terms( get_the_ID(), 'product_cat' );
	foreach ($terms as $term) {
		if( $term->term_id != '15' || $term->term_id != '51' ){
			$product_cat_id = $term->term_id;
			$link = get_term_link( (int)$product_cat_id, 'product_cat' );
			echo '<div id="product-cat-backlink">';
			echo '<a href="' . $link . '">← Back to '. $term->name .'</a>';
			echo '</div>';
		}
	}
}
add_action( 'woocommerce_before_single_product', 'kmc_add_category_link_to_single_products', 20 );

/**
 * Update cart contents count with Misfits discount
 */
 /*
add_filter( 'woocommerce_add_cart_item_data', 'kmc_add_misfits_discount_item_data' );
function kmc_header_misfits_discount_fragment( $cart_item_data, $product_id, $variation_id ) {
	$category = 'misfit';
    $percent = 25; // Percentage discount rate
    
	// Is this product in the "Misfits" category?
	if ( has_term( $category, 'product_cat', $product_id ) ) {
		$product = wc_get_product( $product_id );
		$regularprice = $product->get_price();
		$misfitPrice = ( ( $regularPrice - $regularPrice * $percent / 100 ) );
		
		// Store the overall price for the product
		$cart_item_data['misfit_price'] = $misfitPrice;
	}
	return $cart_item_data;
}
*/

/**
* @snippet Notice with $$$ remaining to Free Shipping @ WooCommerce Cart
* @how-to Watch tutorial @ https://businessbloomer.com/?p=19055
* @sourcecode https://businessbloomer.com/?p=442
* @author Rodolfo Melogli
* @testedwith WooCommerce 3.4.2
*/
// add_action( 'woocommerce_before_cart', 'kmc_free_shipping_cart_notice_zones', 15 );
function kmc_free_shipping_cart_notice() {
	
	// Get Free Shipping Methods for Rest of the World Zone & populate array $min_amounts 
	$default_zone = new WC_Shipping_Zone(0);
	$default_methods = $default_zone->get_shipping_methods();
	 
	foreach( $default_methods as $key => $value ) {
		if ( $value->id === "free_shipping" ) {
			if ( $value->min_amount > 0 ) $min_amounts[] = $value->min_amount;
		}
	}
	 
	// Get Free Shipping Methods for all other ZONES & populate array $min_amounts
	$delivery_zones = WC_Shipping_Zones::get_zones();
	 
	foreach ( $delivery_zones as $key => $delivery_zone ) {
		foreach ( $delivery_zone['shipping_methods'] as $key => $value ) {
			if ( $value->id === "free_shipping" ) {
				if ( $value->min_amount > 0 ) $min_amounts[] = $value->min_amount;
			}
		}
	}
	
	$calcd_country = WC()->customer->get_shipping_country();
	
	if( $calcd_country == "US" ){

		// Find lowest min_amount
		if ( is_array($min_amounts) ) {
		 
			$min_amount = floatval( min($min_amounts) );
			 
			// Get cart subtotal before tax & shipping
			$current = floatval( WC()->cart->get_subtotal() );

			$remaining = $min_amount - $current;
			 
			// If $current subtotal < $min_amount, echo notice and add "Continue Shopping" button
			if ( $current < $min_amount ) {
				$added_text = esc_html__('Get free shipping on orders of $50 or more!', 'kmc' );
				$return_to = wc_get_page_permalink( 'shop' );
				$notice = sprintf( '<div class="kmc-message good"><div class="message-wrap" id="shipping-discount"><div class="icon-wrap"><i class="fas fa-shipping-fast"></i></div><div class="text-content"><h3>Spend %s more for FREE shipping!</h3><p>%s</p><a href="%s" class="continue-shopping-link">%s</a></div></div></div>', wc_price( $remaining ), $added_text, esc_url( $return_to ), esc_html__( 'Continue Shopping', 'kmc' ) );
				return $notice;
			} else {
				$checkout_url = wc_get_checkout_url();
				$notice = sprintf( '<div class="kmc-message good"><div class="message-wrap" id="shipping-discount"><div class="icon-wrap"><i class="fas fa-check"></i></div><div class="text-content"><h3>You qualify for FREE domestic(US) shipping!</h3><a href="%s" class="checkout-link">%s</a></div></div></div>', esc_url( $checkout_url ), esc_html__( 'Go to checkout', 'kmc' ) );
				return $notice;
			}
	 
		}
	} else {
		$checkout_url = wc_get_checkout_url();
		$notice = sprintf( '<div class="kmc-message good"><div class="message-wrap" id="shipping-discount"><div class="text-content"><h3>Unfortunately, free shipping is not available for international orders.</h3><a href="%s" class="checkout-link">%s</a></div></div></div>', esc_url( $checkout_url ), esc_html__( 'Go to checkout', 'kmc' ) );
		return $notice;
	}
}

/**
 * Adds custom message (above) to cart page
 */
add_action('woocommerce_before_cart', 'kmc_messages', 20);
// add_action('woocommerce_before_checkout_form', 'kmc_messages', 5); // Add the message before the checkout form too?
function kmc_messages() {

	$thebox = kmc_free_shipping_cart_notice();

/*
	// In case we need to add a conditional. ie. if(is_pre_order)
	foreach( WC()->cart->get_cart() as $cart_item )
		if($cart_item['days_manufacture'] > $max_days)
			$max_days = $cart_item['days_manufacture'];
*/
	echo $thebox;
}

/**
 * Shipping alert message on cart
 */
/*
function kmc_shipping_delay_cart_notice() {
	$notice = sprintf( '<div class="kmc-message bad"><div class="message-wrap" id="shipping-delay"><div class="icon-wrap"><i class="fas fa-dolly"></i></div><div class="text-content"><h3>Shipping delay for orders Feb. 15 - 22</h3><p>Any orders placed from 2/15 through 2/22 will be shipped out on 2/24.<br><strong>We apologize for any inconvenience!</strong></p></div></div></div>' );
	return $notice;
}

add_action('woocommerce_before_cart', 'kmc_shipping_message', 10);
function kmc_shipping_message() {

	$thebox = kmc_shipping_delay_cart_notice();

	echo $thebox;
}
*/

/**
 * Calculates actual 25% discount for "Misfits" category products in cart
 */
 /*
add_action( 'woocommerce_cart_calculate_fees', 'kmc_cart_misfits_discount_calc', 50, 1 );
function kmc_cart_misfits_discount_calc( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    ## ------ Settings below ------- ##

	$category = 'misfit';
    $percent = 25; // Percentage discount rate
    $discount_text = __( '"Misfits" discount', 'woocommerce' ); // Discount Text

    ## ----- ----- ----- ----- ----- ##

    $cart_lines_total = $cart->get_subtotal() - $cart->get_discount_total();

	foreach( $cart->get_cart() as $cart_item ){
		// Check if item with "Misfits" category is in cart
		if ( has_term( $category, 'product_cat', $cart_item['product_id'] ) ) {
			$discount_text .=  ' (' . $percent . '%)';
			$discount = $cart_lines_total * $percent / 100;
			WC()->cart->add_fee( $discount_text, -$discount, false );
		}
	}
}
*/

/**
 * Show "Misfits" category 25% discount on cart page
 */
 /*
add_filter( 'woocommerce_cart_item_subtotal', 'kmc_misfits_cart_discount_price', 99, 3 );
function kmc_misfits_cart_discount_price( $subtotal, $cart_item, $cart_item_key ) {
	global $woocommerce;

	$category = 'misfit';
    $percent = 25; // Percentage discount rate

	foreach( $woocommerce->cart->get_cart() as $cart_item ){
		
		// Get an instance of the WC_Product object (or the variation product object)
		$product = $cart_item['data'];
		
		// Check if item with "Misfits" category is in cart
		if ( has_term( $category, 'product_cat', $product->get_id() ) ) {
			$cart_line_total = $product->get_price();
			$discount = $cart_line_total * $percent / 100;
			$misfitPrice = $cart_line_total - $discount;
			if ( $discount ) {
				return '<div class="misfit-price">'. wc_price( $misfitPrice ) .'</div>' . $subtotal;
				$product->set_price( $subtotal );
			} else {
				return $subtotal;
			}
		} else {
			return $subtotal;
		}
	}
}
*/

/**
 * Show "Misfits" category discount products by 25% on main shop page
 */
/*
add_filter( 'woocommerce_get_price_html', 'kmc_misfits_shop_discount_price' );
function kmc_misfits_shop_discount_price( $price ) {
	global $product;
	
	$category = 'misfit';
	
	if ( ! has_term( $category, 'product_cat', $product->get_id() ) ) {
		return $price;
	} else {		
		$percent = 25; // Percentage discount rate
		$regularPrice = $product->get_regular_price();
		
		if ( $product->is_type( 'simple' ) ) {
			$misfitPrice = ( ( $regularPrice - $regularPrice * $percent / 100 ) );
		} elseif ( $product->is_type( 'variable' ) ) {
			$misfitPrice = 0;
			foreach ( $product->get_children() as $child_id ) {
				$variation = wc_get_product( $child_id );
				$price = $variation->get_regular_price();
				if ( $price != 0 ) $misfitPrice = ( ( $regularPrice - $regularPrice * $percent / 100 ) );
			}
		}
		if ( $misfitPrice > 0 ) {
			return '<div class="misfit-price">'. wc_price( $misfitPrice ) .'</div>' . $price;
		} else {
			return $price;
		}
	}
}
*/

/**
 * Add category display option to hide sub-categories when only one product is in that sub-category
 * 
 * @Overrides - woocommerce_output_product_subcategories() in wc-template-functions.php
 */
add_filter( 'woocommerce_product_loop_start', 'kmc_woocommerce_maybe_show_product_subcategories' );
if ( ! function_exists( 'kmc_woocommerce_maybe_show_product_subcategories' ) ) {

	/**
	 * Maybe display categories before, or instead of, a product loop.
	 *
	 * @since 3.3.0
	 * @param string $loop_html HTML.
	 * @return string
	 */
	function kmc_woocommerce_maybe_show_product_subcategories( $loop_html = '' ) {
		if ( wc_get_loop_prop( 'is_shortcode' ) && ! WC_Template_Loader::in_content_filter() ) {
			return $loop_html;
		}

		$display_type = woocommerce_get_loop_display_mode();

		// If displaying categories, append to the loop.
		if ( 'subcategories' === $display_type || 'both' === $display_type ) {
			ob_start();
			kmc_woocommerce_output_product_categories(
				array(
					'parent_id' => is_product_category() ? get_queried_object_id() : 0,
				)
			);
			$loop_html .= ob_get_clean();

			if ( 'subcategories' === $display_type ) {
				wc_set_loop_prop( 'total', 0 );

				// This removes pagination and products from display for themes not using wc_get_loop_prop in their product loops.  @todo Remove in future major version.
				global $wp_query;

				if ( $wp_query->is_main_query() ) {
					$wp_query->post_count    = 0;
					$wp_query->max_num_pages = 0;
				}
			}
		}

		return $loop_html;
	}
}

if ( ! function_exists( 'kmc_woocommerce_output_product_categories' ) ) {
	/**
	 * Display product sub categories as thumbnails.
	 *
	 * This is a replacement for woocommerce_product_subcategories which also does some logic
	 * based on the loop. This function however just outputs when called.
	 *
	 * @since 3.3.1
	 * @param array $args Arguments.
	 * @return boolean
	 */
	function kmc_woocommerce_output_product_categories( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'before'    => apply_filters( 'woocommerce_before_output_product_categories', '' ),
				'after'     => apply_filters( 'woocommerce_after_output_product_categories', '' ),
				'parent_id' => 0,
			)
		);

		$product_categories = woocommerce_get_product_subcategories( $args['parent_id'] );

		if ( ! $product_categories ) {
			return false;
		}

		echo $args['before']; // WPCS: XSS ok.

		foreach ( $product_categories as $category ) {
			$cat_name = get_category( $category )->name;
			$cat_parent = get_category( $category )->parent;
			$cat_count = get_category( $category )->count;
			if( $cat_parent != 0 && $cat_count > 1 ) {
				wc_get_template(
					'content-product_cat.php',
					array(
						'category' => $category,
					)
				);
			}
			elseif( $cat_parent != 0 && $cat_count <= 1 ) {
				echo "Parent cat == true. Prod count <= 1.";
			}
			elseif( $cat_parent == 0 ) {
				echo "Category parent is: " . $cat_parent . ".";
				wc_get_template(
					'content-product_cat.php',
					array(
						'category' => $category,
					)
				);
			}
		}

		echo $args['after']; // WPCS: XSS ok.

		return true;
	}
}

/**
 * Remove Downloads & Pre-Orders from Account nav
 */
add_filter( 'woocommerce_account_menu_items', 'kmc_account_menu_items' );
function kmc_account_menu_items( $items ){
	unset( $items['downloads'] );
	unset( $items['pre-orders' ]);
	return $items;
}

/**
 * Filter out-of-stock product from related products
 */
add_filter( 'woocommerce_related_products', 'kmc_filter_related_products', 10, 1 );
function kmc_filter_related_products( $related_product_ids ) {

	foreach( $related_product_ids as $key => $value ) {
		$relatedProduct = wc_get_product( $value );
		if( ! $relatedProduct->is_in_stock() ) {
			unset( $related_product_ids["$key"] );
		}
	}

	return $related_product_ids;
}