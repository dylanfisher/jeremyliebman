<?php
  $categories = get_categories( array('hide_empty' => 0) );
  foreach ($categories as &$cat) :
    // Parent categories

    if($cat->category_parent == 0 && $cat->slug != 'uncategorized') :

      $parent_cat_posts = get_posts('category_name=' . $cat->name);
      if($parent_cat_posts) :
?>
    <h2><?php echo $cat->name; ?></h2>

<?php
      endif;
?>

    <div class="footer-image-section">

      <?php
        $child_cats = get_categories( array('parent' => $cat->cat_ID ) );

        foreach ($child_cats as $child) :
          // var_dump($child);

          $args = array(
            'post_type' => 'post',
            'posts_per_page' => 1,
            'cat' => $child->cat_ID
          );

          $the_query = new WP_Query( $args );

          if($the_query->have_posts()) :
            while ( $the_query->have_posts() ) :
              $the_query->the_post();

              // Vars
              $rows = get_field('images'); // get all the rows
              $first_row = $rows[0]; // get the first row

              $image = $first_row['image'];
              $url = $image['url'];
              $title = $image['title'];
              $alt = $image['alt'];
              $caption = $image['caption'];

              $size = 'small';

              // sizes
              $thumb = $image['sizes'][ $size ];
              $width = $image['sizes'][ $size . '-width' ];
              $height = $image['sizes'][ $size . '-height' ];

              $image_at_size = $image['sizes'][ $size ];

              // Post URL
              $postName = get_the_title();
              $postSlug = sanitize_title(get_the_title());
              $postUrl = get_the_permalink();
              $searchLink = get_home_url() . '/?search&amp;s=' . $child->cat_name;

      ?>

      <div class="footer-image-wrapper">
        <?php if($image_at_size){ ?>
          <a href="<?php echo $searchLink; ?>">
            <picture>
              <!--[if IE 9]><video style="display: none;"><![endif]-->
              <source srcset="<?php echo $image['sizes']['small']; ?>, <?php echo $image['sizes']['small@2x']; ?> 2x">
              <!--[if IE 9]></video><![endif]-->
              <img srcset="<?php echo $image['sizes']['small']; ?>, <?php echo $image['sizes']['small@2x']; ?> 2x" alt="<?php echo $alt ?>">
            </picture>
          </a>
        <?php } ?>
        <h5>
          <a href="<?php echo $searchLink; ?>"><?php echo $child->cat_name ?></a>
        </h5>
      </div>

      <?php
            endwhile;
          endif;
          wp_reset_postdata();
        endforeach;
      ?>

    </div><?php // End <div class="footer-image-section"> ?>

<?php
    endif;
  endforeach;
?>
