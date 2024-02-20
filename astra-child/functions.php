<?php
/**
 * Lifelong Literacy 2 Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Lifelong Literacy 2
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_LIFELONG_LITERACY_2_VERSION', '1.0.36' );
define('CHILD_THEME_LIFELONG_LITERACY_DIR', __DIR__ . '/');

// Add custom learndash shortcode
include get_theme_file_path('/inc/shortcodes.php');

// Limit access to 2 devices only
include get_theme_file_path('/inc/limit-access.php');

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'lifelong-literacy-2-theme-css', get_stylesheet_directory_uri() . '/style.css', array(), time(), 'all' );
	wp_enqueue_style( 'custom-css', get_stylesheet_directory_uri() . '/assets/css/custom.css', array(), time() );

}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

/**
 * Accordion shortcode function for Cart Icon
 */
add_shortcode('ll_cart', 'll_cart_func');
function ll_cart_func($atts){
	$html = '';
	if ( WC()->cart->get_cart_contents_count() > 0 ) {
		$html = do_shortcode('[astra_woo_mini_cart]');
	}

	return $html;
}

/**
 * Change the breakpoint of the Astra Header Menus
 *
 * @return int Screen width when the header should change to the mobile header.
 */
function your_prefix_change_header_breakpoint() {
 return 1150;
};

add_filter( 'astra_header_break_point', 'your_prefix_change_header_breakpoint' );


add_filter( 'woocommerce_checkout_fields' , 'bbloomer_add_email_verification_field_checkout' );

function bbloomer_add_email_verification_field_checkout( $fields ) {

$fields['billing']['billing_email']['class'] = array( 'form-row-first' );

$fields['billing']['billing_em_ver'] = array(
    'label' => 'Confirm email address',
    'required' => true,
    'class' => array( 'form-row-last' ),
    'clear' => true,
    'priority' => 999,
);

return $fields;
}
// Generate error message if field values are different

add_action('woocommerce_checkout_process', 'bbloomer_matching_email_addresses');

function bbloomer_matching_email_addresses() {
    $email1 = $_POST['billing_email'];
    $email2 = $_POST['billing_em_ver'];
    if ( $email2 !== $email1 ) {
        wc_add_notice( 'Your email addresses do not match', 'error' );
    }
}
add_filter( 'woocommerce_widget_cart_is_hidden', '__return_true' );

/** hide captcha */
function conditionally_load_plugin_js_css(){
	if(!is_page(array(4829)) ) { # Only load CSS and JS on needed Pages
		wp_dequeue_script('contact-form-7'); # Restrict scripts.
		wp_dequeue_script('google-recaptcha');
		wp_dequeue_style('contact-form-7'); # Restrict css.
	}}
add_action( 'wp_enqueue_scripts', 'conditionally_load_plugin_js_css' );

/** hide single image zoom */

function remove_image_zoom_support() {
    remove_theme_support( 'wc-product-gallery-zoom' );
}


/* change "sold out"  */
add_filter( 'woocommerce_get_availability', 'wcs_custom_get_availability', 1, 2);
function wcs_custom_get_availability( $availability, $_product ) {

    // Change Out of Stock Text
    if ( ! $_product->is_in_stock() ) {
        $availability['availability'] = __('Registrations closed. Please <a href="https://lifelongliteracy.com/contact-us/">contact us</a> if you&#146;d like to pre-register for the next course.', 'woocommerce');
    }
    return $availability;
}
/**
 * Change "Out Of Stock" text added on WooCommerce Product Grid.
 *
 * @return String
 */
function your_prefix_change_out_stock_string() {
	return __( 'Registrations closed', 'your-text-domain' );
}
add_filter( 'astra_woo_shop_out_of_stock_string', 'your_prefix_change_out_stock_string' );

/* product price shortcode for table */
add_shortcode( 'cl_product_price', 'cl_woo_product_price_shortcode' );
/**
 * Shortcode WooCommerce Product Price.
 *
 */
function cl_woo_product_price_shortcode( $atts ) {

	$atts = shortcode_atts( array(
		'id' => null
	), $atts, 'cl_product_price' );

	if ( empty( $atts[ 'id' ] ) ) {
		return '';
	}

	$product = wc_get_product( $atts['id'] );

	if ( ! $product ) {
		return '';
	}

	return $product->get_price_html();
}
/* add woocommerce category description */
function display_product_category_descriptions(){
  global $product;

  $terms = get_the_terms( $product->get_id(), 'product_cat');

  foreach ($terms as $term) {
    echo '<p>' . $term->description  . '</p>';
  }
}
add_action( 'woocommerce_single_product_summary', 'display_product_category_descriptions', 45);
//add_filter("gform_confirmation_anchor", create_function("","return true;"));




