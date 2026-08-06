<?php 
    $SecTitle = "";
    if ($_SESSION['Idioma'] == 'es')
        $SecTitle = "SOMOS SU MEJOR OPCION PARA";
    else
        $SecTitle = "WE ARE THE PLACE FOR";    

?>

<section class="mb-5 py-4" id="eventos">
        <h2 class="section-title"><?= $SecTitle ?></h2>
        <div class="swiper swiperEvents">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="event-card">
                        <i class="fas fa-glass-cheers"></i>
                        <h5>💍 Bodas</h5>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="event-card">
                        <i class="fas fa-birthday-cake"></i>
                        <h5>🎂 Cumpleaños</h5>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="event-card">
                        <i class="fas fa-briefcase"></i>
                        <h5>🏢 Corporativos</h5>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="event-card">
                        <i class="fas fa-graduation-cap"></i>
                        <h5>🎓 Graduaciones</h5>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="event-card">
                        <i class="fas fa-music"></i>
                        <h5>🎶 Conciertos</h5>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination mt-4"></div>
        </div>
    </section>