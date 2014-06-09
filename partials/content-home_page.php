<?php
$args = array(
  'post_type' => 'post',
  'posts_per_page' => 15
);

$home_page = true;

$the_query = new WP_Query( $args );

// The Loop
if ( $the_query->have_posts() ) {
  while ( $the_query->have_posts() ) {
    $the_query->the_post();
    include(locate_template('partials/module-image_set.php'));
  }
}

wp_reset_postdata();
?>