function login_css() { ?>
    <style type="text/css">
		form#language-switcher {
    display: none;
}
        body.login {
			background-image: url(/wp-content/uploads/2021/07/discussing-literature-V5MKDFB-1-scaled.jpg);
			background-position: center center;
			background-size: cover;
		}
		body.login:after {
			content: "";
			background-color: #00ACEF;
			opacity: 0.85;
			transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
			height: 100%;
			width: 100%;
			top: 0;
			left: 0;
			position: absolute;
			z-index: -1;
		}
		.login h1 a {
			background-image: url(/wp-content/uploads/2020/10/Lifelong-Literacy-logo-white.png) !important;
			background-size: contain  !important;
			width: 290px  !important;
			margin: 0 auto 0px !important;
		}
		.login #nav a {
			color: #fff !important;
		}
		.login #nav {
			text-align: center;
			color: #fff !important;
			width:100%;
		}
		p#backtoblog a {
			color: #fff !important;
		}
		p#backtoblog, .login #nav {
			text-align: center;
			color: #fff !important;
			width: 100%;
			margin: 0px !important;
			background: #113d79;
			box-sizing: border-box;
			padding: 0px 0px 2em !important;
		}
		.login form {
			background: #113d79 !important;
			color: #fff;
			width: 100% !important;
			border: none !important;
			padding: 4em 2em !important;
			box-sizing: border-box;
		}
		#login form p.submit {
			margin: 0;
			padding: 0;
			float: none;
			clear: both;
			width: 100%;
			text-align: center !important;
		}
		#login form p.submit input:hover {
			opacity: 0.7;
		}
		#login form p.submit input {
			width: 100%;
			margin-top: 17px;
			background: #009ada;
			border-radius: 50px;
			padding: 0.2em 1em;
			font-size: 21px;
			font-family: "Barlow Condensed", Sans-serif;
			text-transform: uppercase;
			font-weight: 500;
		}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'login_css' );

/**
 * Filter the woocommerce email template path to use our email template
 */
add_filter('woocommerce_locate_template', 'lll_woocommerce_email_locate_template', 9999, 3);
function lll_woocommerce_email_locate_template( $template, $template_name, $template_path ) {
	 $basename = basename( $template );
	 $dir =  CHILD_THEME_LIFELONG_LITERACY_DIR."/templates/";
	 if( $basename == 'customer-invoice.php' || $basename == 'customer-completed-order.php' || $basename == 'customer-processing-order.php') {
		$template =  $dir.'customer-invoice.php';
	 }
	 if( $basename == 'email-order-details.php' ) {
		$template = $dir.'email-order-details.php';
	 }
	 if( $basename == 'email-order-items.php' ) {
	   $template = $dir.'email-order-items.php';
	 }
	 if( $basename == 'email-addresses.php' ) {
	   $template = $dir.'email-addresses.php';
	 }
	 return $template;
}

