<?php
  // Query the database directly for all ACF tags fields, omitting any empty results.
  // - http://codex.wordpress.org/Class_Reference/wpdb#SELECT_Generic_Results
  global $wpdb;
  $rows = $wpdb->get_results($wpdb->prepare( 
      "
      SELECT *
      FROM wp_postmeta
      WHERE meta_key LIKE %s
        OR meta_key LIKE %s
        AND meta_value <>''
      ",
      'images_%_tags', // meta_name: $ParentName_$RowNumber_$ChildName
      'images_%_caption'
  ));

  // loop through the results
  if($rows){
    // print_r($rows);
    $tags_array = array(); // Initialize empty array
    foreach($rows as $row){
      $tags_array[] = $row->meta_value; // Add each result to the array
    }
    $tags_array = implode(", ", $tags_array); // Implode array to flatten comma delimited values from tags string
    $tags_array = explode(", ", $tags_array); // Explode array so that all comma delimited values are separate key->value pairs
    $tags_array = array_unique($tags_array); // Remove duplicate entries
    $tags_array = array_filter($tags_array); // Remove empty items
    // print_r($tags_array); // Now $tags_array is an array of every ACF tag for every image
  }
?>
