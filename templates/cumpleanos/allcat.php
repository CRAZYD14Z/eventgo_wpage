<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "allcat"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 
<div class="party-divider"><span>🎁 ⭐ 🎁</span></div>
    <section class="mb-5">
        <div class="section-center">
            <h2 class="section-title"><?= Trd(1) ?>
                <span class="title-tag">¡Hay de todo aquí!</span>
            </h2>
        </div>
        <div class="row g-3">
                <?php
                    $api_url = URL_API."categories";
                    //$data = json_encode(["Product" => $_GET['Id']]);
                    $data='';
                    $data = json_decode(API($jwt,$api_url,$data,'POST'), true);
                    if ($data['status'] === 'success') {
                        foreach ($data['data'] as $category) {
                            $category['Imagen'] = URL_IMAGES.'/categories/thumbnails/'.$category['Imagen'];
                            
                            $URL = str_replace(" ","-",$category['Nombre']);
                            echo "<div class='col-6 col-md-4 col-lg-3'><a href='".URL_BASE."/products/{$URL}' class='cat-card'><img height='150px' src='{$category['Imagen']}' alt='{$category['Nombre']}'> </a></div>";
                        }        
                    } 
                ?>
        </div>
    </section>