// Add additional setting to be use in invoice
add_filter('woocommerce_email_settings', 'add_additional_field_email_setting' ,10,1);
function add_additional_field_email_setting( $settings ) {

  $updated_settings = [];

  foreach ( $settings as $section ) {
	if ( isset( $section['id'] ) && 'email_template_options' == $section['id'] &&
	   isset( $section['type'] ) && 'sectionend' == $section['type'] ) {

	  $updated_settings[] = array(
		'name'     => __( 'ABN', 'woocommerce_abn' ),
		'desc_tip' => __( 'ABN ', 'woocommerce_abn' ),
		'id'       => 'woocommerce_abn',
		'type'     => 'textarea',
		'css'      => 'min-width:300px;',
		'default'  => '',

	);
	 $updated_settings[] = array(
		  'name'     => __( 'Invoice Header', 'right_invoice_header' ),
		  'desc_tip' => __( 'Right top Invoice Header Address ', 'right_invoice_header' ),
		  'id'       => 'right_invoice_header',
		  'type'     => 'textarea',
		  'css'      => 'min-width:300px;',
		  'default'  => '',

	  );
	  $updated_settings[] = array(
		 'name'     => __( 'Tax Invoice Title', 'tax_invoice' ),
		 'desc_tip' => __( 'Tax Invoice Title', 'tax_invoice' ),
		 'id'       => 'tax_invoice',
		 'type'     => 'textarea',
		 'css'      => 'min-width:300px;',
		 'default'  => '',

	   );
	  $updated_settings[] = array(
		  'name'     => __( 'Terms', 'woocommerce_terms' ),
		  'desc_tip' => __( 'Terms that will display on the term column of the invoice ', 'woocommerce_terms' ),
		  'id'       => 'woocommerce_terms',
		  'type'     => 'text',
		  'css'      => 'min-width:300px;',
		  'default'  => '',

	  );
	  $updated_settings[] = array(
		  'name'     => __( 'Payment Instruction', 'woocommerce_payment_instuction' ),
		  'desc_tip' => __( 'Payment Instruction', 'woocommerce_payment_instuction' ),
		  'id'       => 'woocommerce_payment_instuction',
		  'type'     => 'textarea',
		  'css'      => 'min-width:300px;',
		  'default'  => '',

	  );

	}
	$updated_settings[] = $section;
  }

  return $updated_settings;
}


add_action( 'learndash_init', function() {
	if ( ! defined( 'K_PATH_FONTS' ) ) {
		$wp_upload_dir = wp_upload_dir();
		$basedir = str_replace( '\\', '/', $wp_upload_dir['basedir'] );
		$ld_tcpdf_fonts_dir = trailingslashit( $basedir ) . 'learndash/tcpdf/fonts/';
		define( 'K_PATH_FONTS', $ld_tcpdf_fonts_dir );
	}
});


// Get Related Products from SAME Sub-category
add_filter( 'woocommerce_product_related_posts', 'lllc_related_products' );
function lllc_related_products($product){
    global $woocommerce;
    // Related products are found from category and tag
    $tags_array = array(0);
    $cats_array = array(0);
    // Get tags
    $terms = wp_get_post_terms($product->id, 'product_tag');
    foreach ( $terms as $term ) $tags_array[] = $term->term_id;
    // Get categories
    $terms = wp_get_post_terms($product->id, 'product_cat');
    foreach ( $terms as $key => $term ){
        $check_for_children = get_categories(array('parent' => $term->term_id, 'taxonomy' => 'product_cat'));
        if(empty($check_for_children)){
            $cats_array[] = $term->term_id;
        }
    }
    // Don't bother if none are set
    if ( sizeof($cats_array)==1 && sizeof($tags_array)==1 ) return array();
    // Meta query
    $meta_query = array();
    $meta_query[] = $woocommerce->query->visibility_meta_query();
    $meta_query[] = $woocommerce->query->stock_status_meta_query();
    $meta_query   = array_filter( $meta_query );
    // Get the posts
    $related_posts = get_posts( array(
            'orderby'        => 'rand',
            'posts_per_page' => $limit,
            'post_type'      => 'product',
            'fields'         => 'ids',
            'meta_query'     => $meta_query,
            'tax_query'      => array(
                'relation'      => 'OR',
                array(
                    'taxonomy'     => 'product_cat',
                    'field'        => 'id',
                    'terms'        => $cats_array
                ),
                array(
                    'taxonomy'     => 'product_tag',
                    'field'        => 'id',
                    'terms'        => $tags_array
                )
            )
        ) );
    $related_posts = array_diff( $related_posts, array( $product->id ), $product->get_upsells() );
    return $related_posts;
}

/**
 * End of Staging Site Codebase
 * 
 * Code Adjustments for Live Site
 */

function custom_text_replace( $translated_text, $untranslated_text, $domain ) {
    switch ( $untranslated_text ) {
        case 'Enrolled':
            $translated_text = __( 'Enrolled', $domain );
            break;

 		case 'Enroll Now':
            $translated_text = __( 'Enrol Now', $domain );
            break;

        default:
            $translated_text = $untranslated_text;
            break;
    }
    return $translated_text;
}
add_filter( 'gettext', 'custom_text_replace', 20, 3 );

