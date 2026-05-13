<?php
function personalizar_taxonomias_inmobiliarias() {
    // 🔹 1. Eliminar la taxonomía original de categorías
    unregister_taxonomy('category');

    // 🔹 2. Registrar "Regiones" (como categoría jerárquica)
    $labels_region = array(
        'name'              => 'Regiones',
        'singular_name'     => 'Región',
        'search_items'      => 'Buscar Regiones',
        'all_items'         => 'Todas las Regiones',
        'edit_item'         => 'Editar Región',
        'update_item'       => 'Actualizar Región',
        'add_new_item'      => 'Agregar nueva Región',
        'new_item_name'     => 'Nueva Región',
        'menu_name'         => 'Regiones',
    );

    register_taxonomy('category', array('post'), array(
        'hierarchical'      => true,
        'labels'            => $labels_region,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_quick_edit'=> true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'region'),
    ));

    // 🔹 3. Tipo de Operación
    $labels_operacion = array(
        'name'          => 'Tipos de Operación',
        'singular_name' => 'Tipo de Operación',
        'menu_name'     => 'Tipo de Operación',
    );

    register_taxonomy('tipo_operacion', array('post'), array(
        'hierarchical'      => true,
        'labels'            => $labels_operacion,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'tipo-operacion'),
    ));

    // 🔹 4. Tipo de Propiedad
    $labels_prop = array(
        'name'          => 'Tipos de Propiedad',
        'singular_name' => 'Tipo de Propiedad',
        'menu_name'     => 'Tipo de Propiedad',
    );

    register_taxonomy('tipo_propiedad', array('post'), array(
        'hierarchical'      => true,
        'labels'            => $labels_prop,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'tipo-propiedad'),
    ));
}
add_action('init', 'personalizar_taxonomias_inmobiliarias', 11);



// 🔹 Agregar automáticamente las regiones y comunas
function insertar_regiones_y_comunas_en_categorias() {
    if (get_option('regiones_comunas_insertadas')) return;

    $regiones = array(
        'Región de Arica y Parinacota' => ['Arica', 'Camarones', 'Putre', 'General Lagos'],
        'Región de Tarapacá' => ['Iquique', 'Alto Hospicio', 'Pozo Almonte', 'Camiña', 'Colchane', 'Huara', 'Pica'],
        'Región de Antofagasta' => ['Antofagasta', 'Mejillones', 'Sierra Gorda', 'Taltal', 'Calama', 'Ollagüe', 'San Pedro de Atacama'],
        'Región de Atacama' => ['Copiapó', 'Caldera', 'Tierra Amarilla', 'Chañaral', 'Diego de Almagro', 'Vallenar', 'Freirina', 'Huasco', 'Alto del Carmen'],
        'Región de Coquimbo' => ['La Serena', 'Coquimbo', 'Andacollo', 'La Higuera', 'Paiguano', 'Vicuña', 'Illapel', 'Los Vilos', 'Salamanca', 'Ovalle', 'Combarbalá', 'Monte Patria', 'Punitaqui', 'Río Hurtado'],
        'Región de Valparaíso' => ['Valparaíso', 'Viña del Mar', 'Concón', 'Quilpué', 'Villa Alemana', 'Casablanca', 'Quillota', 'La Calera', 'Hijuelas', 'Nogales', 'San Antonio', 'Cartagena', 'El Quisco', 'El Tabo', 'Santo Domingo', 'San Felipe', 'Llaillay', 'Catemu', 'Santa María', 'Los Andes', 'Calle Larga', 'Rinconada', 'Putaendo'],
        'Región Metropolitana' => ['Santiago', 'Providencia', 'Las Condes', 'Maipú', 'La Florida', 'Puente Alto', 'Ñuñoa', 'Recoleta', 'San Bernardo', 'Peñalolén', 'Estación Central', 'Independencia', 'Lo Barnechea', 'Macul', 'Huechuraba', 'Cerrillos', 'El Bosque', 'Pedro Aguirre Cerda', 'Cerro Navia', 'La Pintana', 'Lo Espejo', 'La Granja', 'San Joaquín', 'Quilicura', 'Conchalí'],
        'Región del Libertador General Bernardo O’Higgins' => ['Rancagua', 'Machalí', 'Graneros', 'Rengo', 'San Vicente', 'San Fernando', 'Santa Cruz', 'Nancagua', 'Palmilla', 'Peralillo', 'Chimbarongo'],
        'Región del Maule' => ['Talca', 'Curicó', 'Linares', 'Cauquenes', 'San Clemente', 'Maule', 'San Javier', 'Constitución', 'Parral', 'Retiro', 'Rauco'],
        'Región de Ñuble' => ['Chillán', 'Chillán Viejo', 'San Carlos', 'Coihueco', 'Bulnes', 'Quirihue', 'Ninhue', 'Trehuaco', 'San Fabián'],
        'Región del Biobío' => ['Concepción', 'Talcahuano', 'Hualpén', 'Chiguayante', 'San Pedro de la Paz', 'Lota', 'Coronel', 'Tomé', 'Los Ángeles', 'Nacimiento', 'Mulchén', 'Cabrero'],
        'Región de La Araucanía' => ['Temuco', 'Padre Las Casas', 'Villarrica', 'Pucón', 'Angol', 'Victoria', 'Traiguén', 'Gorbea', 'Lautaro', 'Freire'],
        'Región de Los Ríos' => ['Valdivia', 'Lanco', 'Panguipulli', 'La Unión', 'Río Bueno', 'Futrono', 'Paillaco'],
        'Región de Los Lagos' => ['Puerto Montt', 'Puerto Varas', 'Osorno', 'Castro', 'Ancud', 'Quellón', 'Chonchi', 'Frutillar'],
        'Región de Aysén del General Carlos Ibáñez del Campo' => ['Coyhaique', 'Aysén', 'Chile Chico', 'Cochrane'],
        'Región de Magallanes y la Antártica Chilena' => ['Punta Arenas', 'Puerto Natales', 'Porvenir', 'Cabo de Hornos'],
    );

    foreach ($regiones as $region => $comunas) {
        $region_term = term_exists($region, 'category');
        if (!$region_term) {
            $region_term = wp_insert_term($region, 'category');
        }

        if (!is_wp_error($region_term)) {
            $region_id = is_array($region_term) ? $region_term['term_id'] : $region_term;
            foreach ($comunas as $comuna) {
                if (!term_exists($comuna, 'category')) {
                    wp_insert_term($comuna, 'category', array('parent' => $region_id));
                }
            }
        }
    }

    update_option('regiones_comunas_insertadas', true);
}
add_action('init', 'insertar_regiones_y_comunas_en_categorias', 20);


// --- AJAX para cargar comunas según la región seleccionada ---
add_action('wp_ajax_get_comunas', 'get_comunas_ajax');
add_action('wp_ajax_nopriv_get_comunas', 'get_comunas_ajax');

function get_comunas_ajax() {
    $region_id = intval($_GET['region_id']);

    $comunas = get_terms(array(
        'taxonomy' => 'category',
        'parent' => $region_id,
        'hide_empty' => false,
    ));

    wp_send_json($comunas);
}
