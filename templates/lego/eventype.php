<?php 
    $SecTitle = "";
    if ($_SESSION['Idioma'] == 'es')
        $SecTitle = "SOMOS SU MEJOR OPCION PARA";
    else
        $SecTitle = "WE ARE THE PLACE FOR";    

?>

<section class="mb-5 py-4 section-brick brick-red" id="eventos">
        <div class="section-studs"></div>
        <div class="container">
            <h2 class="section-title" style="color:var(--lego-yellow);">🟥 <?= $SecTitle ?></h2>
            <p class="section-sub" style="color:rgba(255,255,255,.75);">— ¡para cada construcción especial! —</p>
            <div class="swiper swiperEvents">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="event-card">
                            <div class="card-studs"><div class="stud"></div><div class="stud"></div></div>
                            <i class="fas fa-glass-cheers"></i><h5>💍 Bodas</h5>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="event-card">
                            <div class="card-studs"><div class="stud"></div><div class="stud"></div></div>
                            <i class="fas fa-birthday-cake"></i><h5>🎂 Cumpleaños</h5>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="event-card">
                            <div class="card-studs"><div class="stud"></div><div class="stud"></div></div>
                            <i class="fas fa-briefcase"></i><h5>🏢 Corporativos</h5>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="event-card">
                            <div class="card-studs"><div class="stud"></div><div class="stud"></div></div>
                            <i class="fas fa-graduation-cap"></i><h5>🎓 Graduaciones</h5>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="event-card">
                            <div class="card-studs"><div class="stud"></div><div class="stud"></div></div>
                            <i class="fas fa-music"></i><h5>🎶 Conciertos</h5>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination mt-4"></div>
            </div>
        </div>
    </section>