<?php

  // Image set router

  include(locate_template('partials/module-image_set-image_medium.php'));

  if(get_field('video')){
    include(locate_template('partials/module-videos.php'));
  }

?>
