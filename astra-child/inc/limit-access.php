<?php
/**
 * Lifelong Literacy limit login access to specific IP addresses
 * Capture ip address of active user
 */
function ll_get_client_ip() {
	$ip_headers = array(
		'HTTP_CF_CONNECTING_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'HTTP_CLIENT_IP',
	);

	foreach ($ip_headers as $header) {
		if (isset($_SERVER[$header]) && filter_var($_SERVER[$header], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
			return $_SERVER[$header];
		}
	}

	return $_SERVER['REMOTE_ADDR'];
}

function ll_set_ip_address_from_login( $user_login, WP_User $user ) {
	$user_id = 'user_'. $user->ID;
	$primary_ip = get_field('allowed_ip_address_1', $user_id);
	$secondary_ip = get_field('allowed_ip_address_2', $user_id);
	$ip_address = ll_get_client_ip();

	if ( empty( $primary_ip ) ) {
		update_field( 'allowed_ip_address_1', $ip_address , $user_id );
	} else if ( empty( $secondary_ip ) && $primary_ip != $ip_address ) {
    update_field( 'allowed_ip_address_2', $ip_address , $user_id );
  } else {
    // do nothing
  }

}
add_action( 'wp_login', 'll_set_ip_address_from_login', 10, 2 );

function ll_match_ip_address($userid) {
	$store_ip_address = array();
	$store_ip_string = '';

	if ( !empty($userid ) ) {
		$user_id = 'user_'. $userid;
	} else {
		global $current_user; 
		$user_id = 'user_'. $current_user->ID;
	}
	
	$get_ip_1 = get_field('allowed_ip_address_1', $user_id );
	$get_ip_2 = get_field('allowed_ip_address_2', $user_id );

	if ( !empty($get_ip_1) && isset($get_ip_1) ) {
		$store_ip_address[] = $get_ip_1;
	}

	if ( !empty($get_ip_2) && isset($get_ip_2) && ! in_array( $get_ip_2, $store_ip_address ) ) {
		$store_ip_address[] = $get_ip_2;
	}

	return $store_ip_address;
}

function ll_login_check( WP_User $user ) {
	$user_id = 'user_'. $user->ID;
	$primary_ip = get_field('allowed_ip_address_1', $user_id);
	$secondary_ip = get_field('allowed_ip_address_2', $user_id);
	$get_stored_ip  = ll_match_ip_address($user->ID);
	$current_ip_address = ll_get_client_ip();
	$getuserrole = $user->roles[0];

	if ( empty( $primary_ip ) || empty( $secondary_ip ) ) {
		return $user;
	} else if ( in_array( 'customer', $user->roles ) && in_array( $current_ip_address, $get_stored_ip ) ) {
		return $user;
	} else if ( in_array( 'administrator', $user->roles ) ) {
		return $user;
	} else {
		$message = esc_html__( 'User not verified.', 'lifelong-literacy' );
		return new WP_Error( 'user_not_verified', $message );
	}

  return $user;
}
add_filter( 'wp_authenticate_user', 'll_login_check' );