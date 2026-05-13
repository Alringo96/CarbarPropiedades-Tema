document.addEventListener('DOMContentLoaded', () => {

  const buttons = document.querySelectorAll('.filter-buttons button');
  const carouselInner = document.querySelector('.carousel-inner');
  const allCards = Array.from(document.querySelectorAll('.col-md-4'));
  const noResultsMessage = document.getElementById('no-results-message');
  const cardsPerSlide = 3; // puedes cambiarlo a 4 si prefieres
  const carousel = document.querySelector('#propertyCarousel');

  function renderSlides(filteredCards) {
    carouselInner.innerHTML = ''; // Limpiar el carrusel actual

    if (filteredCards.length === 0) {
      if (noResultsMessage) noResultsMessage.style.display = 'block';
      return;
    } else {
      if (noResultsMessage) noResultsMessage.style.display = 'none';
    }

    let slide;
    filteredCards.forEach((card, index) => {
      if (index % cardsPerSlide === 0) {
        slide = document.createElement('div');
        slide.className = `carousel-item ${index === 0 ? 'active' : ''}`;
        const row = document.createElement('div');
        row.className = 'row g-4';
        slide.appendChild(row);
        carouselInner.appendChild(slide);
      }

      // Clonar la tarjeta para mantener eventos e imágenes
      const cardClone = card.cloneNode(true);
      slide.querySelector('.row').appendChild(cardClone);
    });

    // Reiniciar el carrusel
    if (carousel && typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
      const carouselInstance = bootstrap.Carousel.getInstance(carousel) || new bootstrap.Carousel(carousel);
      carouselInstance.to(0);
    }
  }

  // === FILTRO ===
  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const filter = btn.getAttribute('data-filter').toLowerCase();

      // Activar botón seleccionado
      buttons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      let filteredCards = allCards.filter(card => {
        const type = card.getAttribute('data-type')?.toLowerCase() || '';

        // “Arriendo temporal” cuenta también como “Arriendo”
        return (
          filter === 'all' ||
          type.includes(filter) ||
          (filter === 'Arriendo' && type.includes('Arriendo temporal'))
        );
      });

      renderSlides(filteredCards);
    });
  });

  // Mostrar todo al inicio
  renderSlides(allCards);
});
