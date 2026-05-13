document.addEventListener('DOMContentLoaded', () => {
    const currentImageIndex = {};

    // Inicializa índices
    Object.keys(propertyImages).forEach(id => currentImageIndex[id] = 0);

    // Delegación de eventos: escucha clicks en todo el documento
    document.addEventListener('click', function(e) {
        const arrow = e.target.closest('.arrow');
        if (!arrow) return;

        const imgElement = arrow.closest('.property-image').querySelector('img');
        const imgId = imgElement.id.replace('property-img-', '');
        const isLeft = arrow.classList.contains('left');
        const imgs = propertyImages[imgId];
        if (!imgs || imgs.length === 0) return;

        currentImageIndex[imgId] =
            (currentImageIndex[imgId] + (isLeft ? -1 : 1) + imgs.length) % imgs.length;

        imgElement.src = imgs[currentImageIndex[imgId]];
    });
});
