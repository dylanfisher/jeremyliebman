<?php if( have_rows('images') ):

  // Specify the size of image we want to work with, and the margin for the placeholder images
  $size = 'large';
  $marginTop = 7;
  $marginLeft = 7;

  include(locate_template('partials/module-image_set-get_size.php'));

  endif;

?>
