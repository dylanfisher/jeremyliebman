<?php

  ///////////////////////////////////////////////////////
  // Videos
  ///////////////////////////////////////////////////////

  $video = wp_oembed_get( get_field('url'));

  // Video image vars
  $image = get_field('video_image');
  $url = $image['url'];
  $title = $image['title'];
  $alt = $image['alt'];

  // sizes
  $image_at_size = $image['sizes'][ $size ];

?>

<div class="image-result">
  <?php echo $video; ?>
</div>

<!--
<div class="image-result video-result <?php sandbox_post_class() ?>" tabindex="0">
  <div class="image-set-info-wrapper" data-title="<?php the_title(); ?>">
    <div class="image-set-info">
      <div class="image-info-text">
        <h6><?php the_title() ?></h6>
        <?php if(get_field('image_set_caption')){ ?>
          <h6><?php the_field('image_set_caption') ?></h6>
        <?php } ?>
      </div>
    </div>
    <picture>
      <source srcset="<?php echo $image['sizes']['medium']; ?>, <?php echo $image['sizes']['medium@2x']; ?> 2x">
      <img srcset="<?php echo $image['sizes']['medium']; ?>, <?php echo $image['sizes']['medium@2x']; ?> 2x" alt="<?php echo $alt ?>">
    </picture>
    <div class="hidden video-embed">
      <?php echo $video; ?>
    </div>
  </div>
</div>
 -->
