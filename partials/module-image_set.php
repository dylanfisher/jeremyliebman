<?php if( have_rows('images') ):

  // Specify the size of image we want to work with, and the margin for the placeholder images
  $size = 'medium';
  $marginTop = 7;
  $marginLeft = 7;

  $repeater = get_field('images');
  $row_count = count($repeater);

  // Loop through each image size and find the largest. This is used to set the image-set dimensions.
  $max_image_height = null;
  $max_image_width = null;

  foreach ($repeater as $item) {
    $max_image_height = $max_image_height === null ? $item['image']['sizes'][$size . '-height'] : max($max_image_height, $item['image']['sizes'][$size . '-height']);
    $max_image_width = $max_image_width === null ? $item['image']['sizes'][$size . '-width'] : max($max_image_width, $item['image']['sizes'][$size . '-width']);
  }

  $imageSet_height = $max_image_height + ($row_count * $marginTop);
  $imageSet_width = $max_image_width + ($row_count * $marginTop);

?>

  <div class="image-set <?php sandbox_post_class() ?>" style="width: <?php echo $imageSet_width .'px' ?>; height: <?php echo $imageSet_height .'px' ?>;">

    <?php while( have_rows('images') ): the_row(); $row_count--;

      // vars
      $image = get_sub_field('image');
      $url = $image['url'];
      $title = $image['title'];
      $alt = $image['alt'];
      $caption = $image['caption'];

      // sizes
      $thumb = $image['sizes'][ $size ];
      $width = $image['sizes'][ $size . '-width' ];
      $height = $image['sizes'][ $size . '-height' ];


      $image_at_size = $image['sizes'][ $size ];

    ?>

      <?php if($row_count > 0){ // only show an image for the first image in the set ?>
        <div class="image-set-placeholder" data-image-url="<?php echo $url ?>" style="width: <?php echo $width . 'px' ?>; height: <?php echo $height . 'px' ?>; margin-top: <?php echo $row_count * $marginTop . 'px' ?>; margin-left: <?php echo $row_count * $marginLeft . 'px' ?>;"></div>
      <?php } else { ?>
        <img src="<?php echo $image_at_size; ?>" alt="<?php echo $alt ?>" width="<?php echo $width ?>" height="<?php echo $height ?>">
      <?php } ?>

    <?php endwhile; ?>

  </div>

<?php endif; ?>
