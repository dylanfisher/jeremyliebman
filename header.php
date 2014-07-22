<!DOCTYPE html>
<html class="no-js" data-home-url="<?php echo home_url(); ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php wp_title( '-', true, 'right' ); echo esc_html( get_bloginfo('name'), 1 ) ?></title>
    <meta name="description" content="<?php echo get_bloginfo('description') ?>">
    <meta name="keywords" content="">
    <meta name="viewport" id="viewport" content="width=500, minimal-ui">
    <link rel="icon" type="image/png" href="<?php echo get_bloginfo('template_url'); ?>/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?php echo bloginfo('stylesheet_url'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_url'); ?>/css/build/minified/application.css" />
    <script src="<?php echo get_bloginfo('template_url'); ?>/js/modernizr-2.6.2.min.js"></script>
    <?php wp_head() // For plugins ?>
  </head>
  <body>
  <!--[if lte IE 9]>
    <p class="chromeframe">&#42; You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
  <![endif]-->

  <?php
    // Query single page by slug
    $args=array(
      'name' => 'information',
      'post_type' => 'page',
      'numberposts' => 1
    );
    $info_page = get_posts($args);
    if( $info_page ):
      $info_id = $info_page[0]->ID;
      $info_content = get_post_meta($info_id);
      $column1 = wpautop($info_content['column_one'][0]);
      $column2 = wpautop($info_content['column_two'][0]);
      $column3 = wpautop($info_content['column_three'][0]);
  ?>
  <div id="info-wrapper" class="info-wrapper clearfix">
    <div id="info-wrapper-close" class="info-wrapper-close"></div>
    <h2>Information</h2>
    <div class="column column1">
    <?php echo $column1 ?>
    </div>
    <div class="column column2">
    <?php echo $column2; ?>
    </div>
    <div class="column column3">
    <?php echo $column3; ?>
    </div>
  </div>
<?php endif; ?>

  <div class="wrapper">
    <header>
      <h1 id="site-title"><span><a href="<?php bloginfo('url') ?>/" title="<?php echo esc_html( bloginfo('name'), 1 ) ?>" rel="home"><?php bloginfo('name') ?></a></span></h1>
      <nav>
        <div id="search-container" class="search-container">
          <?php get_search_form(); ?>
        </div>
      </nav>
      <div id="info-button" class="info-button">Info</div>
    </header>
    <div id="pjax-container" class="pjax-container">