// Add hotjar tracking code in specific pages
function hotjar_lms_tracking() {
	if( is_singular( array( 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz', 'sfwd-assignment' ) ) || is_checkout() || is_cart() ) {
?>
<!-- Hotjar Tracking Code for Lifelong Literacy -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:3580719,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
<?php
	}
}
add_action( 'wp_head', 'hotjar_lms_tracking' );

// Add google tools tracking code in specific pages
function google_tools_tracking_code() {
	if ( $_SERVER['SERVER_NAME'] == 'lifelongliteracy.com' ) {
?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-40621179-1"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date()); gtag('config', 'UA-40621179-1');
	</script>

	<!-- Meta Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '1914523631938612');
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=1914523631938612&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Meta Pixel Code -->
<?php
	}
}
add_action( 'wp_head', 'google_tools_tracking_code' );

// Only show files that administator uploaded in the Media Library
function only_show_user_images( $query ) {

	// get all administator ids
	$admins = get_users( array( 'role' => 'administrator' ) );
	$admin_ids = array();
	foreach( $admins as $admin ) {
		$admin_ids[] = $admin->ID;
	}

	if ( $admins ) 
		$query['author__in'] = $admin_ids;
	return $query;

}
add_filter( 'ajax_query_attachments_args', 'only_show_user_images' );

// Callback function for media library protected file indicators AJAX action
function get_custom_field_value_callback() {
	// Check if the request is coming from an authenticated user
	if (!is_user_logged_in()) {
			wp_send_json_error('Authentication required.', 401);
	}

	// Get the post ID from the AJAX request
	$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

	// Get the custom field name from the AJAX request
	$field_name = isset($_POST['field_name']) ? sanitize_text_field($_POST['field_name']) : '';

	// Check if the post ID and field name are valid
	if (empty($post_id) || empty($field_name)) {
			wp_send_json_error('Invalid request parameters.', 400);
	}

	// Get the custom field value for the specified post ID and field name
	$custom_field_value = get_post_meta($post_id, $field_name, true);

	// Send the custom field value as a response
	wp_send_json_success($custom_field_value);
}
add_action('wp_ajax_get_custom_field_value', 'get_custom_field_value_callback');
add_action('wp_ajax_nopriv_get_custom_field_value', 'get_custom_field_value_callback');

function enqueue_custom_media_library_script() {
	wp_enqueue_script('custom-media-library-script', get_stylesheet_directory_uri() . '/assets/js/custom-media-library.js', array('jquery'), time(), true);
}
add_action('admin_enqueue_scripts', 'enqueue_custom_media_library_script', 10);

// load custom admin style and script
function load_admin_style() {
    wp_enqueue_style( 'admin_css', get_stylesheet_directory_uri() . '/assets/css/admin-style.css', false, '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'load_admin_style', 10 );

// Add indicator for protected files on Media Library list view
function custom_media_library_column_content($column_name, $post_ID) {
	if ($column_name === 'custom_class') {
			$custom_field_value = get_post_meta($post_ID, 'access_level', true);
			if ($custom_field_value === 'member') {
					echo '<span class="your-custom-class">Members Only</span>';
			} else {
					echo '<span class="your-custom-class">Public</span>';
			}
	}
}
add_action('manage_media_custom_column', 'custom_media_library_column_content', 10, 2);

// Add Access Level filter column on Media Library list view
function custom_media_library_column_class($posts_columns) {
	$posts_columns['custom_class'] = 'Custom Class';
	return $posts_columns;
}
add_filter('manage_media_columns', 'custom_media_library_column_class');

// Remove Previous and Next Link from Single Post
add_filter( 'astra_single_post_navigation_enabled', '__return_false' );

// Comment form customization
function customize_comment_form_text_area($arg) {
	$arg['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . _x( 'Join the discussion', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="1" aria-required="true"></textarea></p>';
	return $arg;
}
add_filter('comment_form_defaults', 'customize_comment_form_text_area');

// Add US and Canada Price Field on Woocommerce Product
function woocommerce_product_price_by_country() {

		//Custom Product Price Field US
		woocommerce_wp_text_input(
			array(
					'id' => '_custom_product_usd_price',
					'class' => 'wc_input_price_extra_info short',
					'label' => __('Price in USA', 'woocommerce'),
					'type' => 'number',
					'custom_attributes' => array(
							'step' => 'any',
							'min' => '0',
							'required' => 'required'
					)
			)
		);
		woocommerce_wp_text_input(
			array(
					'id' => '_custom_product_cad_price',
					'class' => 'wc_input_price_extra_info short',
					'label' => __('Price in Canada', 'woocommerce'),
					'type' => 'number',
					'custom_attributes' => array(
							'step' => 'any',
							'min' => '0',
							'required' => 'required'
					)
			)
		);

		woocommerce_wp_text_input(
			array(
					'id' => '_custom_product_euro_price',
					'class' => 'wc_input_price_extra_info short',
					'label' => __('Price in Euro', 'woocommerce'),
					'type' => 'number',
					'custom_attributes' => array(
							'step' => 'any',
							'min' => '0',
							'required' => 'required'
					)
			)
		);

		woocommerce_wp_text_input(
			array(
					'id' => '_custom_product_gbp_price',
					'class' => 'wc_input_price_extra_info short',
					'label' => __('Price in GBP', 'woocommerce'),
					'type' => 'number',
					'custom_attributes' => array(
							'step' => 'any',
							'min' => '0',
							'required' => 'required'
					)
			)
		);

}
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_price_by_country');

// Save Fields
function woocommerce_product_price_by_country_save($post_id) {

		// Save Custom Meta Price Field
		$woocommerce_custom_product_usd_price = $_POST['_custom_product_usd_price'];
		if (!empty($woocommerce_custom_product_usd_price))
				update_post_meta($post_id, '_custom_product_usd_price', esc_attr($woocommerce_custom_product_usd_price)
		);

		$woocommerce_custom_product_cad_price = $_POST['_custom_product_cad_price'];
		if (!empty($woocommerce_custom_product_cad_price))
				update_post_meta($post_id, '_custom_product_cad_price', esc_attr($woocommerce_custom_product_cad_price)
		);

		$woocommerce_custom_product_euro_price = $_POST['_custom_product_euro_price'];
		if (!empty($woocommerce_custom_product_euro_price))
				update_post_meta($post_id, '_custom_product_euro_price', esc_attr($woocommerce_custom_product_euro_price)
		);

		$woocommerce_custom_product_gbp_price = $_POST['_custom_product_gbp_price'];
		if (!empty($woocommerce_custom_product_gbp_price))
				update_post_meta($post_id, '_custom_product_gbp_price', esc_attr($woocommerce_custom_product_gbp_price)
		);

}
add_action('woocommerce_process_product_meta', 'woocommerce_product_price_by_country_save');

// Overide the product price by country 
function bbloomer_alter_price_display( $price_html, $product ) {

		if ( is_admin() ) return $price_html;

		// Get an instance of the WC_Geolocation object class
		$geo_instance  = new WC_Geolocation();
		// Get geolocated user geo data.
		$user_geodata = $geo_instance->geolocate_ip();
		// Get current user GeoIP Country
		$country = $user_geodata['country'];

		global $post;
		$product = wc_get_product($post->ID);

		// Get US and Canada Price
		$woocommerce_product_usd_price = $product->get_meta('_custom_product_usd_price');
		$woocommerce_product_cad_price = $product->get_meta('_custom_product_cad_price');
		$woocommerce_product_euro_price = $product->get_meta('_custom_product_euro_price');
		$woocommerce_product_gbp_price = $product->get_meta('_custom_product_gbp_price');

		$tax_rates = WC_Tax::get_rates( $product->get_tax_class() );
		$tax_rate = reset($tax_rates); // first element of the array or the standard rate

		switch ($country) {
			case 'AU':
				$price_html = $product->get_price() + ( $product->get_price() * ( $tax_rate['rate'] / 100 ) );
				break;
			case 'NZ':
				$price_html = $product->get_price();
				break;
			case 'CA':
				$price_html = $woocommerce_product_cad_price;
				break;
			case 'GB':
				$price_html = $woocommerce_product_gbp_price;
				break;
			case 'AT':
			case 'BE':
			case 'HR':
			case 'CY':
			case 'EE':
			case 'FI':
			case 'FR':
			case 'DE':
			case 'GR':
			case 'IE':
			case 'IT':
			case 'LV':
			case 'LT':
			case 'LU':
			case 'MT':
			case 'NL':
			case 'PT':
			case 'SK':
			case 'SI':
			case 'ES':
				$price_html = $woocommerce_product_euro_price;
				break;
			default:
				$price_html = $woocommerce_product_usd_price;
		}
			
		return wc_price($price_html);

}
add_filter( 'woocommerce_get_price_html', 'bbloomer_alter_price_display', 9999, 2 );

// Alter Product Pricing Cart/Checkout
function ll_alter_price_cart( $cart ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

		// Get an instance of the WC_Geolocation object class
		$geo_instance  = new WC_Geolocation();
		// Get geolocated user geo data.
		$user_geodata = $geo_instance->geolocate_ip();
		// Get current user GeoIP Country
		$country_code = $user_geodata['country'];

		// check if woocommerce session is set before calling member function
		global $woocommerce;
		$session = $woocommerce->session;

		// if session is set, get billing country from session
		$country = ( $session ) ? $woocommerce->customer->get_billing_country() : $country_code;

		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
			$product = $cart_item['data'];

			switch ($country) {
				case 'AU':
					$price = $product->get_price();
					break;
				case 'NZ':
					$price = $product->get_price();
					break;
				case 'CA':
					$price = $product->get_meta('_custom_product_cad_price');
					break;
				case 'GB':
					$price = $product->get_meta('_custom_product_gbp_price');
				case 'AT':
				case 'BE':
				case 'HR':
				case 'CY':
				case 'EE':
				case 'FI':
				case 'FR':
				case 'DE':
				case 'GR':
				case 'IE':
				case 'IT':
				case 'LV':
				case 'LT':
				case 'LU':
				case 'MT':
				case 'NL':
				case 'PT':
				case 'SK':
				case 'SI':
				case 'ES':
					$price = $product->get_meta('_custom_product_euro_price');
					break;
				default:
					$price = $product->get_meta('_custom_product_usd_price');
			}
			
			$cart_item['data']->set_price( $price );
			
		}
		
}
add_action( 'woocommerce_before_calculate_totals', 'll_alter_price_cart', 15 );

// Get one free product if a specific product is in the cart
function ll_buy_one_get_one_sale() {

	// get all product ids in the cart and check if the required product is in the cart
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

		$required_products = array( 9188, 2397, 5328, 8695 );
		// get all product ids in the cart
		$product_ids[] = $cart_item['product_id'];
		// get all the required product in the cart
		$required_product_in_cart = array_intersect( $required_products, $product_ids );
		// get only the first item of required product in cart
		$required_product_in_cart = array_slice( $required_product_in_cart, 0, 1 );

	}

	// if the required product is in the cart, set the price of the free product to 0
	if ( !empty( $required_product_in_cart ) ) {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

			// remove the required product from the free product array
			$free_product = array_diff( $required_products, $required_product_in_cart );

			// get all product ids in the cart
			foreach ( $free_product as $free_product_id ) {
				// check if the free product is in the cart
				if ( $cart_item['product_id'] == $free_product_id ) {
					// only set one free product to 0
					if ( !isset( $done ) ) {
						$cart_item['data']->set_price( 0 );
						$done = true;
					}

					// check if promo code is applied
					if( count( WC()->cart->get_applied_coupons() ) > 0 ) {
						// remove applied promo codes
						foreach ( WC()->cart->get_applied_coupons() as $code ) {
							WC()->cart->remove_coupon( $code );
						}
						
					}

				}

			}

		}
	}

}
// add_action( 'woocommerce_before_calculate_totals', 'll_buy_one_get_one_sale', 999 );

// Get one free product if a specific product is in the cart
function group_b_buy_one_get_one_sale() {

	// get all product ids in the cart and check if the required product is in the cart
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

		$required_products = array( 25632, 12237, 25634, 12126, 11977, 12040, 11866, 9500 );
		
		// get all product ids in the cart
		$product_ids[] = $cart_item['product_id'];
		// get all the required product in the cart
		$required_product_in_cart = array_intersect( $required_products, $product_ids );
		// get only the first item of required product in cart
		$required_product_in_cart = array_slice( $required_product_in_cart, 0, 1 );

	}

	// if the required product is in the cart, set the price of the free product to 0
	if ( !empty( $required_product_in_cart ) ) {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

			// remove the required product from the free product array
			$free_product = array_diff( $required_products, $required_product_in_cart );

			// get all product ids in the cart
			foreach ( $free_product as $free_product_id ) {
				// check if the free product is in the cart
				if ( $cart_item['product_id'] == $free_product_id ) {
					// only set one free product to 0
					if ( !isset( $done ) ) {
						$cart_item['data']->set_price( 0 );
						$done = true;
					}

					// check if promo code is applied
					if( count( WC()->cart->get_applied_coupons() ) > 0 ) {
						// remove applied promo codes
						foreach ( WC()->cart->get_applied_coupons() as $code ) {
							WC()->cart->remove_coupon( $code );
						}
						
					}

				}

			}

		}
	}

}
// add_action( 'woocommerce_before_calculate_totals', 'group_b_buy_one_get_one_sale', 998 );

