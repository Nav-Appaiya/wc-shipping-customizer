add_filter( 'woocommerce_package_rates', 'bravo_shipping_customizer', 10, 2 );
 
/**
 * Hide shipping rates when free shipping is available
 *
 * @param array $rates Array of rates found for the package
 * @param array $package The package array/object being shipped
 * @return array of modified rateshow
 * Product IDs:
 * Two Pack Pizza: 14552, Four Pack Pizza: 14553, Six Pack Pizza 14554, Gift Certificate: 1039
 */
function bravo_shipping_customizer( $rates, $package ) {
	global $woocommerce;
	
	$shipping_total = Array();
	
	$cart_items = $woocommerce->cart->cart_contents;
	foreach($cart_items as $key => $item) {
		$item_id = $item['product_id'];
		$qty = $item['quantity'];
		if ($item_id == 14552) {
		  // if is small pack
			$shipping_total[] = 30 * $qty;
		} elseif ( $item_id == 14553 || $item_id == 14554 ){
		  // if is large pack
			$shipping_total[] = 50 * $qty;
		} elseif ( $item_id == 1039 ) {
		  // if is gift certificate
			$shipping_total[] = 8.75; 
		}
	}

  $total = array_sum($shipping_total);
	if ( $total == 8.75 ) {
	  // if is only gift certificates, remove overnight options
		unset($rates['flat_rate:ups-overnight-shipping']);
		unset($rates['table_rate-5 : 6']); 
	} else {
	  // update cost of overnight shipping based on cart contents
		$rates['flat_rate:ups-overnight-shipping']->cost = $total;
	}
  return $rates;
}
