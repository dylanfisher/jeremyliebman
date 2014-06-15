<?php
  $repeater = get_field('images');
  $row_count = count($repeater);

  $search_query = get_search_query();
  // If search query is empty it means we are searching for a page, so change search query to equal the page's title.
  empty($search_query) ? $search_query = get_the_title() : $search_query;

  // echo $search_query;

  $tagsArray = array();
  foreach($repeater as $tags) {
    $tagsArray[] = $tags['tags'];
  }

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

  // Create a one-dimensional array of WP Tags.
  $tags_array = array();
  $tags = get_the_tags();
  if (is_array($tags)){
    foreach ($tags as $tag) {
      array_push($tags_array, $tag->name);
    };
  };

  // Check if the image's Category OR WP Tag OR Title match the search query, and show these image sets.
  $cats = get_the_category();
  $cat_end = end($cats);
  $cat_name = $cat_end->name;

  if( strpos( strtolower($cat_name), strtolower($search_query) ) !== false || in_array($search_query, $tags_array) !== false || strpos(sanitize_title(get_the_title()), sanitize_title($search_query)) !== false):

?>

  <div class="image-result image-set<?php echo $featured_set ? ' featured-image-set' : false ?> <?php sandbox_post_class() ?>" style="width: <?php echo $imageSet_width .'px' ?>; height: <?php echo $imageSet_height .'px' ?>;">

    <?php

      ///////////////////////////////////////////////////////
      // Image Sets
      ///////////////////////////////////////////////////////

      while( have_rows('images') ): the_row(); $row_count--;

      // Get the variables for each image inside the loop
      $image = get_sub_field('image');
      $url = $image['url'];
      $title = $image['title'];
      $alt = $image['alt'];
      $caption = get_sub_field('caption');
      $tags = strtolower(get_sub_field('tags'));

      // sizes
      $image_at_size = $image['sizes'][ $size ];
      $width = $image['sizes'][ $size . '-width' ];
      $height = $image['sizes'][ $size . '-height' ];

      $data_url = $image['sizes']['huge'];
      $data_url_2x = $image['sizes']['huge@2x'];
      $data_url_mobile = $image['sizes']['carousel-mobile'];
      $data_url_mobile_2x = $image['sizes']['huge'];

    ?>

      <?php if($row_count > 0){ // only show an image for the first image in the set ?>
        <div class="image-set-placeholder" data-image-url="<?php echo $data_url ?>" data-image-url-2x="<?php echo $data_url_2x ?>" data-image-url-mobile="<?php echo $data_url_mobile ?>" data-image-url-mobile-2x="<?php echo $data_url_mobile_2x ?>" data-image-caption="<?php echo $caption ?>" style="width: <?php echo $width_first . 'px' ?>; height: <?php echo $height_first . 'px' ?>; margin-top: <?php echo $row_count * $marginTop . 'px' ?>; margin-left: <?php echo $row_count * $marginLeft . 'px' ?>;"></div>
      <?php } else { ?>
        <div class="image-set-info-wrapper" data-image-url="<?php echo $data_url ?>" data-image-url-2x="<?php echo $data_url_2x ?>" data-image-url-mobile="<?php echo $data_url_mobile ?>" data-image-url-mobile-2x="<?php echo $data_url_mobile_2x ?>" data-image-caption="<?php echo $caption ?>" style="width: <?php echo $width_first . 'px' ?>; height: <?php echo $height_first . 'px' ?>">
          <div class="image-set-info">
            <div class="image-info-text">
              <h6><?php the_title() ?></h6>
              <?php if(get_field('image_set_caption')){ ?>
                <h6><?php the_field('image_set_caption') ?></h6>
              <?php } ?>
            </div>
          </div>
          <picture>
            <!--[if IE 9]><video style="display: none;"><![endif]-->
            <source srcset="<?php echo $image_first['sizes']['medium']; ?>, <?php echo $image_first['sizes']['medium@2x']; ?> 2x">
            <!--[if IE 9]></video><![endif]-->
            <img srcset="<?php echo $image_first['sizes']['medium']; ?>, <?php echo $image_first['sizes']['medium@2x']; ?> 2x" alt="<?php echo $alt_first ?>">
          </picture>
        </div>
      <?php } ?>

    <?php endwhile; ?>

  </div>

<?php endif; ?>

  <?php

    ///////////////////////////////////////////////////////
    // Single Images
    ///////////////////////////////////////////////////////

    // Don't show single images on non-search pages.
    if(!is_search()){
      return;
    };

    while( have_rows('images') ): the_row();

      // Re-set variables for individual images
      // Get the variables for each image inside the loop
      $image = get_sub_field('image');
      $url = $image['url'];
      $title = $image['title'];
      $alt = $image['alt'];
      $caption = get_sub_field('caption');
      $tags = strtolower(get_sub_field('tags'));

      // sizes
      $image_at_size = $image['sizes'][ $size ];
      $width = $image['sizes'][ $size . '-width' ];
      $height = $image['sizes'][ $size . '-height' ];

      $data_url = $image['sizes']['huge'];
      $data_url_2x = $image['sizes']['huge@2x'];
      $data_url_mobile = $image['sizes']['carousel-mobile'];
      $data_url_mobile_2x = $image['sizes']['huge'];

      // Check if the image's individual ACF tags OR caption match the search query, and only show these images.
      if(strpos($tags, strtolower($search_query)) !== false || strpos(strtolower($caption), strtolower($search_query)) !== false):
  ?>
        <div class="image-result single-image <?php sandbox_post_class() ?>">
          <div class="image-set-info-wrapper" data-image-url="<?php echo $data_url ?>" data-image-url-2x="<?php echo $data_url_2x ?>" data-image-url-mobile="<?php echo $data_url_mobile ?>" data-image-url-mobile-2x="<?php echo $data_url_mobile_2x ?>" data-image-caption="<?php echo $caption ?>">
            <div class="image-set-info">
              <div class="image-info-text">
                <h6><?php the_title() ?></h6>
                <?php if(get_field('image_set_caption')){ ?>
                  <h6><?php the_field('image_set_caption') ?></h6>
                <?php } ?>
              </div>
            </div>
            <picture>
              <!--[if IE 9]><video style="display: none;"><![endif]-->
              <source srcset="<?php echo $image['sizes']['medium']; ?>, <?php echo $image['sizes']['medium@2x']; ?> 2x">
              <!--[if IE 9]></video><![endif]-->
              <img srcset="<?php echo $image['sizes']['medium']; ?>, <?php echo $image['sizes']['medium@2x']; ?> 2x" alt="<?php echo $alt ?>" data-image-url="<?php echo $data_url ?>" data-image-url-2x="<?php echo $data_url_2x ?>" data-image-url-mobile="<?php echo $data_url_mobile ?>" data-image-url-mobile-2x="<?php echo $data_url_mobile_2x ?>" data-image-caption="<?php echo $caption ?>">
            </picture>
          </div>
        </div>
  <?php
      endif;
    endwhile;
  ?>