// Get one free product if a specific product is in the cart
function group_c_buy_one_get_one_sale() {

	// get all product ids in the cart and check if the required product is in the cart
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		
		$required_products = array( 46739, 47314, 38554, 33075, 37259 );

		// get all product ids in the cart
		$product_ids[] = $cart_item['product_id'];
		// get all the required product in the cart
		$required_product_in_cart = array_intersect( $required_products, $product_ids );
		// get only the first item of required product in cart
		$required_product_in_cart = array_slice( $required_product_in_cart, 0, 1 );

	}

	// if the required product is in the cart, set the price of the free product to 0
	if ( !empty( $required_product_in_cart ) ) {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

			// remove the required product from the free product array
			$free_product = array_diff( $required_products, $required_product_in_cart );

			// get all product ids in the cart
			foreach ( $free_product as $free_product_id ) {
				// check if the free product is in the cart
				if ( $cart_item['product_id'] == $free_product_id ) {
					// only set one free product to 0
					if ( !isset( $done ) ) {
						$cart_item['data']->set_price( 0 );
						$done = true;
					}

					// check if promo code is applied
					if( count( WC()->cart->get_applied_coupons() ) > 0 ) {
						// remove applied promo codes
						foreach ( WC()->cart->get_applied_coupons() as $code ) {
							WC()->cart->remove_coupon( $code );
						}
						
					}
					
				}

			}

		}
	}

}
// add_action( 'woocommerce_before_calculate_totals', 'group_c_buy_one_get_one_sale', 997 );

