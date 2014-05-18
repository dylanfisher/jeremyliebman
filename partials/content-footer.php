<?php
  $categories = get_categories( array('hide_empty' => 0, 'orderby' => 'count', 'order' => 'desc') );
  foreach ($categories as &$cat) :
    // Parent categories
    if($cat->category_parent == 0 && $cat->slug != 'uncategorized') :
?>
    <h2><?php echo $cat->name; ?></h2>
      <?php
        // Child categories
        $children = get_categories( array('child_of' => $cat->cat_ID) );
        foreach ($children as &$child) :
      ?>
        <h4><?php echo $child->name; ?></h4>
      <?php
        endforeach;
      ?>
<?php
    endif;
  endforeach;
?>
