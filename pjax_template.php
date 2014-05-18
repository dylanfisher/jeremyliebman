<?php
  //
  // A template for displaying pjax-loaded content.
  // Right now, this template is just used to update the
  // <title> of the page. Ultimately all single posts
  // should come from partials/application-router.php.
  //
?>
<title>
  <?php
    //Print the <title> tag based on what is being viewed.
    global $page, $paged;
  
    wp_title( '-', true, 'right' );
  
    // Add the blog name.
    bloginfo( 'name' );
  
    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
      echo " - $site_description";
  ?>
</title> 

<?php get_template_part( 'partials/application', 'router' ); ?>

<br><span><i><sub>Page called from pjax_template.php</sub></i></span>
