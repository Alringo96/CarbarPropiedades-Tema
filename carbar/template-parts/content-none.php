<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Carbar
 */

?>

<section class="no-results not-found">
<main id="primary" class="site-main">

	<div class="contenedor-hero">
		<div class="seccion-1-texto">
			<h1><?php the_title(); ?></h1>
			<p><span class="inicio">Inicio / </span> <span class="resultado">Resultados</span></p>
		</div>
		<?php
		$image = get_field('hero');
		if (!empty($image)): ?>
			<img class="img-hero" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
		<?php endif; ?>
	</div>

	<div class="filtros ms-3 d-flex justify-content-start gap-3 my-4">
		<input type="text" placeholder=" nuble">


		<button>Departamento</button>
		<button>Nuevo/Usado</button>
		<button>Precio</button>
		<button>Dormitorios</button>
		<button>Baños</button>
		<button>Mas filtros</button>
		<button>Guardar busqueda</button>

	</div>

	<!-- Fila para el botón de mostrar/ocultar mapa -->
	<div class="row mb-2 me-2" id="map-controls">
		<div class="col-12 text-end">
			<button id="toggle-map-btn" class="btn btn-primary">Ver lista</button>
		</div>
	</div>

	<div class="container-fluid">

		<!-- Fila principal con el listado de posts y el mapa -->
		<div class="row" id="map-posts-row">

			<!-- Columna para mostrar las tarjetas de posts -->
			<div id="post-list" class="col-12 col-md-6 ps-3 pe-2">
				<div id="posts-cards" class="row row-cols-1 row-cols-md-3 g-3"></div>
			</div>

			<!-- Columna para el mapa -->
			<div class="col-12 col-md-6 p-0" id="map-container">
				<div id="map"></div>
			</div>

		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Inicializar el mapa con vista centrada en Chile (ejemplo)
			let map = L.map('map').setView([-33.4297, -70.6469], 3);

			// Añadir capa de OpenStreetMap
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 19,
				attribution: '&copy; OpenStreetMap contributors'
			}).addTo(map);

			// Obtener datos de las entradas con coordenadas del PHP (JSON)
			let markersData = <?php
								$posts = get_posts([
									'posts_per_page' => -1,
									'post_type' => 'post',
									'post_status' => 'publish',
									'meta_query' => [],
								]);

								$coords_array = [];

								foreach ($posts as $post) {
									$excerpt = get_the_excerpt($post);

									// Extraer coordenadas latitud y longitud del excerpt con regex
									if (preg_match('/(-?\d+\.\d+),\s*(-?\d+\.\d+)/', $excerpt, $matches)) {
										$lat = floatval($matches[1]);
										$lng = floatval($matches[2]);
										$title = esc_js(get_the_title($post));
										$permalink = get_permalink($post);

										// Obtener imagen destacada o imagen placeholder
										if (has_post_thumbnail($post)) {
											$img_url = get_the_post_thumbnail_url($post, 'medium');
										} else {
											$img_url = 'https://via.placeholder.com/350x200?text=Sin+imagen';
										}

										// Guardar datos en arreglo para pasar a JS
										$coords_array[] = [
											'lat' => $lat,
											'lng' => $lng,
											'title' => $title,
											'permalink' => $permalink,
											'image' => $img_url,
										];
									}
								}

								echo json_encode($coords_array);
								?>;

			let leafletMarkers = []; // Array para guardar marcadores y datos
			let postsContainer = document.getElementById('posts-cards'); // Contenedor para tarjetas

			// Función para actualizar las entradas visibles según el área visible del mapa
			function updateVisiblePosts() {
				let bounds = map.getBounds(); // Obtener límites visibles del mapa
				postsContainer.innerHTML = ''; // Limpiar contenedor

				leafletMarkers.forEach(function(obj) {
					let marker = obj.marker;
					let data = obj.data;

					// Mostrar solo las entradas cuyas coordenadas están dentro del área visible
					if (bounds.contains(marker.getLatLng())) {
						let col = document.createElement('div');
						col.className = 'col';

						// Crear tarjeta para cada entrada
						let card = document.createElement('div');
						card.className = 'card h-100';
						card.style.cursor = 'pointer';

						// Al hacer click, centrar el mapa en el marcador y abrir popup
						card.onclick = function() {
							window.open(data.permalink); // o window.location.href si quieres abrir en la misma pestaña
						};

						let img = document.createElement('img');
						img.src = data.image;
						img.className = 'card-img-top';
						img.alt = data.title;

						let cardBody = document.createElement('div');
						cardBody.className = 'card-body';

						let cardTitle = document.createElement('h5');
						cardTitle.className = 'card-title';
						cardTitle.textContent = data.title;

						// Armar estructura de la tarjeta
						cardBody.appendChild(cardTitle);
						card.appendChild(img);
						card.appendChild(cardBody);
						col.appendChild(card);

						// Añadir tarjeta al contenedor
						postsContainer.appendChild(col);
					}
				});
			}

			// Crear marcadores en el mapa a partir de los datos
			markersData.forEach(function(data) {
				let marker = L.marker([data.lat, data.lng])
					.addTo(map)
					.bindPopup('<a href="' + data.permalink + '" target="_blank">' + data.title + '</a>');

				leafletMarkers.push({
					marker: marker,
					data: data
				});
			});

			// Actualizar entradas visibles cuando se termina de mover/zoom el mapa
			map.on('moveend', updateVisiblePosts);

			// Mostrar entradas visibles inicialmente
			updateVisiblePosts();

			// letiables para controlar el botón y los contenedores
			let toggleBtn = document.getElementById('toggle-map-btn');
			let mapDiv = document.getElementById('map-container');
			let postListDiv = document.getElementById('post-list');

			// Manejar clic en botón para mostrar u ocultar mapa
			toggleBtn.addEventListener('click', function() {
				if (mapDiv.style.display === 'none') {
					// Si el mapa está oculto, mostrarlo
					mapDiv.style.display = 'block';
					toggleBtn.textContent = 'Ver lista';

					// Ajustar tamaño del listado para que ocupe la mitad
					postListDiv.classList.remove('col-12');
					postListDiv.classList.add('col-md-6');

					// Informar a Leaflet que cambió tamaño para que redibuje correctamente
					map.invalidateSize();

					// Mostrar solo entradas visibles en el mapa
					updateVisiblePosts();
				} else {
					// Si el mapa está visible, ocultarlo
					mapDiv.style.display = 'none';
					toggleBtn.textContent = 'Mostrar mapa';

					// Ajustar listado para ocupar todo el ancho
					postListDiv.classList.add('col-12');
					postListDiv.classList.remove('col-md-6');

					// Mostrar todas las entradas sin filtro
					postsContainer.innerHTML = '';

					leafletMarkers.forEach(function(obj) {
						let data = obj.data;

						let col = document.createElement('div');
						col.className = 'col';

						// Crear tarjeta de entrada
						let card = document.createElement('div');
						card.className = 'card h-100';
						card.style.cursor = 'pointer';

						// Al hacer click abrir la entrada en nueva pestaña, ya que el mapa no está visible
						card.onclick = function() {
							window.open(data.permalink);
						};

						let img = document.createElement('img');
						img.src = data.image;
						img.className = 'card-img-top';
						img.alt = data.title;

						let cardBody = document.createElement('div');
						cardBody.className = 'card-body';

						let cardTitle = document.createElement('h5');
						cardTitle.className = 'card-title';
						cardTitle.textContent = data.title;

						// Armar estructura de la tarjeta
						cardBody.appendChild(cardTitle);
						card.appendChild(img);
						card.appendChild(cardBody);
						col.appendChild(card);

						// Añadir tarjeta al contenedor
						postsContainer.appendChild(col);
					});
				}
			});
		});
	</script>
</main><!-- #primary -->
</section><!-- .no-results -->
