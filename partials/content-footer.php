<?php
  $categories = get_categories( array('hide_empty' => 0, 'orderby' => 'count', 'order' => 'desc') );
  foreach ($categories as &$cat) :
    // Parent categories
    if($cat->category_parent == 0 && $cat->slug != 'uncategorized') :
?>
    <h2><?php echo $cat->name; ?></h2>

    <div class="footer-image-section">

      <?php
        // Child posts of parent categories
        $children = get_posts( array('category' => $cat->cat_ID) );

        foreach ($children as &$child) :
          $rows = get_field('images', $child->ID); // get all the rows
          $first_row = $rows[0]; // get the first row

          // vars
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

      ?>
        <div class="footer-image-wrapper">
          <?php if($image_at_size){ ?>
            <img src="<?php echo $image_at_size; ?>" alt="<?php echo $alt ?>" width="<?php echo $width ?>" height="<?php echo $height ?>">
          <?php } ?>
          <h6><?php echo $child->post_title; ?></h6>
        </div>

      <?php
        endforeach;
      ?>

    </div>

<?php
    endif;
  endforeach;
?>
