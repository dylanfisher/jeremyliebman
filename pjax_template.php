<?php
  //
  // A template for displaying pjax-loaded content.
  // Right now, this template is just used to update the
  // <title> of the page. Ultimately all posts should
  // be routed through partials/application-router.php.
  //
?>
<title><?php wp_title( '-', true, 'right' ); echo esc_html( get_bloginfo('name'), 1 ) ?></title>

<?php get_template_part( 'partials/application', 'router' ); ?>
