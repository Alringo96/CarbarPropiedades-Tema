document.addEventListener('DOMContentLoaded', function () {

    // === Variables globales y Data ===
    let markersData = typeof mapData !== 'undefined' ? mapData : [];
    const propertyImgsData = typeof propertyImages !== 'undefined' ? propertyImages : {};

    let leafletMarkers = [];
    let postsContainer = document.getElementById('posts-cards');

    // === LÓGICA DE DELEGACIÓN DE EVENTOS PARA EL CARRUSEL ===
    const currentImageIndex = {};
    Object.keys(propertyImgsData).forEach(id => currentImageIndex[id] = 0);

    document.addEventListener('click', function (e) {
        const arrow = e.target.closest('.arrow');
        if (!arrow) return;

        e.stopPropagation();

        const imgElement = arrow.closest('.property-image').querySelector('img');
        if (!imgElement) return;

        const imgId = imgElement.id.replace('property-img-', '');
        const isLeft = arrow.classList.contains('left');
        const imgs = propertyImgsData[imgId];

        if (!imgs || imgs.length <= 1) return;

        currentImageIndex[imgId] =
            (currentImageIndex[imgId] + (isLeft ? -1 : 1) + imgs.length) % imgs.length;

        imgElement.src = imgs[currentImageIndex[imgId]];
    });
    // === FIN LÓGICA DE DELEGACIÓN ===

    // === Inicializar mapa ===
    let map = L.map('map').setView([-33.43531, -70.67625], 9);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // === Función para renderizar tarjeta ===
    function renderCard(data) {
        let col = document.createElement('div');
        col.className = 'col mb-4';

        let card = document.createElement('div');
        card.className = 'property-card';

        let imageDiv = document.createElement('div');
        imageDiv.className = 'property-image position-relative';

        let img = document.createElement('img');
        img.id = 'property-img-' + data.id;
        img.src = data.image;
        img.className = 'w-100';
        img.alt = data.title;
        imageDiv.appendChild(img);

        const propertyImgs = propertyImgsData[data.id];
        if (propertyImgs && propertyImgs.length > 1) {
            let leftArrow = document.createElement('i');
            leftArrow.className = 'fa-solid fa-chevron-left arrow left';
            let rightArrow = document.createElement('i');
            rightArrow.className = 'fa-solid fa-chevron-right arrow right';

            imageDiv.appendChild(leftArrow);
            imageDiv.appendChild(rightArrow);
            imageDiv.classList.add('has-carousel');
        }

        if (data.tipo_operacion) {
            let badgesDiv = document.createElement('div');
            badgesDiv.className = 'property-badges position-absolute top-0 start-0 p-2';

            let badge = document.createElement('span');
            badge.className = `badge ${data.badge_class}`;
            badge.textContent = data.tipo_operacion;

            badgesDiv.appendChild(badge);
            imageDiv.appendChild(badgesDiv);
        }

        let priceSection = document.createElement('div');
        priceSection.className = 'price-section d-flex justify-content-between align-items-center';
        let price = document.createElement('div');
        price.className = 'price';

        let formattedCardPrice = data.precio ? parseFloat(data.precio).toLocaleString('es-CL', { maximumFractionDigits: 0 }) : '';
        price.textContent = formattedCardPrice ? `UF ${formattedCardPrice}` : '';

        let btn = document.createElement('button');
        btn.className = 'btn btn-primary';
        btn.textContent = 'Ver Detalles';
        btn.addEventListener('click', () => window.open(data.permalink, '_blank'));

        priceSection.appendChild(price);
        priceSection.appendChild(btn);

        let infoBar = document.createElement('div');
        infoBar.className = 'info-bar d-flex justify-content-center gap-3';
        if (Array.isArray(data.detalle_rapido)) {
            data.detalle_rapido.forEach(item => {
                let infoItem = document.createElement('div');
                infoItem.className = 'info-item text-center';
                let icon = document.createElement('i');
                icon.className = item.icono;
                infoItem.appendChild(icon);
                infoItem.appendChild(document.createTextNode(` ${item.cantidad} ${item.texto}`));
                infoBar.appendChild(infoItem);
            });
        }

        card.appendChild(imageDiv);
        card.appendChild(priceSection);
        card.appendChild(infoBar);
        col.appendChild(card);
        postsContainer.appendChild(col);
    }

    // === Crear marcadores ===
    markersData.forEach(data => {
        let marker = L.marker([data.lat, data.lng])
            .bindPopup(`<a href="${data.permalink}" target="_blank">${data.title}</a>`);
        leafletMarkers.push({ marker, data });
    });

    // === Filtrar lista y mapa según búsqueda y bounds ===
  function updateVisiblePostsFiltered(searchValue = '') {

    const isMobile = window.innerWidth < 996;
    const query = searchValue.trim().toLowerCase();

    // ================================
    // 📱 MODO MÓVIL (< 996px)
    // → Mostrar siempre TODO
    // ================================
    if (isMobile) {

        // 1. Vaciar lista
        postsContainer.innerHTML = '';

        // 2. Quitar markers actuales
        leafletMarkers.forEach(obj => {
            if (map.hasLayer(obj.marker)) map.removeLayer(obj.marker);
        });

        // 3. Agregar TODOS los markers
        leafletMarkers.forEach(obj => {
            obj.marker.addTo(map);
        });

        // 4. Renderizar TODAS las tarjetas
        leafletMarkers.forEach(obj => renderCard(obj.data));

        return; // 🚀 MISIÓN CUMPLIDA → No aplicar filtros
    }

    // =================================================
    // 🖥️ MODO ESCRITORIO (tu lógica original)
    // =================================================

    postsContainer.innerHTML = '';

    // Quitar markers
    leafletMarkers.forEach(obj => map.removeLayer(obj.marker));

    const bounds = mapDiv.style.display !== 'none' ? map.getBounds() : null;
    let anyVisible = false;

    leafletMarkers.forEach(obj => {
        const title = obj.data.title.toLowerCase();
        const category = obj.data.tipo_operacion?.toLowerCase() || '';
        const markerLatLng = L.latLng(obj.data.lat, obj.data.lng);

        const matchesSearch = !query || title.includes(query) || category.includes(query);
        const withinBounds = !bounds || bounds.contains(markerLatLng);

        if (matchesSearch && withinBounds) {
            renderCard(obj.data);

            if (mapDiv.style.display !== 'none') {
                obj.marker.addTo(map);
            }

            anyVisible = true;
        }
    });

    if (!anyVisible) {
        postsContainer.innerHTML =
            '<p class="alert alert-info text-center w-100 no-results-msg">No hay propiedades que coincidan con la búsqueda actual o el área del mapa.</p>';
    }
}

    // === Toggle mapa/lista con persistencia ===
    let toggleBtn = document.getElementById('toggle-map-btn');
    let mapDiv = document.getElementById('map-container');
    let postListDiv = document.getElementById('post-list');

    const mapState = localStorage.getItem('mapOpen');
    if (mapState === 'false') {
        mapDiv.style.display = 'none';
        toggleBtn.textContent = 'Mostrar mapa';
        postListDiv.classList.add('col-12');
        postListDiv.classList.remove('col-md-6');
        postsContainer.className = 'row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xxl-4 g-3';
    } else {
        postListDiv.classList.add('col-md-6');
        postListDiv.classList.remove('col-12');
    }

    toggleBtn.addEventListener('click', function () {
        if (mapDiv.style.display === 'none' || mapDiv.style.display === '') {
            mapDiv.style.display = 'block';
            toggleBtn.textContent = 'Ver lista';
            postListDiv.classList.remove('col-12');
            postListDiv.classList.add('col-md-6');
            postsContainer.className = 'row row-cols-1 row-cols-md-2 g-3';
            map.invalidateSize();

            const searchInput = document.querySelector('.buscador');
            updateVisiblePostsFiltered(searchInput ? searchInput.value : '');

            localStorage.setItem('mapOpen', 'true');
        } else {
            mapDiv.style.display = 'none';
            toggleBtn.textContent = 'Mostrar mapa';
            postListDiv.classList.add('col-12');
            postListDiv.classList.remove('col-md-6');
            postsContainer.className = 'row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xxl-4 g-3';

            // Renderizar todas las tarjetas aunque el mapa esté oculto
            postsContainer.innerHTML = '';
            leafletMarkers.forEach(obj => renderCard(obj.data));

            localStorage.setItem('mapOpen', 'false');
        }
    });

    // ===============================================
    // 🔗 LÓGICA CENTRAL PARA CONSTRUIR Y RECARGAR LA URL
    // ===============================================

    function cleanNumber(value) {
        let cleaned = value.replace(/\./g, '').replace(',', '.');
        return cleaned.replace(/[^\d.]/g, '');
    }

    function formatNumberInput(event) {
        let input = event.target;
        let cursorPosition = input.selectionStart;

        let rawValue = input.value.replace(/\./g, '').replace(',', '|');
        let parts = rawValue.split('|');
        let integerPart = parts[0].replace(/[^0-9]/g, '');
        let decimalPart = parts.length > 1 ? parts[1].replace(/[^0-9]/g, '') : '';

        if (integerPart === '') {
            input.value = '';
            return;
        }

        let formattedInteger = parseInt(integerPart, 10).toLocaleString('es-CL');

        let formattedValue = formattedInteger;
        if (parts.length > 1) {
            if (rawValue.endsWith('|') && decimalPart === '') {
                formattedValue += ',';
            } else if (decimalPart) {
                formattedValue += ',' + decimalPart;
            }
        }

        let lengthDifference = formattedValue.length - input.value.length;

        input.value = formattedValue;

        if (cursorPosition !== null) {
            input.setSelectionRange(cursorPosition + lengthDifference, cursorPosition + lengthDifference);
        }
    }

    const paramMap = {
        'propiedad': 'propiedad',
        'estado': 'estado',
        'min-price': 'min_price',
        'max-price': 'max_price',
        'min-dormitorios': 'min_dormitorios',
        'min-banos': 'min_banos',
        'comuna': 'comuna'
    };

    function buildUrlAndReload(newParams = {}) {
        const url = new URL(window.location.href);
        const searchParams = url.searchParams;

        const currentFilters = {};
        Object.values(paramMap).forEach(paramKey => {
            currentFilters[paramKey] = searchParams.get(paramKey) || '';
        });

        const finalFilters = { ...currentFilters, ...newParams };

        url.search = '';
        Object.keys(finalFilters).forEach(paramKey => {
            const value = finalFilters[paramKey];
            if (value && String(value).trim() !== '') {
                url.searchParams.append(paramKey, value);
            }
        });

        window.location.href = url.toString();
    }

    function initializeFiltersFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);

        const propValue = urlParams.get(paramMap.propiedad);
        if (propValue) {
            const btn = document.getElementById('btn-propiedad-filter');
            const option = document.querySelector(`.dropdown[data-filter="propiedad"] a[data-value="${propValue}"]`);
            if (btn && option) btn.textContent = option.textContent;
        }

        const estadoValue = urlParams.get(paramMap.estado);
        if (estadoValue) {
            const btn = document.getElementById('btn-estado-filter');
            const option = document.querySelector(`.dropdown[data-filter="estado"] a[data-value="${estadoValue}"]`);
            if (btn && option) btn.textContent = option.textContent;
        }

        const minP = urlParams.get(paramMap['min-price']);
        const maxP = urlParams.get(paramMap['max-price']);
        const minPriceInput = document.getElementById('min-price');
        const maxPriceInput = document.getElementById('max-price');

        if (minPriceInput) minPriceInput.value = minP && !isNaN(parseFloat(minP)) ? parseFloat(minP).toLocaleString('es-CL', { maximumFractionDigits: 2 }) : '';
        if (maxPriceInput) maxPriceInput.value = maxP && !isNaN(parseFloat(maxP)) ? parseFloat(maxP).toLocaleString('es-CL', { maximumFractionDigits: 2 }) : '';

        const minD = urlParams.get(paramMap['min-dormitorios']);
        const minDormitoriosInput = document.getElementById('min-dormitorios');
        if (minDormitoriosInput) minDormitoriosInput.value = minD && !isNaN(parseInt(minD)) ? parseInt(minD) : '';

        const minB = urlParams.get(paramMap['min-banos']);
        const minBanosInput = document.getElementById('min-banos');
        if (minBanosInput) minBanosInput.value = minB && !isNaN(parseInt(minB)) ? parseInt(minB) : '';

        document.querySelector('.buscador').value = urlParams.get(paramMap.comuna) || '';
    }

    // === MANEJO DE EVENTOS ===
    document.querySelectorAll('.filter-option').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const value = this.getAttribute('data-value');
            const filterType = this.closest('.dropdown').getAttribute('data-filter');
            const paramKey = paramMap[filterType];
            buildUrlAndReload({ [paramKey]: value });
        });
    });

    document.querySelectorAll('.apply-filter-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const filterType = this.getAttribute('data-filter-type');
            const params = {};

            if (filterType === 'precio') {
                params[paramMap['min-price']] = cleanNumber(document.getElementById('min-price').value.trim());
                params[paramMap['max-price']] = cleanNumber(document.getElementById('max-price').value.trim());
            } else if (filterType === 'dormitorios') {
                const value = document.getElementById('min-dormitorios').value.trim();
                params[paramMap['min-dormitorios']] = value && !isNaN(parseInt(value)) ? parseInt(value) : '';
            } else if (filterType === 'banos') {
                const value = document.getElementById('min-banos').value.trim();
                params[paramMap['min-banos']] = value && !isNaN(parseInt(value)) ? parseInt(value) : '';
            }

            buildUrlAndReload(params);
        });
    });

    document.getElementById('clear-filters-btn').addEventListener('click', function () {
        window.location.href = window.location.pathname;
    });

    const searchContainer = document.querySelector('.buscador-container');
    if (searchContainer) {
        const searchInput = searchContainer.querySelector('.buscador');
        const icon = searchContainer.querySelector('i.fa-magnifying-glass');

        function searchAndReload() {
            const value = searchInput.value.trim();
            buildUrlAndReload({ [paramMap.comuna]: value });
        }

        icon.addEventListener('click', searchAndReload);
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchAndReload();
            }
        });
    }

    // Añadir listeners a inputs de precio
    const minPriceInput = document.getElementById('min-price');
    const maxPriceInput = document.getElementById('max-price');

    if (minPriceInput) minPriceInput.addEventListener('input', formatNumberInput);
    if (maxPriceInput) maxPriceInput.addEventListener('input', formatNumberInput);

    // Inicializar filtros desde URL
    initializeFiltersFromUrl();

    // Inicializar lista y mapa
    if (mapDiv.style.display === 'none') {
        // Mostrar todas las tarjetas si el mapa está oculto
        postsContainer.innerHTML = '';
        leafletMarkers.forEach(obj => renderCard(obj.data));
    } else {
        updateVisiblePostsFiltered();
    }

    // Actualizar al mover/zoom mapa
    map.on('moveend zoomend', function () {
        if (mapDiv.style.display !== 'none') {
            const searchInput = document.querySelector('.buscador');
            const value = searchInput ? searchInput.value.trim() : '';
            updateVisiblePostsFiltered(value);
        }
    });

});
