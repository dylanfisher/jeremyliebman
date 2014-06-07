<?php
  // Build the select field search options using two arrays,
  // one for single images, one for image sets.

  //
  // Image set array
  //

  $image_sets = array();

  // Loop through every WP tag on the site and list it as an <option> search-tag.
  // These tags represent the image set's tags as an entire set.
  $tags = get_tags();
  foreach ($tags as &$tag) {
    array_push($image_sets, $tag->name);
  }

  // Loop through every Page and get the Title.
  // List it as an option for image sets.
  $pages = get_posts(array('post_type' => 'post', 'posts_per_page' => -1));
  foreach ($pages as $page) {
    array_push($image_sets, $page->post_title);
  }

  // Remove duplicates
  $image_sets = array_unique($image_sets);

  //
  // Single images array
  //

  $single_images = array();

  // Include our wpdb module that queries the database for all ACF tags. Returns $tags_array with all tags.
  include(locate_template('partials/module-acf_tags_query.php'));
  // Loop through every ACF tag and list these as search-tag's as well.
  // These represent each individual image's tags.
  foreach ($tags_array as $tag=>$value) {
    array_push($single_images, $value);
  }

  // Remove duplicates
  $single_images = array_unique($single_images);

?>

<div class="search-nav">
  <select id="search-select">

  <?php
    // Image set select options
    foreach ($image_sets as $tag) {
      echo '<option class="search-tag image-set-search-tag" disabled>' . $tag . '</option>';
    }

    // Single image select options
    foreach ($single_images as $tag) {
      echo '<option class="search-tag image-search-tag" disabled>' . $tag . '</option>';
    }
  ?>

    <option class="search-select-title"><?php echo strlen(get_search_query()) > 0 ? get_search_query() : the_title() ?></option>
    <?php
      $categories = get_categories( array('hide_empty' => 0) );
      foreach ($categories as &$cat) :
        // Parent categories
        if($cat->category_parent == 0 && $cat->slug != 'uncategorized') :
    ?>

    <optgroup class="search-category-parent" label="<?php echo $cat->name; ?>">
      <?php
        // Child categories
        $children = get_categories( array('child_of' => $cat->cat_ID) );
        foreach ($children as &$child) :
      ?>
        <option class="image-set-search-tag"><?php echo $child->name; ?></option>
      <?php
        endforeach;
      ?>
    </optgroup>

    <?php
        endif;
      endforeach;
    ?>
  </select>
  <div id="search-test"></div>
  <input id="search-test-input" type="hidden">
</div>

<?php // Hide the #wp-search-form. This is just used to submit our data to WP. The actual search/sorting takes place in #search-select. ?>
<?php // Use this search form for image sets ?>
<form role="search" method="get" id="wp-search-form" class="search-form visuallyhidden" action="<?php echo get_bloginfo('url') ?>/?search" hidden>
  <input type="search" id="wp-search-input" class="search-field" placeholder="Search" value="" name="s" title="Search for:" />
  <input type="submit" class="search-submit" value="Search" />
</form>

<?php // Use a separate search form for searching for single images ?>
<form role="search" method="get" id="wp-search-form-single-images" class="search-form visuallyhidden" action="<?php echo get_bloginfo('url') ?>/?search&amp;single" hidden>
  <input type="search" id="wp-search-input" class="search-field" placeholder="Search" value="" name="s" title="Search for:" />
  <input type="submit" class="search-submit" value="Search" />
</form>
