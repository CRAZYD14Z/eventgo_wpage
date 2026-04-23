    <section class="mb-5" id="categorias">
        <div class="caption-box">📖 CAPÍTULO 1 — ESCOGE TU PODER</div>
        <h2 class="section-title" style="color:var(--red);">Categorías Destacadas</h2>
        <p class="section-sub">— ¡Cada héroe tiene su especialidad! —</p>
        <div class="swiper swiperCategories">
            <div class="swiper-wrapper">

                <?php
                    $api_url = URL_API."categories";
                    //$data = json_encode(["Product" => $_GET['Id']]);
                    $data='';
                    $data = json_decode(API($jwt,$api_url,$data,'POST'), true);
                    if ($data['status'] === 'success') {
                        foreach ($data['data'] as $category) {
                            $category['Imagen'] = URL_IMAGES.$category['Imagen'];
                            $URL = str_replace(" ","-",$category['Nombre']);
                            echo "<div class='swiper-slide'><a href='products/{$URL}' class='cat-card'><img height='150px' src='{$category['Imagen']}' alt='{$category['Nombre']}'> </a></div>";
                        }        
                    } 
                ?>                

            </div>
            <div class="swiper-pagination mt-4"></div>
        </div>
    </section>