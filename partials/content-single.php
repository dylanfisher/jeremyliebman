<?php while ( have_posts() ) : the_post(); ?>

  <?php get_template_part( 'partials/module', 'image_set' ); ?>

<?php endwhile; ?>
