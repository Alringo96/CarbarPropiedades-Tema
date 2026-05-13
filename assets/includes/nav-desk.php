      <?php
      wp_nav_menu(array(
          'theme_location' => 'menu-superior',
          'menu_class'     => 'menu',
          'container'      => false,
          'depth'          => 2,
          'walker'         => new bootstrap_5_wp_nav_menu_walker(),
          'fallback_cb'    => 'bootstrap_5_wp_nav_menu_walker::fallback',
      ));
      ?>  
