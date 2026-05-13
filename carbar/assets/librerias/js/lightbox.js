 document.addEventListener('DOMContentLoaded', () => {
        const lightbox = document.querySelector('.lightbox');
        const lightboxImg = document.querySelector('.lightbox-img');
        const closeBtn = document.querySelector('.lightbox .close');
        const prevBtn = document.querySelector('.lightbox .prev');
        const nextBtn = document.querySelector('.lightbox .next');

        const images = document.querySelectorAll('.swiper-slide .masonry-grid img');
        let currentIndex = 0;

        // Abrir lightbox al hacer clic
        images.forEach((img, index) => {
            img.addEventListener('click', () => {
                currentIndex = index;
                openLightbox();
            });
        });

        function openLightbox() {
            lightbox.style.display = 'flex';
            lightboxImg.src = images[currentIndex].src;
            document.body.style.overflow = 'hidden'; // Evita scroll en fondo
        }

        function closeLightbox() {
            lightbox.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function showPrev() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            lightboxImg.src = images[currentIndex].src;
        }

        function showNext() {
            currentIndex = (currentIndex + 1) % images.length;
            lightboxImg.src = images[currentIndex].src;
        }

        closeBtn.addEventListener('click', closeLightbox);
        prevBtn.addEventListener('click', showPrev);
        nextBtn.addEventListener('click', showNext);

        // Cerrar al hacer clic fuera de la imagen
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) closeLightbox();
        });

        // Navegación con teclado
        document.addEventListener('keydown', (e) => {
            if (lightbox.style.display === 'flex') {
                if (e.key === 'ArrowRight') showNext();
                if (e.key === 'ArrowLeft') showPrev();
                if (e.key === 'Escape') closeLightbox();
            }
        });
    });
