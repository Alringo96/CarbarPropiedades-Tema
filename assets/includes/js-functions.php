<?php
/**
 * FUNCTIONS.PHP COMPLETO Y FUNCIONAL
 * Filtrado por Taxonomía y Meta Query
 * Copia valores de repeater a campos simples para filtros
 */

// ===============================================
// 🎯 HOOK: COPIAR VALORES DE REPEATER A CAMPOS SIMPLES
// ===============================================
function carbar_update_meta_for_filtering($post_id) {
    if (get_post_type($post_id) !== 'post' || !function_exists('get_field')) {
        return;
    }

    $detalle_rapido_rows = get_field('detalle_rapido', $post_id);
    $dormitorios = 0;
    $banos = 0;

    if ($detalle_rapido_rows && is_array($detalle_rapido_rows)) {
        foreach ($detalle_rapido_rows as $row) {
            $icono = $row['icono'] ?? $row['iconos'] ?? '';
            $cantidad = (int)($row['cantidad'] ?? $row['cantidad__numero'] ?? 0);

            if ($icono && $cantidad > 0) {
                $icono_slug = strtolower($icono);
                if (strpos($icono_slug, 'fa-bed') !== false) {
                    $dormitorios = $cantidad;
                } elseif (strpos($icono_slug, 'fa-bath') !== false) {
                    $banos = $cantidad;
                }
            }
        }
    }

    update_field('num_dormitorios', $dormitorios, $post_id);
    update_field('num_banos', $banos, $post_id);

    error_log("✅ Guardado meta post $post_id: Dormitorios=$dormitorios, Baños=$banos");
}
add_action('acf/save_post', 'carbar_update_meta_for_filtering', 20);

