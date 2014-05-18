<div class="search-nav">
  <select id="search-select">
    <option class="search-select-title" disabled><?php echo strlen(get_search_query()) > 0 ? get_search_query() : the_title() ?></option>
    <?php
      // Loop through every tag on the site and list it as an <option> search-tag.
      $tags = get_tags();
      foreach ($tags as &$tag) {
        echo '<option class="search-tag" disabled>' . $tag->name . '</option>';
      }
    ?>
    <?php
      $categories = get_categories( array('hide_empty' => 0, 'orderby' => 'count', 'order' => 'desc') );
      foreach ($categories as &$cat) :
        // Parent categories
        if($cat->category_parent == 0 && $cat->slug != 'uncategorized') :
    ?>
        <optgroup label="<?php echo $cat->name; ?>">
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
<form role="search" method="get" id="wp-search-form" class="search-form visuallyhidden" hidden>
  <label>
    <span class="screen-reader-text">Search for:</span>
    <input type="search" id="wp-search-input" class="search-field" placeholder="Search" value="" name="s" title="Search for:" />
  </label>
  <input type="submit" class="search-submit" value="Search" />
</form>
