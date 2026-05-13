<?php

/**
 * Template part for displaying page content in page.php
 * @package mapas
 */

get_header();
?>

<main id="primary" class="site-main">

    <div class="contenedor-hero">
        <div class="seccion-1-texto">
            <h1><?php the_title(); ?></h1>
            <p><span class="inicio"><?php the_field('inicio') ?></span> <span class="text-white">/</span> <span class="resultado"><?php the_field('titulo_de_la_pagina') ?></span></p>
        </div>
        <?php
        $image = get_field('hero');
        if (!empty($image)): ?>
            <img class="img-hero" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
        <?php endif; ?>
    </div>

    <div class="filtros my-4 container d-flex flex-column flex-md-row gap-3">
        <div class="buscador-container">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input class="buscador" type="text" placeholder="Region/Comuna" id="search-category">
        </div>


        <div class="btn-filtros d-flex flex-wrap gap-2" id="filter-buttons-container">
            <?php
            // Obtener todos los términos de la taxonomía 'tipo_propiedad' que tienen posts asociados.
            $propiedad_terms = get_terms([
                'taxonomy'   => 'tipo_propiedad',
                'hide_empty' => true, // Mostrar solo si hay entradas con esa categoría
            ]);
            ?>
            <div class="dropdown" data-filter="propiedad">
                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="btn-propiedad-filter">
                    Tipo de Propiedad
                </button>
                <ul class="dropdown-menu" aria-labelledby="btn-propiedad-filter">
                    <li><a class="dropdown-item filter-option text-dark" href="#" data-value="">Mostrar Todos</a></li>
                    <?php foreach ($propiedad_terms as $term) : ?>
                        <li><a class="dropdown-item filter-option text-dark" href="#" data-value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php
            // Obtener etiquetas 'nuevo' y 'usado'
            $estado_terms = get_terms([
                'taxonomy'   => 'post_tag',
                'slug'       => ['nuevo', 'usado'],
                'hide_empty' => true,
            ]);
            ?>
            <div class="dropdown" data-filter="estado">
                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="btn-estado-filter">
                    Nuevo/Usado
                </button>
                <ul class="dropdown-menu" aria-labelledby="btn-estado-filter">
                    <li><a class="dropdown-item filter-option text-dark" href="#" data-value="">Mostrar Todos</a></li>
                    <?php foreach ($estado_terms as $term) : ?>
                        <li><a class="dropdown-item filter-option text-dark" href="#" data-value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="dropdown" data-filter="min_dormitorios">
                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="btn-dormitorios-filter">
                    Dormitorios
                </button>
                <div class="dropdown-menu p-3" style="min-width: 200px;">
                    <div class="mb-3">
                        <label for="min-dormitorios" class="form-label">Mínimo de Dormitorios</label>
                        <input type="number" class="form-control" id="min-dormitorios" placeholder="Ej: 3" min="0">
                    </div>
                    <button class="w-100 apply-filter-btn" data-filter-type="dormitorios">Aplicar</button>
                </div>
            </div>

            <div class="dropdown" data-filter="min_banos">
                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="btn-banos-filter">
                    Baños
                </button>
                <div class="dropdown-menu p-3" style="min-width: 200px;">
                    <div class="mb-3">
                        <label for="min-banos" class="form-label">Mínimo de Baños</label>
                        <input type="number" class="form-control" id="min-banos" placeholder="Ej: 2" min="0">
                    </div>
                    <button class="w-100 apply-filter-btn" data-filter-type="banos">Aplicar</button>
                </div>
            </div>

            <div class="dropdown" data-filter="precio">
                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="btn-precio-filter">
                    Precio
                </button>
                <div class="dropdown-menu p-3" style="min-width: 250px;">
                    <div class="mb-2">
                        <label for="min-price" class="form-label">Precio Mínimo (UF)</label>
                        <input type="number" class="form-control" id="min-price" placeholder="Mín." min="0">
                    </div>
                    <div class="mb-3">
                        <label for="max-price" class="form-label">Precio Máximo (UF)</label>
                        <input type="number" class="form-control" id="max-price" placeholder="Máx." min="0">
                    </div>
                    <button class="w-100 apply-filter-btn" data-filter-type="precio">Aplicar</button>
                </div>
            </div>
            
            <button class="" id="clear-filters-btn">Limpiar Filtros</button>
        </div>
    </div>


    <div class="row mb-2 me-2" id="map-controls">
        <div class="col-12 text-end">
            <button id="toggle-map-btn" class="btn-mapa">Ver lista</button>
        </div>
    </div>



    <?php
    // Recibir filtros del buscador y nuevos filtros
    $operacion       = isset($_GET['operacion']) ? sanitize_text_field($_GET['operacion']) : '';
    $propiedad       = isset($_GET['propiedad']) ? sanitize_text_field($_GET['propiedad']) : '';
    $comuna          = isset($_GET['comuna']) ? sanitize_text_field($_GET['comuna']) : '';
    $category        = isset($_GET['comuna']) ? sanitize_text_field($_GET['comuna']) : ''; 
    $min_dormitorios = isset($_GET['min_dormitorios']) ? intval($_GET['min_dormitorios']) : 0; // NUEVO
    $min_banos       = isset($_GET['min_banos']) ? intval($_GET['min_banos']) : 0;           // NUEVO

    //  Construir consulta
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ];

    // Taxonomías
    $tax_query = ['relation' => 'AND'];

    if ($operacion) {
        $tax_query[] = [
            'taxonomy' => 'tipo_operacion',
            'field'    => 'slug',
            'terms'    => $operacion,
        ];
    }

    if ($propiedad) {
        $tax_query[] = [
            'taxonomy' => 'tipo_propiedad',
            'field'    => 'slug',
            'terms'    => $propiedad,
        ];
    }

    if ($comuna) {
        // Buscar el término por nombre en la taxonomía 'category'
        $term = get_term_by('name', $comuna, 'category'); // busca exactamente por nombre
        if ($term) {
            $tax_query[] = [
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ];
        } else {
            // Si no existe la comuna/region, no mostrar nada
            $args['post__in'] = [0];
        }
    }

    if (count($tax_query) > 1) {
        $args['tax_query'] = $tax_query;
    }
    
    // Lógica de Meta Query (Precio, Dormitorios, Baños)
    $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
    $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 0;
    
    $meta_query = array('relation' => 'AND');

    // --- Filtro de Precio ---
    if ($min_price > 0 || $max_price > 0) {
        $value_array = [];
        $compare = '';
        if ($min_price > 0 && $max_price > 0) {
            $compare = 'BETWEEN';
            $value_array = [$min_price, $max_price];
        } elseif ($min_price > 0) {
            $compare = '>=';
            $value_array = $min_price;
        } elseif ($max_price > 0) {
            $compare = '<=';
            $value_array = $max_price;
        }

        if (!empty($value_array)) {
            $meta_query[] = [
                'key'     => 'precio',
                'value'   => $value_array,
                'type'    => 'DECIMAL', 
                'compare' => $compare,
            ];
        }
    }
    
