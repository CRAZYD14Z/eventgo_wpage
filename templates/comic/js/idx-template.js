    new Swiper(".swiperCategories", {
        slidesPerView:1, spaceBetween:10, loop:true,
        autoplay:{delay:2800, disableOnInteraction:false},
        breakpoints:{640:{slidesPerView:2},768:{slidesPerView:3},1024:{slidesPerView:5}},
        pagination:{el:".swiper-pagination",clickable:true},
    });
    new Swiper(".swiperReviews", {
        slidesPerView:1, spaceBetween:20, loop:true,
        autoplay:{delay:5000},
        navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"},
        breakpoints:{768:{slidesPerView:2}}
    });
    new Swiper(".swiperEvents", {
        slidesPerView:1, spaceBetween:20, loop:true,
        autoplay:{delay:3500, disableOnInteraction:false},
        breakpoints:{640:{slidesPerView:2},1024:{slidesPerView:4}},
        pagination:{el:".swiper-pagination",clickable:true},
    });

    const movingLogo = document.getElementById('movingLogo');
    const aboutLogoContainer = document.querySelector('.about-logo-container');
    if (movingLogo && aboutLogoContainer) {
        aboutLogoContainer.addEventListener('mousemove', (e) => {
            const r = aboutLogoContainer.getBoundingClientRect();
            const nx = ((e.clientX - r.left) / r.width)  * 2 - 1;
            const ny = ((e.clientY - r.top)  / r.height) * 2 - 1;
            movingLogo.style.transform = `translate(${nx*25}px,${ny*25}px)`;
        });
        aboutLogoContainer.addEventListener('mouseleave', () => {
            movingLogo.style.transform = 'translate(0,0)';
        });
    }