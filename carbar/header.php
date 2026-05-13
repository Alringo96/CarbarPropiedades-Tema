<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Carbar
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
<meta name="description" content="Corredora chilena enfocada en gestión inmobiliaria transparente, asesoría personalizada y conexión con oportunidades confiables de compra, venta y arriendo.">
<meta name="author" content="RedMouse Agencia de Diseño y Desarrollo Web: Angelo Veloso, Valentina Tapia, Antonia Palma, Gustavo Alringo, Juan Cavieres">
<meta name="keywords" content="corredora de propiedades, inmobiliaria Chile, compra de propiedades, venta de propiedades, arriendo de inmuebles, gestión inmobiliaria, propiedades en venta, propiedades en arriendo, corretaje, inversión inmobiliaria, tasación, Carbar Propiedades, bienes raíces Chile">

    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'carbar'); ?></a>




        <!-- NAV -->
        <nav class="navbar">
            <div class="logo">
                <?php the_custom_logo() ?>
            </div>
            <div class="menu-btn" id="menuBtn">MENÚ</div>

             <?php include get_template_directory() . '/assets/includes/nav-desk.php'; ?>
        </nav>
    </div>