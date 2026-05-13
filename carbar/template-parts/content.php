<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Carbar
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('body'); ?>>
	<div class="container">
		<div class="titulo-precio">
			<h1><?php the_title(); ?></h1>
		<?php
    $precio_uf = get_field('precio');

    // Llamada segura a la API de UF
    $response = wp_remote_get('https://mindicador.cl/api/uf');

if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {
    $body = wp_remote_retrieve_body($response);
    $uf_data = json_decode($body, true);
    
    if (isset($uf_data['serie'][0]['valor'])) {
        $valor_uf = $uf_data['serie'][0]['valor'];
        $precio_clp = $precio_uf * $valor_uf;
    } else {
        $valor_uf = 0;
        $precio_clp = 0;
    }
} else {
    $valor_uf = 0;
    $precio_clp = 0;
}
?>

<div class="precios d-flex flex-column">
    <div>UF <?php echo number_format($precio_uf, 2, ',', '.'); ?></div>
    <div>CLP $<?php echo number_format($precio_clp, 0, ',', '.'); ?></div>
</div>

		</div>

		<div class="badges">
			<?php
			// Tipo de Operación
			$tipos_operacion = get_the_terms(get_the_ID(), 'tipo_operacion');
			if ($tipos_operacion && !is_wp_error($tipos_operacion)) {
				foreach ($tipos_operacion as $operacion) {
					echo '<div class="badge tipo-operacion">' . esc_html($operacion->name) . '</div>';
				}
			}

			// Tipo de Propiedad
			$tipos_propiedad = get_the_terms(get_the_ID(), 'tipo_propiedad');
			if ($tipos_propiedad && !is_wp_error($tipos_propiedad)) {
				foreach ($tipos_propiedad as $propiedad) {
					echo '<div class="badge tipo-propiedad">' . esc_html($propiedad->name) . '</div>';
				}
			}
			?>
		</div>

		<div class="detalles-rapidos">
			<?php
			if (have_rows('detalle_rapido')):
				while (have_rows('detalle_rapido')) : the_row();

					$icono = get_sub_field('iconos');
					$numero = get_sub_field('cantidad__numero');
					$texto = get_sub_field('texto');

			?>

					<div class="detalle-item d-flex flex-column"><i class="<?php echo esc_attr($icono) ?>"></i> <?php echo esc_attr($numero); ?> <?php echo esc_attr($texto) ?></div>
			<?php

				endwhile;
			else :

			endif;
			?>
		</div>

		<div class="row g-4 align-items-stretch layout-propiedad">
			<div class="col-lg-7">
				<!-- Carrusel -->

				<?php
				$imagenes = get_field('galeria');
				if ($imagenes) {
					$chunks = array_chunk($imagenes, 6);
					echo '<div class="swiper mySwiper"><div class="swiper-wrapper">';

					foreach ($chunks as $grupo) {
						echo '<div class="swiper-slide"><div class="masonry-grid">';
						foreach ($grupo as $img) {
							$url = $img['imagen']['url'];
							$alt = $img['imagen']['alt'] ?: 'Imagen';
							echo '<img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '">';
						}
						echo '</div></div>';
					}

					echo '</div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>';
				}
				?>

			</div>
		</div>

		<div class="col-lg-5 d-flex flex-column justify-content-between info-propiedad">
			<div class="card cerca-de flex-grow-1 mb-3">
				<div class="card-body">

					<h2>Cerca de</h2>
					<div class="detalles">
						<?php
						if (have_rows('cerca_de')):
							while (have_rows('cerca_de')) : the_row();

								$icono = get_sub_field('iconos');
								$texto = get_sub_field('texto');
								$distancia = get_sub_field('distancia');

						?>

								<div class="detalle-item d-flex flex-column gap-2">
									<i class="<?php echo esc_attr($icono) ?>"></i>
									<?php echo esc_attr($texto); ?> 
									<span class="distancia"><?php echo esc_attr($distancia) ?></span>
								</div>
						<?php

							endwhile;
						else :

						endif;
						?>
					</div>
				</div>
			</div>


			<div class="card publicado-en">
				<div class="card-body">
					<h2>Publicado en:</h2>

					<div class="logos">
						<!-- primer ícono con modal -->
						<div class="logo-item" id="openModal">
							<?php
							$image = get_field('imagen_de_mas_portales');
							if (!empty($image)): ?>
								<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
							<?php endif; ?>
						</div>

						<!-- Otros íconos con link directo -->
						<?php
						if (have_rows('portales_publicados')):
							while (have_rows('portales_publicados')) : the_row();

								$url = get_sub_field('url_portal');
								$nombre = get_sub_field('nombre_portal');
								$imagen_array = get_sub_field('logo_portal');

								$imagen_url = $imagen_array['url'];

								if ($url && $imagen_url) :
						?>
									<a class="logo-item" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
										<img src="<?php echo esc_url($imagen_url); ?>"
											alt="<?php echo esc_attr($nombre); ?>">
									</a>
						<?php
								endif;

							endwhile;
						else :

						endif;
						?>
					</div>
					<!-- modal de íconos secundarios -->
					<div class="modal" id="modalLogos">
						<div class="modal-content">
							<span class="close-modal">&times;</span>
							<h4>Más portales</h4>
							<div class="modal-logos">
								<?php
								if (have_rows('portales_secundarios')):
									while (have_rows('portales_secundarios')) : the_row();

										$url = get_sub_field('url_portal');
										$nombre = get_sub_field('nombre_portal');
										$imagen_array = get_sub_field('logo_portal');

										$imagen_url = $imagen_array['url'];

										if ($url && $imagen_url) :
								?>
											<a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
												<img src="<?php echo esc_url($imagen_url); ?>"
													alt="<?php echo esc_attr($nombre); ?>">
											</a>
								<?php
										endif;

									endwhile;
								else :

								endif;
								?>
							</div>
						</div>
					</div>
				</div>
			</div>


		</div>


		<div class="desc-mapa">
			<div class="row">
				<section class="mapa col-lg-7">
					<h2>Ubicación</h2>
					<?php
					// Obtener el excerpt (debería tener formato "lat, lng")
					$coords = get_the_excerpt();

					// Verificar que tenga coma
					if (strpos($coords, ',') !== false) {
						$coords = trim($coords);
						list($lat, $lng) = array_map('trim', explode(',', $coords));
					?>

						<div id="map"></div>

						<script>
							document.addEventListener('DOMContentLoaded', function() {
								// Inicializar mapa
								var map = L.map('map').setView([<?php echo esc_js($lat); ?>, <?php echo esc_js($lng); ?>], 15);

								// Cargar capa base (OpenStreetMap)
								L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
									maxZoom: 19,
									attribution: '© OpenStreetMap'
								}).addTo(map);

								// Añadir marcador
								L.marker([<?php echo esc_js($lat); ?>, <?php echo esc_js($lng); ?>])
									.addTo(map)
									.bindPopup('<?php echo esc_html(get_the_title()); ?>')
									.openPopup();
							});
						</script>

					<?php
					} else {
						echo '<p><em>No hay coordenadas disponibles para este inmueble.</em></p>';
					}
					?>

				</section>




				<section class="descripcion col-lg-5">
					<h2>Descripción</h2>
					<?php the_content() ?>
					<button class="ver-mas">Ver más</button>
				</section>
				<!-- Mapa -->

			</div>
		</div>
	</div>

	<div class="lightbox">
		<span class="close">&times;</span>
		<img class="lightbox-img" src="" alt="Imagen ampliada">
		<button class="prev"><i class="fa-solid fa-chevron-left"></i></button>
		<button class="next"><i class="fa-solid fa-chevron-right"></i></button>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->