// Change currency based on billing country
function change_woocommerce_currency( $currency ) {

		if ( ( is_admin() && ! defined( 'DOING_AJAX' ) ) ) return $currency;

		// check if woocommerce session is set before calling member function
		global $woocommerce;
		$session = $woocommerce->session;

		// Get an instance of the WC_Geolocation object class
		$geo_instance  = new WC_Geolocation();
		// Get geolocated user geo data.
		$user_geodata = $geo_instance->geolocate_ip();
		// Get current user GeoIP Country
		$country_code = $user_geodata['country'];

		// if in checkout page, get billing country from session
		if ( is_checkout() || is_cart() ) {
			$country = ( $session ) ? $woocommerce->customer->get_billing_country() : $country_code;
		} else {
			$country = $country_code;
		}
		
		switch ($country) {
			case 'AU':
				$currency = 'AUD';
				break;
			case 'NZ':
				$currency = 'AUD';
				break;
			case 'CA':
				$currency = 'CAD';
				break;
			case 'GB':
				$currency = 'GBP';
				break;
			case 'AT':
			case 'BE':
			case 'HR':
			case 'CY':
			case 'EE':
			case 'FI':
			case 'FR':
			case 'DE':
			case 'GR':
			case 'IE':
			case 'IT':
			case 'LV':
			case 'LT':
			case 'LU':
			case 'MT':
			case 'NL':
			case 'PT':
			case 'SK':
			case 'SI':
			case 'ES':
				$currency = 'EUR';
				break;
			default:
				$currency = 'USD';
		}
		return $currency;

}
add_filter( 'woocommerce_currency', 'change_woocommerce_currency', 10, 2 );

