<!DOCTYPE html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php wp_title( '-', true, 'right' ); echo esc_html( get_bloginfo('name'), 1 ) ?></title>
    <meta name="description" content="<?php echo get_bloginfo('description') ?>">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width">
    <link rel="icon" type="image/png" href="<?php echo get_bloginfo('template_url'); ?>/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?php echo bloginfo('stylesheet_url'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_url'); ?>/css/build/minified/application.css" />
    <script src="<?php echo get_bloginfo('template_url'); ?>/js/modernizr-2.6.2.min.js"></script>
    <?php wp_head() // For plugins ?>
  </head>
  <body>
  <!--[if lte IE 9]>
    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
  <![endif]-->

  <div id="info-wrapper" class="info-wrapper">
    <div id="info-wrapper-close" class="info-wrapper-close"></div>
    <h2>Information</h2>
    <div class="column">
      <ul>
        <li>Nam ista vestra</li>
        <li>Haec para/doca a dicamus.</li>
        <li>Quod idem cum va</li>
        <li>Mene ergo et Trmas,?</li>
        <li>Isto modo, ne sata non esset.</li>
        <li>Neque solum ea m .</li>
        <li>Nam ista vestra</li>
      </ul>
      <h4>Contact</h4>
      <ul>
        <li>Haec para/doca a dicamus.</li>
        <li>Quod idem cum vatis .</li>
        <li>Mene ergo et?</li>
      </ul>
      <h4>Blog</h4>
      <ul>
        <li>Nam ista vestra</li>
        <li>Haec para/doca a dicamus.</li>
        <li>Quod idem cum va</li>
        <li>Mene ergo et Trmas,?</li>
        <li>Isto modo, ne sata non esset.</li>
        <li>Neque solum ea m .</li>
        <li>Nam ista vestra</li>
        <li>Haec para/doca a dicamus.</li>
        <li>Quod idem cum vatis .</li>
        <li>Mene ergo et?</li>
        <li>Isto modo, ne sata non esset.</li>
        <li>Neque solum ea m paria.</li>
        <li>Nam ista vestra</li>
        <li>Haec para/doca a dicamus.</li>
        <li>Quod idem cum .</li>
        <li>Mene ergo et Trmas, ?</li>
        <li>Isto modo, ne sata non esset.</li>
        <li>Neque solum ea m .</li>
      </ul>
    </div>
    <div class="column">
      <h4>Interview &amp; Press</h4>
      <ul><li>Nam ista vestra</li>
        <li>Haec para/doca a dicamus.</li>
        <li>Quod idem cum vatis .</li>
        <li>Mene ergo et?</li></ul>
      <h4>Client List</h4>
      <ul>
        <li>Quod idem cum .</li>
        <li>Mene ergo et Trmas, ?</li>
        <li>Isto modo, ne sata non esset.</li>
        <li>Neque solum ea m .</li>
      </ul>
    </div>
  </div>

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
