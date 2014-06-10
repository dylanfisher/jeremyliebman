<?php

  if(is_search()):

    $query_term = get_search_query();
    $query_term = sanitize_title($query_term);

    $args = array(
      'post_type' => 'post',
      'posts_per_page' => -1,
      'tax_query' => array(
          'relation' => 'OR',
            array(
              'taxonomy' => 'category',
              'terms' => array($query_term),
              'field' => 'slug'
            ),
            array(
              'taxonomy' => 'post_tag',
              'terms' => array($query_term),
              'field' => 'slug'
            )
          ),
    );

    $the_query = new WP_Query( $args );

    // The Loop
    if ( $the_query->have_posts() ) {
      while ( $the_query->have_posts() ) {
        $the_query->the_post();
        get_template_part( 'partials/module', 'image_set' );
      }
    }

    wp_reset_postdata();

  else:

    // $query_term = get_the_title();

    // $args = array(
    //   // Default loop with no arguments.
    //   'post_type' => 'page'
    // );

      while ( have_posts() ) : the_post();
        get_template_part( 'partials/module', 'image_set' );
      endwhile;

  endif;

  // $the_query = new WP_Query( $args );

  // // The Loop
  // if ( $the_query->have_posts() ) {
  //   while ( $the_query->have_posts() ) {
  //     $the_query->the_post();
  //     get_template_part( 'partials/module', 'image_set' );
  //   }
  // }

  // wp_reset_postdata();
?>


<?php
  // while ( have_posts() ) : the_post();
  //   get_template_part( 'partials/module', 'image_set' );
  // endwhile;
?>
