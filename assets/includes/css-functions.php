<?php

function css_functions()
{
  // 🔹 Registrar librerías base
  wp_register_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', [], null, 'all');
  wp_register_style('bootstrap-icon', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css', [], null, 'all');
  wp_register_style('font', 'https://fonts.googleapis.com/icon?family=Material+Icons+Outlined', [], null, 'all');
  wp_register_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], null, 'all');

  wp_register_style('leaflet', get_template_directory_uri() . '/assets/dist/leaflet.css', [], null, 'all');
  wp_register_style('nav', get_template_directory_uri() . '/assets/librerias/css/topbar-nav-footer.css', [], null, 'all');
  wp_register_style('tarjetas', get_template_directory_uri() . '/assets/librerias/css/tarjetas.css', [], null, 'all');
  wp_register_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', [], null, 'all');
  wp_register_style('lightgallery', 'https://cdn.jsdelivr.net/npm/lightgallery@2.7.2/css/lg-bundle.min.css', [], null, 'all');

  // 🔹 Enqueue general
  wp_enqueue_style('bootstrap-css');
  wp_enqueue_style('bootstrap-icon');
  wp_enqueue_style('font');
  wp_enqueue_style('fontawesome');
  wp_enqueue_style('leaflet');

  wp_enqueue_style('swiper');
  wp_enqueue_style('lightgallery');

  // 🔹 Páginas específicas
  if (is_page_template('home.php')) {
    wp_enqueue_style('home', get_template_directory_uri() . '/assets/librerias/css/home.css', [], null);
  }

  if (is_page_template('resultado.php')) {
    wp_enqueue_style('resultado', get_template_directory_uri() . '/assets/librerias/css/resultado.css', [], null);
  }
  if (is_page_template('nosotros.php')) {
    wp_enqueue_style('nosotros', get_template_directory_uri() . '/assets/librerias/css/nosotros.css', [], null);
  }
  if (is_page_template('servicios.php')) {
    wp_enqueue_style('servicios', get_template_directory_uri() . '/assets/librerias/css/servicios.css', [], null);
  }

  // ⚡ CSS fuerte: se carga al final de todo
  if (is_singular('post')) {
    wp_enqueue_style('paginadetalle', get_template_directory_uri() . '/assets/librerias/css/paginadetalle.css', [], null);
  }
  wp_enqueue_style('nav');
  wp_enqueue_style('tarjetas');
}

add_action('wp_enqueue_scripts', 'css_functions');
