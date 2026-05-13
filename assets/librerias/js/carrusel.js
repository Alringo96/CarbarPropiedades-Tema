document.addEventListener('DOMContentLoaded', function() {
    const swiperContainers = document.querySelectorAll('.mySwiper');
    swiperContainers.forEach((container) => {
        new Swiper(container, {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 10,
            navigation: {
                nextEl: container.querySelector('.swiper-button-next'),
                prevEl: container.querySelector('.swiper-button-prev'),
            },
            pagination: {
                el: container.querySelector('.swiper-pagination'),
                clickable: true,
            },
        });
    });
});
