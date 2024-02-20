<?php
/**
 * LearnDash shortcode
 *
 */

 // Lessons navigation to add on elementor, so we can move the grey message box to the bottom of the page
function course_lessons_navigation() {
  $course_id = learndash_get_course_id();
  $user = wp_get_current_user();

  $shortcode_out = do_shortcode( '[ld_navigation course_id="' . $course_id . '" user_id="' . $user->ID . '" post_id="' . get_the_ID() . '"]' );

  return $shortcode_out;
}
add_shortcode( 'lessons_navigation', 'course_lessons_navigation' );

// Users enrolled courses shortcode to add on elementor page builder
function users_enrolled_courses() {

	$user_id = get_current_user_id();
  $courses = learndash_user_get_enrolled_courses( $user_id, array(), true );
	array_push($courses, 0); // Passing an empty array to post__in will return has_posts() as true, add element to the array for workaround
  $output = '';


	$args = array(
		'post_type' => array('sfwd-courses'),
		'post_status' => 'publish',
		'post__in' =>  $courses,
		'posts_per_page' => -1
	);
  $enrolled = new WP_Query( $args );
	if ( $enrolled->have_posts() ) {

    while ( $enrolled->have_posts() ) {
		$enrolled->the_post();
		$course_id = get_the_ID();
		$course_progress = learndash_user_get_course_progress( $user_id, $course_id, '' );
		$course_completed = learndash_course_completed( $user_id,  $course_id );

		if ( $course_completed ) {
			$course_progress_status = 'Completed';
			$course_progress_summary = 'Completed ' . $course_progress['summary']['completed'] . ' Lessons';
		} else {
			$course_progress_status = ( $course_progress['summary']['status'] == 'in_progress' ) ? 'Continue' : 'Start Course';
			$course_progress_summary = 'In Progress';
		}


		$featured_img = ( has_post_thumbnail( $course_id ) ) ? get_the_post_thumbnail( $course_id, 'full', array( 'class' => 'img-fit', 'alt' => esc_attr( get_the_title() ), 'loading' => 'lazy' ) ) : '<img class="img-fit" src="https://lifelongliteracy.com/wp-content/uploads/2021/10/LandPen-2.jpeg" alt="Placeholder" loading="lazy" />';

		$output .= '<div class="enrolled_courses__items">' . $featured_img . '<div class="enrolled_courses__items__content"><h5><a href="' . get_permalink() . '">' . get_the_title() . '</a></h5><span class="enrolled_courses__items__content__status">' . $course_progress_summary . '</span><progress class="enrolled_courses__items__content__progress-bar" value="' . $course_progress['summary']['completed'] . '" max="' . $course_progress['summary']['total'] . '"></progress><a class="enrolled_courses__items__content__button" href="' . get_permalink() . '">' . $course_progress_status . '</a></div></div>';


    }

  } else {

		$output = '<p>You have no enrolled courses</p>';

	}
  /* Restore original Post Data */
  wp_reset_postdata();

	return '<div class="enrolled_courses__wrapper">' . $output . '</div>';

}
add_shortcode('enrolled_courses', 'users_enrolled_courses');

// event listings from Woocommerce products category events
function event_listings_from_woocommerce_product() {
	// Initialize the output
	$output = '';

	// Get all published products in the category "ticket"
	$args = array(
			'limit' => 5,
			'status' => 'publish',
			'category' => array( 'ticket' ),
			'meta_key' => '_custom_product_event_date',
			'orderby' => 'meta_value',
			'order' => 'DESC',
			'meta_query' => array(
					array(
							'key' => '_custom_product_event_date',
							'value' => date('YmdHi'),
							'compare' => '<=',
					)
			)

	);
	$products = wc_get_products( $args );

	if ( ! empty( $products ) ) {

		// Loop through each product
		foreach ( $products as $product ) {
			$product_id = $product->get_id();
			$event_date = get_post_meta( $product_id, '_custom_product_event_date', true );
			$newDate = date("l jS F", strtotime($event_date));

			$output .= '<a href="' . esc_url( $product->get_permalink() ) . '"><h2>' . esc_attr( $product->get_name() ) . '</h2>  ' .  esc_html( $newDate ) . '</a>';
		}


	} else {
		$output .= 'Watch this space for upcoming events!';
	}

	// Return the output
	return '<div class="event-listings__wrapper">' . $output . '</div>';

}
add_shortcode('event_listings', 'event_listings_from_woocommerce_product');