    // Inicializar Carrusel Categorías    
    new Swiper(".swiperCategories", {
        slidesPerView: 1,
        spaceBetween: 10,
        loop: true,
        autoplay: { delay: 3000, disableOnInteraction: false },
        breakpoints: {
            640: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1024: { slidesPerView: 5 },
        },
        pagination: { el: ".swiper-pagination", clickable: true },
    });

    // Inicializar Carrusel Reviews
    new Swiper(".swiperReviews", {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: { delay: 5000 },
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        breakpoints: {
            768: { slidesPerView: 2 },
        }
    });

    // JavaScript para el logo que sigue al puntero
    const movingLogo = document.getElementById('movingLogo');
    const aboutLogoContainer = document.querySelector('.about-logo-container');
    const movementRange = 20; // Pixeles de movimiento máximo

    if (movingLogo && aboutLogoContainer) {
        aboutLogoContainer.addEventListener('mousemove', (e) => {
            const containerRect = aboutLogoContainer.getBoundingClientRect();
            
            // Calcula la posición relativa del mouse dentro del contenedor
            const mouseX = e.clientX - containerRect.left;
            const mouseY = e.clientY - containerRect.top;

            // Normaliza la posición a un rango de -1 a 1
            const normalizedX = (mouseX / containerRect.width) * 2 - 1;
            const normalizedY = (mouseY / containerRect.height) * 2 - 1;

            // Calcula el desplazamiento del logo
            const offsetX = normalizedX * movementRange;
            const offsetY = normalizedY * movementRange;

            movingLogo.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
        });

        // Reiniciar posición cuando el mouse sale del contenedor
        aboutLogoContainer.addEventListener('mouseleave', () => {
            movingLogo.style.transform = `translate(0px, 0px)`;
        });
    }

    new Swiper(".swiperEvents", {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: { delay: 3500, disableOnInteraction: false },
        breakpoints: {
            640: { slidesPerView: 2 },
            1024: { slidesPerView: 4 },
        },
        pagination: { el: ".swiper-pagination", clickable: true },
    });    
