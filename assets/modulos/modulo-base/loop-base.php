 <!-- Aquí si tiene un contendedor -->
 
 <?php
      $active = true;
      $temp = $wp_query;
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
      $args = array(
        'post_type'      => '', // Poner nombre del modulo de la linea 33
        'orderby'        => 'date',
        'order'          => 'ASC',
        'paged'          => $paged,
        'posts_per_page' => -1 // El -1 es para poner infinitos elementos
      );
      $wp_query = new WP_Query($args);
      if ($wp_query->have_posts()) :
        while ($wp_query->have_posts()) : $wp_query->the_post();
          $post_thumbnail_id  = get_post_thumbnail_id($post->ID);
          $post_thumbnail_url = wp_get_attachment_url($post_thumbnail_id);
          $post_thumbnail_alt = get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true);?>

<!-- Aquí el código de HTML -->

      <?php
        endwhile;
      endif;
      wp_reset_query();
      $wp_query = $temp;
      ?>


<!-- Cerrar los contenedores -->