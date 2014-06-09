<?php

//   if(is_search()):

//     $query_term = get_search_query();

//     $args = array(
//       'post_type' => 'post',
//       'posts_per_page' => -1,
//       'tax_query' => array(
//           'relation' => 'OR',
//             array(
//               'taxonomy' => 'category',
//               'terms' => array($query_term),
//               'include_children' => true,
//               'operator' => 'IN'
//             ),
//             array(
//               'taxonomy' => 'post_tag',
//               'terms' => array($query_term),
//               'include_children' => false,
//               'operator' => 'NOT IN'
//             )
//           ),
//     );

//   else:

//     $query_term = get_the_title();

//     $args = array(
//       // Default loop with no arguments.
//       'post_type' => 'page'
//     );

//   endif;


//   $home_page = true;

//   $the_query = new WP_Query( $args );

//   // The Loop
//   if ( $the_query->have_posts() ) {
//     while ( $the_query->have_posts() ) {
//       $the_query->the_post();
//       get_template_part( 'partials/module', 'image_set' );
//     }
//   }

//   wp_reset_postdata();
// ?>


<?php while ( have_posts() ) : the_post(); ?>

  <?php get_template_part( 'partials/module', 'image_set' ); ?>

<?php endwhile; ?>
