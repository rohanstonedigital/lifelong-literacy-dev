<?php
$user_id = get_current_user_id();
$course_id = get_the_ID();
$course_lessons = learndash_course_get_lessons( $course_id, '' );
$lesson_count = 0;

echo '<div class="ll-course-content">';
foreach ( $course_lessons as $lesson ) :
	$lesson_id = $lesson->ID;
	$lesson_completed = learndash_is_lesson_complete( $user_id, $lesson_id, $course_id );
  $lesson_count++;
	$lesson_topics = learndash_get_topic_list_legacy( $lesson_id, $course_id );
	$topics_completed_count = 0;
	foreach ( $lesson_topics as $topic ) {
		$topic_id = $topic->ID;
		$topic_complete = learndash_is_topic_complete( $user_id, $topic_id, $course_id );
		if ( $topic_complete ) {
			$topics_completed_count++;
		}
		$topics_listings .= '<li>' . $topic->post_title . ': ' . $topic_complete . '</li>';
	}
?>
		<div class="accordion">
      <div><h3><?php echo $lesson->post_title; ?></h3> <span>Part <?php echo $lesson_count; ?></span></div>
      <div>
        <span>
          <?php if ( $lesson_topics ) : ?>
            <?php echo $topics_completed_count . ' / ' . count( $lesson_topics ); ?>
          <?php else : ?>
            <?php echo ( $lesson_completed ) ? '1' : '0'; ?> / 1
          <?php endif; ?>
          Completed
        <span>
        <span class="chevron-icon"></span>
      </div>
    </div>
    <div class="panel">
      <?php
        if ( $lesson_topics ) {
          echo '<ul>' . $topics_listings . '</ul>';
        } else {
          echo '<p>No topics found.</p>';
        }
      ?>
    </div>
<?php
 endforeach;
?>
</div>
<style>
.ll-course-content {
  border: 1px solid rgba(160, 160, 160, 0.30);
  border-radius: 10px; 
  padding: 31px; 
}
.accordion {
  font-size: 16px; 
  padding: 16px 0;
  cursor: pointer;
  width: 100%;
  border-bottom: 1px solid rgba(160, 160, 160, 0.30);
  transition: 0.4s;
  display: flex;
  align-items: center;
  justify-content: space-between;
  color: rgba(160, 160, 160, 0.80); 
}

.accordion h3 {
  color: #000;
  font-family: Lato;
  font-size: 18px;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
  letter-spacing: -0.18px; 
}


.chevron-icon::before {
  color: #009DDF;
	border-style: solid;
	border-width: 2px 2px 0 0;
	content: '';
	display: inline-block;
	height: 14px;
  width: 14px;
	position: relative;
	transform: rotate(-45deg);
	vertical-align: top;
  top: 8px;
	transform: rotate(45deg);
  transition: all 0.3s ease-in-out;
  margin: 0 12px
}

.active .chevron-icon::before {
  transform: rotate(135deg);
}

.panel {
  padding: 0 18px;
  background-color: white;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}
</style>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>