<div class="search-nav">
  <select id="search-select">
    <option class="search-select-title"><?php echo strlen(get_search_query()) > 0 ? get_search_query() : the_title() ?></option>
    <?php
      // Loop through every WP tag on the site and list it as an <option> search-tag.
      // These tags represent the image set's tags as an entire set.
      $tags = get_tags();
      foreach ($tags as &$tag) {
        echo '<option class="search-tag image-set-search-tag" disabled>' . $tag->name . '</option>';
      }

      // Include our wpdb module that queries the database for all ACF tags. Returns $tags_array with all tags.
      include(locate_template('partials/module-acf_tags_query.php'));
      // Loop through every ACF tag and list these as search-tag's as well.
      // These represent each individual image's tags.
      foreach ($tags_array as $tag=>$value) {
        echo '<option class="search-tag image-search-tag" disabled>' . $value . '</option>';
      }

      $categories = get_categories( array('hide_empty' => 0, 'orderby' => 'count', 'order' => 'desc') );
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
        <option><?php echo $child->name; ?></option>
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
<form role="search" method="get" id="wp-search-form" class="search-form visuallyhidden" action="<?php echo get_bloginfo('url') ?>/?search" hidden>
  <label>
    <span class="screen-reader-text">Search for:</span>
    <input type="search" id="wp-search-input" class="search-field" placeholder="Search" value="" name="s" title="Search for:" />
  </label>
  <input type="submit" class="search-submit" value="Search" />
</form>
