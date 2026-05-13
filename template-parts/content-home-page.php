<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Carbar
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <!-- HERO ANIMADO -->
  <section class="hero">
    <!-- Imágenes de fondo para la transición -->
    <?php if (have_rows('slides')): ?>
      <?php while (have_rows('slides')): the_row();
        $image = get_sub_field('slide_image');
        $url   = get_sub_field('slide_url');

        // Usar la URL del campo de imagen si existe, si no usar la URL externa
        $bg_url = $image ? $image['url'] : $url;
      ?>
        <div class="hero-bg<?php echo get_row_index() == 1 ? ' active' : ''; ?>"
          style="background-image: url('<?php echo esc_url($bg_url); ?>')">
        </div>
      <?php endwhile; ?>
    <?php endif; ?>



    <div class="hero-content">
      <h1><?php the_title(); ?></h1>
      <?php the_excerpt(); ?>

      <!-- SEARCH BOX MEJORADO -->
      <?php
      // Buscar la página que use la plantilla "resultado.php" (Template Name: Resultados)
      $pagina_resultado = get_pages(array(
        'meta_key'   => '_wp_page_template',
        'meta_value' => 'resultado.php'
      ));

      $url_resultado = $pagina_resultado ? get_permalink($pagina_resultado[0]->ID) : home_url('/');
      ?>

      <form class="search-box" action="<?php echo esc_url($url_resultado); ?>" method="get">
        <div class="search-field">
          <label class="search-label">Tipo de operación</label>
          <select name="operacion">
            <option value="">Selecciona</option>
            <?php
            $operaciones = get_categories(array(
              'taxonomy'   => 'tipo_operacion',
              'hide_empty' => true,
              'parent'     => 0,
            ));
            foreach ($operaciones as $operacion) {
              echo '<option value="' . esc_attr($operacion->slug) . '">' . esc_html($operacion->name) . '</option>';
            }
            ?>
          </select>
        </div>

        <div class="search-field">
          <label class="search-label">Tipo de propiedad</label>
          <select name="propiedad">
            <option value="">Selecciona</option>
            <?php
            $propiedades = get_categories(array(
              'taxonomy'   => 'tipo_propiedad',
              'hide_empty' => true,
              'parent'     => 0,
            ));
            foreach ($propiedades as $propiedad) {
              echo '<option value="' . esc_attr($propiedad->slug) . '">' . esc_html($propiedad->name) . '</option>';
            }
            ?>
          </select>
        </div>

        <div class="search-field">
          <label class="search-label">Ubicación</label>
          <input type="text" name="comuna" placeholder="Comuna">
        </div>

        <div class="search-field">
          <button type="submit">Buscar</button>
        </div>
      </form>



    </div>
  </section>

  <!-- PROPIEDADES CON CARRUSEL AUTOMÁTICO -->
  <section class="properties-section">
    <h2 class="section-title"><?php echo the_field('propiedades_destacadas') ?></h2>
    <div class="filter-buttons">
      <button class="active" data-filter="all">Todas</button>
      <button data-filter="Arriendo">Arriendo</button>
      <button data-filter="Venta">Venta</button>
    </div>

    <div id="propiedadesCarousel" class="carousel slide container" data-bs-ride="carousel">
      <!-- Mensaje de no resultados (oculto por defecto) -->
      <div id="no-results-message" class="no-results-message text-center py-5">
        <h4 class="text-muted">No se encontraron propiedades para este filtro</h4>
        <p class="text-muted">Intenta con otro filtro o vuelve a "Todas"</p>
      </div>

      <div class="carousel-inner">

        <?php
        // Query para entradas con etiqueta 'destacado'
        $args = array(
          'post_type' => 'post',
          'posts_per_page' => -1,
          'tax_query' => array(
            array(
              'taxonomy' => 'post_tag',
              'field'    => 'slug',
              'terms'    => 'destacado',
            ),
          ),
        );

        $destacado_query = new WP_Query($args);
        $slide_count = 0;
        $cards_per_slide = 3;
        $card_index = 0;

        // Array para JS
        $property_images_js = array();

        if ($destacado_query->have_posts()):
          while ($destacado_query->have_posts()): $destacado_query->the_post();

            if ($card_index % $cards_per_slide == 0):
              $active_class = ($slide_count == 0) ? 'active' : '';
              echo '<div class="carousel-item ' . $active_class . '"><div class="row g-4">';
            endif;

            // Tipo de operación
            $terms = wp_get_post_terms(get_the_ID(), 'tipo_operacion');
            $tipo_operacion = '';
            $badge_class = '';
            if (!empty($terms)):
              $tipo_operacion = $terms[0]->name;
              switch ($tipo_operacion) {
                case 'Venta':
                  $badge_class = 'bg-success';
                  break;
                case 'Arriendo':
                  $badge_class = 'bg-primary';
                  break;
                case 'Arriendo temporal':
                  $badge_class = 'bg-warning text-dark';
                  break;
              }
            endif;

            $precio = get_field('precio');

            // Galería
            $galeria = get_field('galeria');
            $imagen_url = '';
            $imgs_array = array();
            if ($galeria && isset($galeria[0]['imagen'])):
              $imagen_url = $galeria[0]['imagen']['url'];
              foreach ($galeria as $img): $imgs_array[] = $img['imagen']['url'];
              endforeach;
            endif;

            $property_images_js[get_the_ID()] = $imgs_array;
        ?>

            <div class="col-md-4" data-type="<?php echo esc_attr($tipo_operacion); ?> <?php echo ($tipo_operacion === 'Arriendo temporal') ? 'Arriendo' : ''; ?>">

              <div class="property-card">
                <div class="property-image has-carousel position-relative">
                  <div class="property-badges position-absolute top-0 start-0 p-2">
                    <span class="badge <?php echo esc_attr($badge_class); ?>">
                      <?php echo esc_html($tipo_operacion); ?>
                    </span>
                  </div>
                  <img id="property-img-<?php the_ID(); ?>" src="<?php echo esc_url($imagen_url); ?>" class="w-100" alt="<?php the_title(); ?>">
                  <i class="fa-solid fa-chevron-left arrow left"></i>
                  <i class="fa-solid fa-chevron-right arrow right"></i>
                </div>

                <div class="price-section d-flex justify-content-between align-items-center">
                  <div class="price">UF <?php echo number_format($precio, 0, ',', '.'); ?></div>

                  <button class="btn" onclick="window.open('<?php the_permalink(); ?>', '_blank')">Ver Detalles</button>
                </div>

                <?php
                // Inicializar variables
                $dormitorios = ['cantidad' => 0, 'texto' => ''];
                $banos       = ['cantidad' => 0, 'texto' => ''];
                $areas       = ['cantidad' => 0, 'texto' => 'm²'];

                // Revisar si existe el repeater "detalle_rapido"
                if (have_rows('detalle_rapido', $id)) {
                  while (have_rows('detalle_rapido', $id)) {
                    the_row();
                    $icono   = get_sub_field('iconos');
                    $cantidad = get_sub_field('cantidad__numero');
                    $texto    = get_sub_field('texto');

                    switch ($icono) {
                      case 'fa-solid fa-bed':
                        $dormitorios = ['cantidad' => $cantidad, 'texto' => $texto];
                        break;
                      case 'fa-solid fa-bath':
                        $banos = ['cantidad' => $cantidad, 'texto' => $texto];
                        break;
                      case 'fa-solid fa-ruler-combined':
                        $areas = ['cantidad' => $cantidad, 'texto' => $texto ?: 'm²'];
                        break;
                        // Agrega más iconos si quieres
                    }
                  }
                }

                // Crear el array detalle_rapido
                $detalle_rapido = [
                  ['icono' => 'fa-solid fa-bed', 'cantidad' => $dormitorios['cantidad'], 'texto' => $dormitorios['texto']],
                  ['icono' => 'fa-solid fa-bath', 'cantidad' => $banos['cantidad'], 'texto' => $banos['texto']],
                  ['icono' => 'fa-solid fa-ruler-combined', 'cantidad' => $areas['cantidad'], 'texto' => $areas['texto']],
                ];

                // Mostrar en HTML
                ?>
                <div class="info-bar d-flex justify-content-center">
                  <?php foreach ($detalle_rapido as $item): ?>
                    <div class="info-item">
                      <i class="<?php echo esc_attr($item['icono']); ?>"></i> <?php echo esc_html($item['cantidad'] . ' ' . $item['texto']); ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

        <?php
            $card_index++;
            if ($card_index % $cards_per_slide == 0):
              echo '</div></div>';
              $slide_count++;
            endif;
          endwhile;

          if ($card_index % $cards_per_slide != 0):
            echo '</div></div>';
          endif;
        endif;
        wp_reset_postdata();
        ?>

      </div>

      <!-- Pasamos el array de imágenes a JS -->
      <script>
        const propertyImages = <?php echo json_encode($property_images_js); ?>;
      </script>

    </div> <!-- cierre de #propiedadesCarousel -->

    <div class="carousel-controls-custom text-center">
      <button class="carousel-control-custom prev" type="button" data-bs-target="#propiedadesCarousel" data-bs-slide="prev">
        ‹
      </button>

      <button class="carousel-control-custom next" type="button" data-bs-target="#propiedadesCarousel" data-bs-slide="next">
        ›
      </button>
    </div>

  </section>

  <!-- SECCIÓN INTERMEDIA PARALLAX -->
  <section>
    <?php
    $upload = get_field('subir_imagen');
    $url    = get_field('imagen_url');
    $hero_bg = $upload ? $upload : $url;
    if ($hero_bg):
    ?>
      <section class="parallax-section" style="background-image: url('<?php echo esc_url($hero_bg); ?>');">
        <div class="parallax-content  ">
          <h2><?php echo get_field('titulo_seccion_3'); ?></h2>
          <p><?php echo get_field('texto_seccion_3'); ?></p>
        </div>
      </section>
    <?php endif; ?>

  </section>

  <!-- SERVICIOS MODIFICADOS -->
  <h2 class="section-title"><?php echo the_field('nuestros_servicios') ?></h2>
  <section class="services container">

    <div class="service">
      <?php if (have_rows('imagenes_1')): ?>
        <?php while (have_rows('imagenes_1')): the_row();
          $imagen_subida = get_sub_field('subir_imagen');
          $imagen_url = get_sub_field('imagen_url');
          $imagen_a_mostrar = $imagen_subida ? $imagen_subida : $imagen_url;
          if ($imagen_a_mostrar): ?>
            <img src="<?php echo esc_url($imagen_a_mostrar); ?>" alt="">
          <?php endif; ?>
        <?php endwhile; ?>
      <?php endif; ?>
      <div class="service-content">
        <h3 class="service-title"><?php echo the_field('titulo_1'); ?></h3>
        <div class="service-details">
          <ul>
            <?php
            if (have_rows('lista_1')):
              while (have_rows('lista_1')) : the_row();
                $elemento = get_sub_field('elemento');
            ?>
                <li><?php echo esc_attr($elemento) ?></li>
            <?php
              endwhile;
            else :
            endif;
            ?>
          </ul>
        </div>
        <a href="<?php echo site_url('/servicios/#servicio-1'); ?>" class="service-btn"><?php echo the_field('ver_mas'); ?></a>
      </div>
    </div>

    <div class="service">
      <?php if (have_rows('imagenes_2')): ?>
        <?php while (have_rows('imagenes_2')): the_row();
          $imagen_subida = get_sub_field('subir_imagen');
          $imagen_url = get_sub_field('imagen_url');
          $imagen_a_mostrar = $imagen_subida ? $imagen_subida : $imagen_url;
          if ($imagen_a_mostrar): ?>
            <img src="<?php echo esc_url($imagen_a_mostrar); ?>" alt="">
          <?php endif; ?>
        <?php endwhile; ?>
      <?php endif; ?>
      <div class="service-content">
        <h3 class="service-title"><?php echo the_field('titulo_2'); ?></h3>
        <div class="service-details">
          <ul>
            <?php
            if (have_rows('lista_2')):
              while (have_rows('lista_2')) : the_row();
                $elemento = get_sub_field('elemento');
            ?>
                <li><?php echo esc_attr($elemento) ?></li>
            <?php
              endwhile;
            else :
            endif;
            ?>
          </ul>
        </div>
        <a href="<?php echo site_url('/servicios/#servicio-2'); ?>" class="service-btn"><?php echo the_field('ver_mas'); ?></a>
      </div>
    </div>

    <div class="service">
      <?php if (have_rows('imagenes_3')): ?>
        <?php while (have_rows('imagenes_3')): the_row();
          $imagen_subida = get_sub_field('subir_imagen');
          $imagen_url = get_sub_field('imagen_url');
          $imagen_a_mostrar = $imagen_subida ? $imagen_subida : $imagen_url;
          if ($imagen_a_mostrar): ?>
            <img src="<?php echo esc_url($imagen_a_mostrar); ?>" alt="">
          <?php endif; ?>
        <?php endwhile; ?>
      <?php endif; ?>
      <div class="service-content">
        <h3 class="service-title"><?php echo the_field('titulo_3'); ?></h3>
        <div class="service-details">
          <ul>
            <?php
            if (have_rows('lista_3')):
              while (have_rows('lista_3')) : the_row();
                $elemento = get_sub_field('elemento');
            ?>
                <li><?php echo esc_attr($elemento) ?></li>
            <?php
              endwhile;
            else :
            endif;
            ?>
          </ul>
        </div>
        <a href="<?php echo site_url('/servicios/#servicio-3'); ?>" class="service-btn"><?php echo the_field('ver_mas'); ?></a>
      </div>
    </div>

    <div class="service">
      <?php if (have_rows('imagenes_4')): ?>
        <?php while (have_rows('imagenes_4')): the_row();
          $imagen_subida = get_sub_field('subir_imagen');
          $imagen_url = get_sub_field('imagen_url');
          $imagen_a_mostrar = $imagen_subida ? $imagen_subida : $imagen_url;
          if ($imagen_a_mostrar): ?>
            <img src="<?php echo esc_url($imagen_a_mostrar); ?>" alt="">
          <?php endif; ?>
        <?php endwhile; ?>
      <?php endif; ?>
      <div class="service-content">
        <h3 class="service-title"><?php echo the_field('titulo_4'); ?></h3>
        <div class="service-details">
          <ul>
            <?php
            if (have_rows('lista_4')):
              while (have_rows('lista_4')) : the_row();
                $elemento = get_sub_field('elemento');
            ?>
                <li><?php echo esc_attr($elemento) ?></li>
            <?php
              endwhile;
            else :
            endif;
            ?>
          </ul>
        </div>
        <a href="<?php echo site_url('/servicios/#servicio-4'); ?>" class="service-btn"><?php echo the_field('ver_mas'); ?></a>
      </div>
    </div>

  </section>

</article><!-- #post-<?php the_ID(); ?> -->