// Change a currency symbol
function change_existing_currency_symbol( $currency_symbol, $currency ) {
		switch( $currency ) {
			case 'AUD':
				$currency_symbol = 'A$';
				break;
			case 'CAD':
				$currency_symbol = 'CA$';
				break;
			case 'USD':
				$currency_symbol = 'US$';
				break;
			case 'GBP':
				$currency_symbol = 'GBP£';
				break;
			case 'EUR':
				$currency_symbol = 'EUR€';
				break;
		}
		return $currency_symbol;
}
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

function astra_masthead_custom_nav_menu_items( $items, $args ) {

	if ( isset( $args->theme_location ) && ! astra_get_option( 'header-display-outside-menu' ) ) {

		if ( 'above_header_menu' === $args->theme_location ) {

			if ( is_user_logged_in() ) {

				$items .= '<li id="menu-item-27350" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-27350"><a href="' . esc_url(home_url( '/my-courses')) . '" class="menu-link">My courses</a></li>';
				$items .= '<li id="menu-item-647" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-647"><a href="' . esc_url(home_url( '/my-account')) . '" class="menu-link">My account</a></li>';
				$items .= '<li id="menu-item-18255" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-18255"><a href="' . wp_logout_url( home_url() ) . '" class="menu-link">Logout</a></li>';

			} else {
				
				$items .= '<li id="menu-item-18254" class="btn-login menu-item menu-item-type-custom menu-item-object-custom menu-item-18254"><a href="' . esc_url(home_url( '/login')) . '" class="menu-link">Login</a></li>';

			}
		}
	}

	return $items;
}


