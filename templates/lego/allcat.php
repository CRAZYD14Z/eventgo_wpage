<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "allcat"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 
<div class="lego-divider">
        <span>
            <div class="mini-stud" style="background:var(--lego-blue);"></div>
            <div class="mini-stud" style="background:var(--lego-green);"></div>
            <div class="mini-stud" style="background:var(--lego-yellow);"></div>
            <div class="mini-stud" style="background:var(--lego-red);"></div>
            <div class="mini-stud" style="background:var(--lego-orange);"></div>
        </span>
    </div>
    <section class="mb-5 section-brick brick-orange">
        <div class="section-studs"></div>
        <div class="container">
            <h2 class="section-title" style="color:var(--lego-yellow);">🟧 <?= Trd(1) ?></h2>
            <p class="section-sub" style="color:rgba(255,255,255,.75);">— ¡todos los bloques disponibles! —</p>
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
        </div>
    </section>