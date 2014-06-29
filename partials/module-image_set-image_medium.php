<?php if( have_rows('images') ):

  // Specify the size of image we want to work with, and the margin for the placeholder images
  $size = 'medium';
  $marginTop = 5;
  $marginLeft = 5;

  include(locate_template('partials/module-image_set-get_size.php'));

  endif;

?>