// Add Event product type on Simple Products 
add_filter( 'product_type_options', 'add_event_date_product_option' );
function add_event_date_product_option( $product_type_options ) {
	$product_type_options['event'] = array(
			'id'            => '_event',
			'wrapper_class' => 'show_if_simple',
			'label'         => __( 'Event', 'woocommerce' ),
			'description'   => __( '', 'woocommerce' ),
			'default'       => 'no'
	);
	return $product_type_options;

}

// Save Event product type on Simple Products
add_action( 'woocommerce_process_product_meta_simple', 'save_event_option_fields' );
add_action( 'woocommerce_process_product_meta_variable', 'save_event_option_fields' );
function save_event_option_fields( $post_id ) {
	$is_event = isset( $_POST['_event'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_event', $is_event );

	// if _event is no, delete post meta _custom_product_event_date
	if ( $is_event == 'no' ) {
		delete_post_meta( $post_id, '_custom_product_event_date' );
	}

}

// Add custom field to product
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_event_date');
function woocommerce_product_event_date() {
	global $post;
	$product_id = $post->ID;
	$product_type = WC_Product_Factory::get_product_type($product_id);

	// Only for simple products
	if ( $product_type == 'simple' ) {

	//Custom Product Future Date Field
	woocommerce_wp_text_input(
		array(
				'wrapper_class' => 'show_if_event show_if_simple',
				'id' => '_custom_product_event_date',
				'class' => 'wc_input_price_extra_info short',
				'label' => __('Event Date', 'woocommerce'),
				'type' => 'date'

		)
	);

	?>
	<script>
		jQuery( document ).ready( function( $ ) {
			$( 'input#_event' ).change( function() {
				var is_event_date = $( 'input#_event:checked' ).size();

				$( '.show_if_event' ).hide();
				$( '.hide_if_event' ).hide();

				if ( is_event_date ) {
					$( '.hide_if_event' ).hide();
				}
				if ( is_event_date ) {
					$( '.show_if_event' ).show();
				}
			});
			$( 'input#_event' ).trigger( 'change' );
		});
	</script>
	<?php
		;
	}

}

// Save custom field to product
add_action('woocommerce_process_product_meta', 'woocommerce_product_event_date_save');
function woocommerce_product_event_date_save($post_id) {

	// Save Custom Meta Price Field
	$woocommerce_custom_product_event_date = $_POST['_custom_product_event_date'];
	if (!empty($woocommerce_custom_product_event_date))
			update_post_meta($post_id, '_custom_product_event_date', esc_attr($woocommerce_custom_product_event_date)
	);

}

// set the order status to completed and send downloadable links to customer email after payment is completed for downloadable products
add_action( 'woocommerce_payment_complete', 'wc_downloadable_product_type_email_notification', 10, 1 );
function wc_downloadable_product_type_email_notification( $order_id ) {
    if ( ! $order_id ) return;

    $order = wc_get_order( $order_id );

		// check payment method is stripe and stripe-cc
		$payment_method = $order->get_payment_method();

		if ( $payment_method == 'stripe' || $payment_method == 'stripe_cc' ) {
			// check if product is downloadable
			$downloadable = false;
			foreach ( $order->get_items() as $item ) {
				$product = $item->get_product();
				if ( $product->is_downloadable() ) {
					$downloadable = true;
				}
			}

			if ( $downloadable ) {
				$order->update_status( 'completed' );
				error_log( 'downloadable product and payment method is stripe' );
			}
		}


}

