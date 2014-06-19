<?php
  $categories = get_categories( array('hide_empty' => 0, 'orderby' => 'count', 'order' => 'desc') );
  foreach ($categories as &$cat) :
    // Parent categories
    if($cat->category_parent == 0 && $cat->slug != 'uncategorized') :
?>
    <h2><?php echo $cat->name; ?></h2>

    <div class="footer-image-section">

      <?php
      // $child_cats = get_categories( array('parent' => $cat->cat_ID ) );
      // // $child_cats = implode(", ", $child_cats);

      // var_dump($child_cats);
      
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

          // Post URL
          $postName = $child->post_name;
          $postUrl = get_bloginfo('url') . '/' . $child->post_name;

      ?>
        <div class="footer-image-wrapper">
          <?php if($image_at_size){ ?>
            <a href="<?php echo $postUrl ?>">
              <picture>
                <!--[if IE 9]><video style="display: none;"><![endif]-->
                <source srcset="<?php echo $image['sizes']['small']; ?>, <?php echo $image['sizes']['small@2x']; ?> 2x">
                <!--[if IE 9]></video><![endif]-->
                <img srcset="<?php echo $image['sizes']['small']; ?>, <?php echo $image['sizes']['small@2x']; ?> 2x" alt="<?php echo $alt ?>">
              </picture>
            </a>
          <?php } ?>
          <h5>
            <a href="<?php echo $postUrl ?>"><?php echo $child->post_title; ?></a>
          </h5>
        </div>

      <?php
        endforeach;
      ?>

    </div>

<?php
    endif;
  endforeach;
?>
