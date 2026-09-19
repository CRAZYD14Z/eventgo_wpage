<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "categories"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 

<section class="mb-5">
    <h2 class="section-title"><?= Trd(1) ?></h2>
    <div class="swiper swiperCategories">
        <div class="swiper-wrapper">
            <?php
                $api_url = URL_API."categories";
                //$data = json_encode(["Product" => $_GET['Id']]);
                $data='';
                $data = json_decode(API($jwt,$api_url,$data,'POST'), true);
                if ($data['status'] === 'success') {
                    foreach ($data['data'] as $category) {
                        $imagen = trim(isset($category['Imagen']) ? (string)$category['Imagen'] : '');
                        $category['Imagen'] = $imagen !== ''
                            ? URL_IMAGES.'/categories/thumbnails/'.$imagen
                            : 'src/img/noimage.svg';
                        $URL = str_replace(" ","-",$category['Nombre']);
                        echo "<div class='swiper-slide'><a href='".URL_BASE."/products/{$URL}' class='cat-card' style='overflow:hidden;'><img src='{$category['Imagen']}' alt='{$category['Nombre']}' style='display:block;width:100%;max-width:100%;height:150px;object-fit:contain;'> </a>{$category['Nombre']}</div>";
                    }        
                } 
            ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>