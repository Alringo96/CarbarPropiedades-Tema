<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Carbar
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('body'); ?>>
  <!-- HEADER QUIÉNES SOMOS -->
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


  <!-- SECCIÓN NOSOTROS -->
  <section class="about-section">
    <div class="about-container"></div>
      <h2><?php echo the_field('titulo_seccion_2'); ?></h2>
      <p><?php echo the_field('texto_seccion_2'); ?></p>
    </div>
  </section>

<!-- SECCIÓN MISIÓN Y VISIÓN -->
<?php 
$mission_text = get_field('mision'); 
$vision_text = get_field('vision'); 
?>

<section class="mission-vision">
  <div class="mv-container">
    <div class="mv-left">
      <button class="mv-tab active" data-target="mision"><?php echo the_field('titulo_mision') ?></button>
      <div class="divider"></div>
      <button class="mv-tab" data-target="vision"><?php echo the_field('titulo_vision') ?></button>
    </div>
    <div class="mv-separator">
      <span class="dot active"></span>
      <span class="dot"></span>
    </div>
    <div class="mv-right">
      <p id="mv-text"><?php echo esc_html($mission_text); ?></p>
    </div>
  </div>
</section>

<script>
const textos = {
  mision: <?php echo json_encode($mission_text); ?>,
  vision: <?php echo json_encode($vision_text); ?>
};
</script>


</article><!-- #post-<?php the_ID(); ?> -->