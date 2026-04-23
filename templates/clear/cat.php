    <section class="mb-5">
        <h2 class="section-title">Categorías Destacadas</h2>
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
            <div class="swiper-pagination"></div>
        </div>
    </section>