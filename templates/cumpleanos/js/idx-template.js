    /* ── SWIPER ── */
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

    /* ── MOVING LOGO ── */
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

    /* ── CART ── */
    let itemCount = 0;
    const cartCountEl = document.getElementById('cartCount');
    function agregarAlCarrito() { itemCount++; cartCountEl.innerText = itemCount; }

    /* ── CONFETTI ── */
    const canvas = document.getElementById('confetti-canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });

    const COLORS = ['#ff6eb4','#ffe566','#6edcc4','#c4a8ff','#7dd6ff','#ffb380','#ff3d9a','#b3f5e5'];
    const pieces = [];

    class Piece {
        constructor() { this.reset(true); }
        reset(initial = false) {
            this.x = Math.random() * canvas.width;
            this.y = initial ? Math.random() * -canvas.height : -16;
            this.w = Math.random() * 10 + 6;
            this.h = Math.random() * 6 + 3;
            this.color = COLORS[Math.floor(Math.random() * COLORS.length)];
            this.rot = Math.random() * Math.PI * 2;
            this.rotSpeed = (Math.random() - 0.5) * 0.12;
            this.vx = (Math.random() - 0.5) * 1.5;
            this.vy = Math.random() * 2 + 1;
            this.opacity = Math.random() * 0.5 + 0.5;
            this.shape = Math.random() > 0.5 ? 'rect' : 'circle';
        }
        draw() {
            ctx.save();
            ctx.globalAlpha = this.opacity;
            ctx.fillStyle = this.color;
            ctx.translate(this.x, this.y);
            ctx.rotate(this.rot);
            if (this.shape === 'rect') {
                ctx.fillRect(-this.w/2, -this.h/2, this.w, this.h);
            } else {
                ctx.beginPath();
                ctx.arc(0, 0, this.w/2, 0, Math.PI * 2);
                ctx.fill();
            }
            ctx.restore();
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            this.rot += this.rotSpeed;
            if (this.y > canvas.height + 20) this.reset();
        }
    }

    for (let i = 0; i < 120; i++) pieces.push(new Piece());

    function animateConfetti() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        pieces.forEach(p => { p.update(); p.draw(); });
        requestAnimationFrame(animateConfetti);
    }
    animateConfetti();