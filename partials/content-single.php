<div class="image-set-loop-wrapper">
  <?php
    // Post limit is set to -1 using pre_get_posts in functions.php
    while ( have_posts() ) : the_post();
      // print_r($post);
      get_template_part( 'partials/module', 'image_set' );
    endwhile;
  ?>

  <?php if(get_next_posts_link()): ?>
    <div class="ajax-navigation">
      <?php echo get_next_posts_link('Load more'); ?>
    </div>
  <?php endif; ?>
</div>
