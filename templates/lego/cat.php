<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "categories"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 

    <div class="lego-divider">
        <span>
            <div class="mini-stud" style="background:var(--lego-red);"></div>
            <div class="mini-stud" style="background:var(--lego-yellow);"></div>
            <div class="mini-stud" style="background:var(--lego-blue);"></div>
            <div class="mini-stud" style="background:var(--lego-green);"></div>
            <div class="mini-stud" style="background:var(--lego-orange);"></div>
        </span>
    </div>

    <section class="mb-5 section-brick brick-blue" id="categorias">
        <div class="section-studs"></div>
        <div class="container">
            <h2 class="section-title" style="color:var(--lego-yellow);">🟦 <?= Trd(1) ?></h2>
            <p class="section-sub" style="color:rgba(255,255,255,.75);">— ¡elige tu bloque favorito! —</p>
            <div class="swiper swiperCategories">
                <div class="swiper-wrapper">

            <?php
                $api_url = URL_API."categories";
                //$data = json_encode(["Product" => $_GET['Id']]);
                $data='';
                $data = json_decode(API($jwt,$api_url,$data,'POST'), true);
                if ($data['status'] === 'success') {
                    foreach ($data['data'] as $category) {
                        $category['Imagen'] = URL_IMAGES.'/categories/thumbnails/'.$category['Imagen'];
                        $URL = str_replace(" ","-",$category['Nombre']);
                        echo "<div class='swiper-slide'><a href='products/{$URL}' class='cat-card'><img height='150px' src='{$category['Imagen']}' alt='{$category['Nombre']}'> </a></div>";
                    }        
                } 
            ?>                

                </div>
                <div class="swiper-pagination mt-4"></div>
            </div>
        </div>
    </section>