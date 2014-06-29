<?php
  //
  // Build a single master array to store all search options (tags, categories, titles, etc.)
  //

  //
  // Image set array
  // Uses Wordpress default tags and Page Titles
  //

  $search_terms = array();

  // Loop through every WP tag on the site and list it as an <option> search-tag.
  // These tags represent the image set's tags as an entire set.
  $tags = get_tags();
  foreach ($tags as &$tag) {
    array_push($search_terms, $tag->name);
  }

  // Loop through every Page and get the Title.
  // List it as an option for image sets.
  $pages = get_posts(array('post_type' => 'post', 'posts_per_page' => -1));
  foreach ($pages as $page) {
    array_push($search_terms, $page->post_title);
  }

  //
  // Single images array
  // Uses ACF single image tags and ACF captions
  //

  // Include our wpdb module that queries the database for all ACF tags and captions. Returns $tags_array with all results.
  include(locate_template('partials/module-acf_tags_query.php'));
  // Loop through every ACF tag and list these as search-tag's as well.
  // These represent each individual image's tags.
  foreach ($tags_array as $tag=>$value) {
    array_push($search_terms, $value);
  }

  //
  // Search terms
  //

  // Case-insensitive array_unique
  function array_iunique($array) {
      return array_intersect_key(
          $array,
          array_unique(array_map("StrToLower",$array))
      );
  }

  // Get unique values
  $search_terms = array_iunique($search_terms);

  // Sort alphabetically
  sort($search_terms);

?>

<div class="search-nav">
  <select id="search-select">
  <?php
    // Select options for each search term
    foreach ($search_terms as $term) {
      echo '<option class="search-tag image-search-tag" disabled>' . $term . '</option>';
    }
  ?>

    <option class="search-select-title"><?php echo strlen(get_search_query()) > 0 ? get_search_query() : the_title() ?></option>

    <option class="image-set-search-tag recent-work-option">Recent Work</option>

    <?php
      $categories = get_categories( array('hide_empty' => 0) );
      foreach ($categories as &$cat) :
        // Parent categories
        if($cat->category_parent == 0 && $cat->slug != 'uncategorized') :
    ?>

    <?php
      // Child categories
      $children = get_categories( array('child_of' => $cat->cat_ID) );
      foreach ($children as &$child) :
    ?>
      <option class="image-set-search-tag"><?php echo $child->name; ?></option>
    <?php
      endforeach;
    ?>

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
