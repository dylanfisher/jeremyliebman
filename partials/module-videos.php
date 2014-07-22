<?php

  ///////////////////////////////////////////////////////
  // Videos
  ///////////////////////////////////////////////////////

  // $video = wp_oembed_get( get_field('url'));

  $is_video = get_field('video');
  get_field('video_site') == 'youtube' ? $is_youtube = true : $is_youtube = false;
  get_field('video_site') == 'vimeo' ? $is_vimeo = true : $is_vimeo = false;

  if($is_video):

    // YOUTUBE
    if($is_youtube):
      $video = get_field('youtube_id');
?>
  <div class="image-result video-result">
    <iframe width="650" height="365" src="http://www.youtube.com/embed/<?php echo $video; ?>?rel=0&showinfo=0&autohide=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
  </div>
<?php

    // VIMEO
    elseif ($is_vimeo):
      $video = get_field('vimeo_id');
?>
  <div class="image-result video-result">
    <iframe src="//player.vimeo.com/video/<?php echo $video; ?>?portrait=0&color=000&badge=0&byline=0&title=0" width="650" height="365" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
  </div>
<?php
    endif;

  endif;
?>
