    </div><?php // <!-- #pjax-container --> ?>

    <footer>
      <?php get_template_part( 'partials/content', 'footer' ); ?>
      <div class="contact">
        <span><a href="mailto:jeremy@jeremyliebman.com">jeremy@jeremyliebman.com</a></span>
        <span>+1 718 909 0883</span>
        <span><a href="http://instagram.com/jeremy_liebman" target="_blank">instagram</a></span>
      </div>
    </footer>

  </div><?php // <!-- .wrapper --> ?>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="<?php echo get_bloginfo('template_url'); ?>/js/jquery-1.10.1.min.js"><\/script>')</script>
  <script src="<?php echo get_bloginfo('template_url'); ?>/js/build/application.min.js?ver=3"></script>

  <script>
    var _gaq=[['_setAccount','UA-15084097-1'],['_trackPageview']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src='//www.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
  </script>
  <?php wp_footer() ?>
