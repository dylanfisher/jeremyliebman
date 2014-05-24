<?php
  $repeater = get_field('images');
  $row_count = count($repeater);

  // Loop through each image size and find the largest. This is used to set the image-set dimensions.
  $max_image_height = null;
  $max_image_width = null;

  foreach ($repeater as $item) {
    $max_image_height = $max_image_height === null ? $item['image']['sizes'][$size . '-height'] : max($max_image_height, $item['image']['sizes'][$size . '-height']);
    $max_image_width = $max_image_width === null ? $item['image']['sizes'][$size . '-width'] : max($max_image_width, $item['image']['sizes'][$size . '-width']);
  }

  get_field('featured_set') ? $featured_set = true : $featured_set = false;

  // Get the variables of the first image outside the loop for placeholder sizes
  $first_row = $repeater[0];
  $image_first = $first_row['image'];
  $url_first = $image_first['url'];
  $alt_first = $image_first['alt'];

  // sizes
  $image_at_size_first = $image_first['sizes'][ $size ];
  $width_first = $image_first['sizes'][ $size . '-width' ];
  $height_first = $image_first['sizes'][ $size . '-height' ];

  // Get the largest width and height out of the set (not being used anymore, since the placeholder images are uniform).
  $imageSet_height = $height_first + ($row_count * $marginTop);
  $imageSet_width = $width_first + ($row_count * $marginTop);
?>

<div class="image-set<?php echo $featured_set ? ' featured-image-set' : false ?> <?php sandbox_post_class() ?>" style="width: <?php echo $imageSet_width .'px' ?>; height: <?php echo $imageSet_height .'px' ?>;">

  <?php while( have_rows('images') ): the_row(); $row_count--;

    // Get the variables for each image inside the loop
    $image = get_sub_field('image');
    $url = $image['url'];
    $title = $image['title'];
    $alt = $image['alt'];
    $caption = $image['caption'];

    // sizes
    $image_at_size = $image['sizes'][ $size ];
    $width = $image['sizes'][ $size . '-width' ];
    $height = $image['sizes'][ $size . '-height' ];

    $data_url = $image['sizes']['huge'];

  ?>

    <?php if($row_count > 0){ // only show an image for the first image in the set ?>
      <div class="image-set-placeholder" data-image-url="<?php echo $data_url ?>" style="width: <?php echo $width_first . 'px' ?>; height: <?php echo $height_first . 'px' ?>; margin-top: <?php echo $row_count * $marginTop . 'px' ?>; margin-left: <?php echo $row_count * $marginLeft . 'px' ?>;"></div>
    <?php } else { ?>
      <div class="image-set-info-wrapper" data-image-url="<?php echo $data_url ?>" style="width: <?php echo $width_first . 'px' ?>; height: <?php echo $height_first . 'px' ?>">
        <div class="image-set-info">
          <div class="image-info-text">
            <h6><?php the_title() ?></h6>
            <?php if(get_field('image_set_caption')){ ?>
              <h6><?php the_field('image_set_caption') ?></h6>
            <?php } ?>
          </div>
        </div>
        <img class="test" src="<?php echo $image_at_size_first; ?>" alt="<?php echo $alt_first ?>" width="<?php echo $width_first ?>" height="<?php echo $height_first ?>">
      </div>
    <?php } ?>

  <?php endwhile; ?>

</div>
