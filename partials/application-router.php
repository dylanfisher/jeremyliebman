<?php
  //
  // This is the application's router. It controls what content we serve to pjax.
  // All posts should use get_template_part( $slug, $name ) to inherit from
  // this router. From here, we can handle pjax show actions by providing
  // the appropriate templates.
  //
?>

<?php // Pages that call module-image_set.php ?>
<div id="post-<?php the_ID() ?>" class="content center <?php sandbox_post_class() ?>">
  <h2 id="pjax-page-title" class="pjax-page-title hidden"><?php echo strlen(get_search_query()) > 0 ? get_search_query() : the_title() ?></h2>

  <?php
    if(isset($post)):
      $post->post_name == 'home-page' ? get_template_part( 'partials/content', 'home_page' ) : false;
    else:
  ?>

  <p>No results found :(</p>
  <p>Try again</p>

  <?php
    endif
  ?>

  <?php get_template_part( 'partials/content', 'single' ); ?>

</div>