// ===============================================
// 🔹 ENCOLAR SCRIPTS Y CONSULTA DE RESULTADOS
// ===============================================
function carbar_enqueue_scripts() {
    // Encolar scripts principales
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js', array(), null, true);
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js', array(), null, true);
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_script('leaflet', get_template_directory_uri() . '/assets/dist/leaflet-src.js', array(), '1.0.0', true);
    wp_enqueue_script('mapa-js', get_template_directory_uri() . '/assets/librerias/js/mapa.js', array('jquery', 'leaflet'), '1.0.4', true);

    // Otros scripts
    $otros_scripts = ['botones-flotante','carrusel','descripcion','filtro-de-propiedades','img','lightbox','menu','modal-detalle','nosotros','servicios','tarjeta','ano'];
    foreach ($otros_scripts as $script) {
        wp_enqueue_script($script, get_template_directory_uri() . "/assets/librerias/js/{$script}.js", array(), '1.0', true);
    }

    wp_localize_script('tarjeta', 'carbarData', array('theme_url' => get_template_directory_uri()));

    // ===============================================
    // 🔹 FILTRO Y LOCALIZACIÓN DE RESULTADOS
    // ===============================================
    if (!is_page('resultados')) return;

    // Recoger filtros de URL
    $operacion       = isset($_GET['operacion']) ? sanitize_text_field($_GET['operacion']) : '';
    $propiedad       = isset($_GET['propiedad']) ? sanitize_text_field($_GET['propiedad']) : '';
    $comuna_input    = isset($_GET['comuna']) ? sanitize_text_field($_GET['comuna']) : '';
    $estado_tag      = isset($_GET['estado']) ? sanitize_text_field($_GET['estado']) : '';
    $min_price       = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
    $max_price       = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 0;
    $min_dormitorios = isset($_GET['min_dormitorios']) ? intval($_GET['min_dormitorios']) : 0;
    $min_banos       = isset($_GET['min_banos']) ? intval($_GET['min_banos']) : 0;

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ];

    $tax_query = ['relation' => 'AND'];
    $meta_query = ['relation' => 'AND'];

    // Filtros de taxonomía
    if (!empty($operacion)) { $tax_query[] = ['taxonomy'=>'tipo_operacion','field'=>'slug','terms'=>$operacion]; }
    if (!empty($propiedad)) { $tax_query[] = ['taxonomy'=>'tipo_propiedad','field'=>'slug','terms'=>$propiedad]; }
    if (!empty($estado_tag)) { $tax_query[] = ['taxonomy'=>'post_tag','field'=>'slug','terms'=>$estado_tag]; }

    // Filtro de comuna
    if ($comuna_input) {
        $comuna_slug = sanitize_title($comuna_input);
        $terms = get_terms(['taxonomy'=>'category','hide_empty'=>false,'slug'=>$comuna_slug]);
        if (empty($terms)) {
            $terms_by_name = get_terms(['taxonomy'=>'category','hide_empty'=>false,'name__like'=>$comuna_input,'number'=>1]);
            if (!empty($terms_by_name)) $terms = $terms_by_name;
        }
        if (!empty($terms)) {
            $term_ids = wp_list_pluck($terms,'term_id');
            $tax_query[] = ['taxonomy'=>'category','field'=>'term_id','terms'=>$term_ids];
        } else {
            $args['post__in'] = [0];
        }
    }

    // Filtro de precio
    if ($min_price > 0 || $max_price > 0) {
        $value_array = []; $compare = '';
        if ($min_price > 0 && $max_price > 0) { $compare='BETWEEN'; $value_array=[$min_price,$max_price]; } 
        elseif ($min_price > 0) { $compare='>='; $value_array=$min_price; } 
        elseif ($max_price > 0) { $compare='<='; $value_array=$max_price; }

        if (!empty($value_array)) {
            $meta_query[] = ['key'=>'precio','value'=>$value_array,'type'=>'DECIMAL','compare'=>$compare];
        }
    }

    // Filtro Dormitorios y Baños
    if ($min_dormitorios > 0) {
        $meta_query[] = ['key'=>'num_dormitorios','value'=>$min_dormitorios,'type'=>'NUMERIC','compare'=>'>='];
    }
    if ($min_banos > 0) {
        $meta_query[] = ['key'=>'num_banos','value'=>$min_banos,'type'=>'NUMERIC','compare'=>'>='];
    }

    if (count($tax_query) > 1) $args['tax_query'] = $tax_query;
    if (count($meta_query) > 1) $args['meta_query'] = $meta_query;

    $posts = get_posts($args);

    // Preparar datos para mapa y galería
    $markers_data = [];
    $property_images_js = [];

    foreach ($posts as $post) {
        $id = $post->ID;
        $lat = get_field('latitud',$id); 
        $lng = get_field('longitud',$id);

        if (!$lat || !$lng) {
            $excerpt = get_the_excerpt($post);
            if (preg_match('/(-?\d+\.\d+),\s*(-?\d+\.\d+)/',$excerpt,$matches)){
                $lat = floatval($matches[1]);
                $lng = floatval($matches[2]);
            }
        }
        if (!$lat || !$lng) continue;

        $terms = wp_get_post_terms($id,'tipo_operacion');
        $tipo_operacion = !empty($terms) ? $terms[0]->name : '';
        $badge_class = match($tipo_operacion){
            'Venta' => 'bg-success',
            'Arriendo' => 'bg-primary',
            'Arriendo temporal' => 'bg-warning text-dark',
            default => 'bg-secondary',
        };

        $precio = get_field('precio',$id);

        // Galería de imágenes
        $imgs_array = [];
        $galeria_rows = get_field('galeria',$id);
        if ($galeria_rows && is_array($galeria_rows)) {
            foreach ($galeria_rows as $row) {
                $img_data = $row['imagen'] ?? null;
                if ($img_data && is_array($img_data)) {
                    $imgs_array[] = $img_data['sizes']['medium'] ?? $img_data['url'] ?? '';
                }
            }
        }
        if (empty($imgs_array)) {
            $featured_image = get_the_post_thumbnail_url($id,'medium') ?: 'https://via.placeholder.com/350x200?text=Sin+imagen';
            $imgs_array[] = $featured_image;
        }
        $property_images_js[$id] = $imgs_array;
        $imagen_url = $imgs_array[0];

        // Detalle rápido
        $detalle_rapido_rows_data = get_field('detalle_rapido',$id);
        $dormitorios = $banos = $areas = 0;
        $texto_dormitorios = $texto_banos = $texto_areas = '';
        if ($detalle_rapido_rows_data && is_array($detalle_rapido_rows_data)) {
            foreach ($detalle_rapido_rows_data as $row) {
                $texto_descriptivo = $row['texto'] ?? '';
                $icono = $row['iconos'] ?? $row['icono'] ?? '';
                $cantidad = (int)($row['cantidad__numero'] ?? $row['cantidad'] ?? 0);
                if ($icono && $cantidad > 0) {
                    $icono_slug = strtolower($icono);
                    if (strpos($icono_slug,'fa-bed')!==false){$dormitorios=$cantidad;$texto_dormitorios=$texto_descriptivo;}
                    elseif(strpos($icono_slug,'fa-bath')!==false){$banos=$cantidad;$texto_banos=$texto_descriptivo;}
                    elseif(strpos($icono_slug,'ruler-combined')!==false){$areas=$cantidad;$texto_areas=$texto_descriptivo;}
                }
            }
        }

        $detalle_rapido = [
            ['icono'=>'fa-solid fa-bed','cantidad'=>$dormitorios,'texto'=>$texto_dormitorios],
            ['icono'=>'fa-solid fa-bath','cantidad'=>$banos,'texto'=>$texto_banos],
            ['icono'=>'fa-solid fa-ruler-combined','cantidad'=>$areas,'texto'=>$texto_areas ?: 'm²'],
        ];

        $propiedad_slug = wp_get_post_terms($id,'tipo_propiedad')[0]->slug ?? '';
        $tags_slugs = wp_list_pluck(wp_get_post_terms($id,'post_tag'),'slug');

        $markers_data[] = [
            'id'=>$id,
            'title'=>get_the_title($id),
            'permalink'=>get_permalink($id),
            'lat'=>$lat,
            'lng'=>$lng,
            'image'=>$imagen_url,
            'precio'=>$precio ?: '',
            'tipo_operacion'=>$tipo_operacion,
            'badge_class'=>$badge_class,
            'detalle_rapido'=>$detalle_rapido,
            'propiedad_slug'=>$propiedad_slug,
            'tags'=>$tags_slugs,
        ];
    }

    wp_localize_script('mapa-js','mapData',$markers_data);
    wp_localize_script('mapa-js','propertyImages',$property_images_js);
}
add_action('wp_enqueue_scripts','carbar_enqueue_scripts');
