<?php
/*
 * Template Name: Course Template
 *  
 */
$user_id = get_current_user_id();
$course_id = get_the_ID();
$categories = get_the_terms( $course_id, 'ld_course_category' );

$course_progress = learndash_user_get_course_progress( $user_id, $course_id, '' );
$course_completed = $course_progress['summary']['completed']; 
$course_total = $course_progress['summary']['total']; 
 
$course_progress_in_percentage = ($course_completed / $course_total) * 100; 


// $course_lessons = learndash_course_get_lessons( $course_id, '' );
// echo '<pre>';
// var_dump($course_lessons);
// echo '</pre>';


get_header(); ?>
<main class="site-main">
	<section class="page-header">
		<div class="page-hader__bg-image"></div>
		<div class="ast-container">
			<div class="page-header__content">
				<?php echo get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'class' => 'card-img-top img-fit', 'alt' => esc_attr( get_the_title() ), 'loading' => 'lazy' ) ); ?>
				<div class="page-header__content__title">
					<span><?php echo $categories[0]->name; ?></span>
					<?php the_title( '<h1>', '</h1>' ); ?>
				</div>
			</div>
			<div class="page-header__course-progress">
				<progress class="enrolled_courses__items__content__progress-bar" value="<?php echo $course_progress['summary']['completed']; ?>" max="<?php echo $course_progress['summary']['total']; ?>"></progress>
				<span><?php echo round($course_progress_in_percentage) . '% Completed'; ?></span>
			</div>
		</div>
	</section>

	<section class="page-content">
		<div class="ast-container">
			<div class="content-area">
				<div class="entry-content">
					<h2>Course Content</h2>
					<?php get_template_part( 'template-parts/course-content' ); ?>
				</div>
				<aside role="complementary">


				</aside>
			</div>
		</div>
	</section>

	<?php
	while (have_posts()) : the_post();

			the_content();

	endwhile; // End of the loop.
	?>


</main><!-- #main -->
<?php
get_footer();