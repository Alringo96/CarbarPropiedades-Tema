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
	<div class="contenedor-hero">
		<div class="seccion-1-texto">
			<h1><?php the_title(); ?></h1>
			<p><span class="inicio"><?php the_field('inicio') ?></span> <span class="text-white">/</span> <span
					class="resultado"><?php the_field('titulo_de_la_pagina') ?></span></p>
		</div>
		<?php
		$image = get_field('hero');
		if (!empty($image)): ?>
			<img class="img-hero" src="<?php echo esc_url($image['url']); ?>"
				alt="<?php echo esc_attr($image['alt']); ?>" />
		<?php endif; ?>
	</div>

	<section class="servicios">
		<div class="container-fluid px-0">


			<!-- Servicio 1 -->
			<div id="servicio-1" class="servicio impar fade-section">

				<!-- Imagen -->
				<?php if (have_rows('imagen_1')): ?>
					<?php while (have_rows('imagen_1')):
						the_row();
						$img_subida = get_sub_field('subir_imagen');
						$img_url = get_sub_field('imagen_url');
						$imagen = $img_subida ? $img_subida : $img_url;
						?>
						<?php if ($imagen): ?>
							<img class="servicio-img" src="<?php echo esc_url($imagen); ?>" alt="">
						<?php endif; ?>
					<?php endwhile; ?>
				<?php endif; ?>

				<!-- Texto -->
				<div class="texto-servicio">
					<h3 class="titulo-servicio"><?php the_field('titulo_servicio_1'); ?></h3>
					<p class="descripcion-servicio"><?php the_field('titulo_descripcion_1'); ?></p>

					<ul>
						<?php if (have_rows('lista_1')):
							while (have_rows('lista_1')):
								the_row(); ?>
								<li><?php echo esc_attr(get_sub_field('elemento')); ?></li>
							<?php endwhile;
						endif; ?>
					</ul>

					<p class="valor-servicio"><?php the_field('valor_del_servicio_1'); ?></p>
				</div>
			</div>


			<!-- Servicio 2 -->
			<div id="servicio-2" class="servicio par fade-section">

				<!-- Imagen -->
				<?php if (have_rows('imagen_2')): ?>
					<?php while (have_rows('imagen_2')):
						the_row();
						$img_subida = get_sub_field('subir_imagen');
						$img_url = get_sub_field('imagen_url');
						$imagen = $img_subida ? $img_subida : $img_url;
						?>
						<?php if ($imagen): ?>
							<img class="servicio-img" src="<?php echo esc_url($imagen); ?>" alt="">
						<?php endif; ?>
					<?php endwhile; ?>
				<?php endif; ?>

				<!-- Texto -->
				<div class="texto-servicio">
					<h3 class="titulo-servicio"><?php the_field('titulo_servicio_2'); ?></h3>
					<p class="descripcion-servicio"><?php the_field('titulo_descripcion_2'); ?></p>

					<ul>
						<?php if (have_rows('lista_2')):
							while (have_rows('lista_2')):
								the_row(); ?>
								<li><?php echo esc_attr(get_sub_field('elemento')); ?></li>
							<?php endwhile;
						endif; ?>
					</ul>

					<p class="valor-servicio"><?php the_field('valor_del_servicio_2'); ?></p>
				</div>
			</div>


			<!-- Servicio 3 -->
			<div id="servicio-3" class="servicio impar fade-section">

				<!-- Imagen -->
				<?php if (have_rows('imagen_3')): ?>
					<?php while (have_rows('imagen_3')):
						the_row();
						$img_subida = get_sub_field('subir_imagen');
						$img_url = get_sub_field('imagen_url');
						$imagen = $img_subida ? $img_subida : $img_url;
						?>
						<?php if ($imagen): ?>
							<img class="servicio-img" src="<?php echo esc_url($imagen); ?>" alt="">
						<?php endif; ?>
					<?php endwhile; ?>
				<?php endif; ?>

				<!-- Texto -->
				<div class="texto-servicio">
					<h3 class="titulo-servicio"><?php the_field('titulo_servicio_3'); ?></h3>
					<p class="descripcion-servicio"><?php the_field('titulo_descripcion_3'); ?></p>

					<ul>
						<?php if (have_rows('lista_3')):
							while (have_rows('lista_3')):
								the_row(); ?>
								<li><?php echo esc_attr(get_sub_field('elemento')); ?></li>
							<?php endwhile;
						endif; ?>
					</ul>

					<p class="valor-servicio"><?php the_field('valor_del_servicio_3'); ?></p>
				</div>
			</div>

			<!-- Servicio 4 -->
			<div id="servicio-4" class="servicio par fade-section">

				<!-- Imagen -->
				<?php if (have_rows('imagen_4')): ?>
					<?php while (have_rows('imagen_4')):
						the_row();
						$img_subida = get_sub_field('subir_imagen');
						$img_url = get_sub_field('imagen_url');
						$imagen = $img_subida ? $img_subida : $img_url;
						?>
						<?php if ($imagen): ?>
							<img class="servicio-img" src="<?php echo esc_url($imagen); ?>" alt="">
						<?php endif; ?>
					<?php endwhile; ?>
				<?php endif; ?>

				<!-- Texto -->
				<div class="texto-servicio">
					<h3 class="titulo-servicio"><?php the_field('titulo_servicio_4'); ?></h3>
					<p class="descripcion-servicio"><?php the_field('titulo_descripcion_4'); ?></p>

					<ul>
						<?php if (have_rows('lista_4')):
							while (have_rows('lista_4')):
								the_row(); ?>
								<li><?php echo esc_attr(get_sub_field('elemento')); ?></li>
							<?php endwhile;
						endif; ?>
					</ul>

					<p class="valor-servicio"><?php the_field('valor_del_servicio_4'); ?></p>
				</div>
			</div>

		</div>
	</section>
</article><!-- #post-<?php the_ID(); ?> -->