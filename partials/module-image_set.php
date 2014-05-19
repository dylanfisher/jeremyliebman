<?php

  // Image set router
  //
  // Check if we want a large image set or a medium image set, and use that partial.
  // Use include( locate_template() ) so that we have access to variables declared in this file.

  get_field('featured_set') ? $featured_set = true : $featured_set = false;

  if($featured_set){
    // get_template_part( 'partials/module', 'image_set-image_large' );
    include(locate_template('partials/module-image_set-image_large.php'));
  } else {
    // get_template_part( 'partials/module', 'image_set-image_medium' );
    include(locate_template('partials/module-image_set-image_medium.php'));
  }

?>
