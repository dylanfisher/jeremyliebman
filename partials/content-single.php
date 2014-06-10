<?php
  // Post limit is set to -1 using pre_get_posts in functions.php
  while ( have_posts() ) : the_post();
    get_template_part( 'partials/module', 'image_set' );
  endwhile;
?>
