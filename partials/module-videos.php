<?php

  ///////////////////////////////////////////////////////
  // Videos
  ///////////////////////////////////////////////////////

  // $video = wp_oembed_get( get_field('url'));

  $is_video = get_field('video');
  get_field('video_site') == 'youtube' ? $is_youtube = true : $is_youtube = false;
  get_field('video_site') == 'vimeo' ? $is_vimeo = true : $is_vimeo = false;

  if($is_video):

    // Video image vars
    $image = get_field('video_image');
    $size = 'large';
    $url = $image['url'];
    $title = $image['title'];
    $alt = $image['alt'];

    // sizes
    $image_at_size = $image['sizes'][ $size ];
?>
  <div class="image-result video-result">
    <div class="image-set-info-wrapper">
      <div class="image-set-info">
        <div class="image-info-text">
          <h6><?php the_title() ?></h6>
          <?php if(get_field('image_set_caption')){ ?>
            <h6><?php the_field('image_set_caption') ?></h6>
          <?php } ?>
        </div>
      </div>
      <div class="play-button"></div>
      <picture>
        <source srcset="<?php echo $image['sizes']['large']; ?>, <?php echo $image['sizes']['large@2x']; ?> 2x">
        <img srcset="<?php echo $image['sizes']['large']; ?>, <?php echo $image['sizes']['large@2x']; ?> 2x" alt="<?php echo $alt_first ?>">
      </picture>

    <?php
      if($is_youtube):
        $video = get_field('youtube_id');
    ?>
      <iframe width="650" height="365" data-src="http://www.youtube.com/embed/<?php echo $video; ?>?rel=0&showinfo=0&autohide=1&autoplay=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    <?php
      elseif ($is_vimeo):
        $video = get_field('vimeo_id');
    ?>
      <iframe data-src="//player.vimeo.com/video/<?php echo $video; ?>?portrait=0&color=000&badge=0&byline=0&title=0&autoplay=1" width="650" height="365" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    <?php
      endif;
    ?>
    </div>
  </div>
<?php
  endif;
?>
