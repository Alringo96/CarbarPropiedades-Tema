<?php 
function crear_post_type_nombre() {
    $labels = array(
        'name'                  => 'Nombres',
        'singular_name'         => 'Nombre',
        'menu_name'             => 'Nombres',
        'name_admin_bar'        => 'Nombre',
        'add_new'               => 'Agregar nuevo',
        'add_new_item'          => 'Agregar nuevo Nombre',
        'new_item'              => 'Nuevo Nombre',
        'edit_item'             => 'Editar Nombre',
        'view_item'             => 'Ver Nombre',
        'all_items'             => 'Todos las Nombres',
        'search_items'          => 'Buscar Nombres',
        'not_found'             => 'No se encontraron Nombres',
        'not_found_in_trash'    => 'No se encontraron Nombres en la papelera',
        'parent_item_colon'     => '',
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'has_archive'       => true,
        'rewrite'           => array('slug' => 'nombres'),
        'show_in_rest'      => true, // Habilita el editor Gutenberg
        'supports'          => array('title','excerpt', 'thumbnail' ),
        'taxonomies'        => array(),
        'show_in_menu'      => true,
        'menu_icon'         => 'dashicons-universal-access',
        'show_ui'           => true,
    );

    register_post_type('nombres', $args);
}
add_action('init', 'crear_post_type_nombres');

// Nombre de función, slug, register y add deben estar sin mayúsculas.
// Ademas el nombre de register es para conectar con otros archivos