// --- Filtro de Dormitorios Mínimos ---
if ($min_dormitorios > 0) {
    $meta_query[] = [
        'key'     => 'num_dormitorios',
        'value'   => $min_dormitorios,
        'type'    => 'NUMERIC', 
        'compare' => '>=',
    ];
}

// --- Filtro de Baños Mínimos ---
if ($min_banos > 0) {
    $meta_query[] = [
        'key'     => 'num_banos',
        'value'   => $min_banos,
        'type'    => 'NUMERIC', 
        'compare' => '>=',
    ];
}

    
    // Aplicar la meta query si hay más de un elemento (la relación 'AND' es el primer elemento)
    if (count($meta_query) > 1) {
        $args['meta_query'] = $meta_query;
    }


    // ==========================
    // Obtener posts
    // ==========================
    $posts = get_posts($args);

    // ==========================
    // 🗺️ Preparar datos para mapa.js
    // ==========================
    $mapData = [];

    foreach ($posts as $post) {
        $id = $post->ID;
        // Intentar obtener lat/lng primero de los campos ACF, luego del excerpt (fallback)
        $lat = get_field('latitud', $id); 
        $lng = get_field('longitud', $id);
        
        if (!$lat || !$lng) {
             $excerpt = get_the_excerpt($post);
             if (preg_match('/(-?\d+\.\d+),\s*(-?\d+\.\d+)/', $excerpt, $matches)) {
                 $lat = floatval($matches[1]);
                 $lng = floatval($matches[2]);
             }
        }
        
        if ($lat && $lng) {
            
            // Tipo de operación
            $tipo_operacion = '';
            $badge_class = '';
            $terms = wp_get_post_terms($id, 'tipo_operacion');
            if (!empty($terms)) {
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
                    default:
                        $badge_class = 'bg-secondary';
                        break;
                }
            }

            // Imagen
            $image = get_the_post_thumbnail_url($id, 'medium') ?: 'https://via.placeholder.com/350x200?text=Sin+imagen';

            // Detalle rápido (Lectura del Repeater para la tarjeta/mapa)
            $dormitorios = 0;
            $banos = 0;
            $areas = 0;
            $detalle_rapido_array = [];
            
            $detalle_rapido_rows_data = get_field('detalle_rapido', $id); 
            
            if ($detalle_rapido_rows_data && is_array($detalle_rapido_rows_data)) {
                 foreach ($detalle_rapido_rows_data as $row) {
                    
                    $icono = $row['iconos'] ?? $row['icono'] ?? '';
                    $cantidad = (int)($row['cantidad__numero'] ?? $row['cantidad'] ?? 0);
                    $texto_descriptivo = $row['texto'] ?? '';

                    if ($icono && $cantidad > 0) {
                        $icono_slug = strtolower($icono);
                        
                        if (strpos($icono_slug, 'fa-bed') !== false) {
                            $dormitorios = $cantidad;
                            $detalle_rapido_array[] = ['icono' => 'fa-solid fa-bed', 'cantidad' => $cantidad, 'texto' => $texto_descriptivo];
                        } elseif (strpos($icono_slug, 'fa-bath') !== false) {
                            $banos = $cantidad;
                            $detalle_rapido_array[] = ['icono' => 'fa-solid fa-bath', 'cantidad' => $cantidad, 'texto' => $texto_descriptivo];
                        } elseif (strpos($icono_slug, 'ruler-combined') !== false) {
                             $areas = $cantidad;
                             $detalle_rapido_array[] = ['icono' => 'fa-solid fa-ruler-combined', 'cantidad' => $cantidad, 'texto' => $texto_descriptivo ?: 'm²']; 
                        }
                    }
                }
            }
            
            // Asegurarse de que el array tenga la estructura correcta para JS si no se leyó bien del Repeater:
            if (empty($detalle_rapido_array)) {
                 $detalle_rapido_array = [
                    ['icono' => 'fa-solid fa-bed', 'cantidad' => $dormitorios, 'texto' => ''],
                    ['icono' => 'fa-solid fa-bath', 'cantidad' => $banos, 'texto' => ''],
                    ['icono' => 'fa-solid fa-ruler-combined', 'cantidad' => $areas, 'texto' => 'm²'],
                ];
            }


            $mapData[] = [
                'id'             => $id,
                'title'          => get_the_title($id),
                'permalink'      => get_permalink($id),
                'lat'            => $lat,
                'lng'            => $lng,
                'image'          => $image,
                'tipo_operacion' => $tipo_operacion,
                'badge_class'    => $badge_class,
                'detalle_rapido' => $detalle_rapido_array,
                'precio'         => get_field('precio', $id),
            ];
        }
    }

    // Pasar datos a JS (Nota: la localización se realiza en functions.php)
    ?>


    <div class="container-fluid">
        <div class="row" id="map-posts-row">
            <div id="post-list" class="col-12 col-md-6 ps-3 pe-2">
                <div id="posts-cards" class="row row-cols-1 row-cols-md-2 g-3">
                </div>
            </div>
            <div class="col-12 col-md-6 p-0" id="map-container">
                <div id="map"></div>
            </div>
        </div>
    </div>

</main>


<?php get_footer(); ?>