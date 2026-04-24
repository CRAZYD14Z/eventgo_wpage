    <div class="comic-divider" data-label="💥 CATÁLOGO DE PODERES 💥"></div>

    <!-- CATÁLOGO GRID -->
    <section class="mb-5">
        <div class="caption-box">📖 CAPÍTULO 3 — EL UNIVERSO COMPLETO</div>
        <h2 class="section-title" style="color:var(--green);">Explora el Catálogo</h2>
        <p class="section-sub">— ¡Un universo de posibilidades! —</p>
        <div class="row g-2">
                <?php
                    $api_url = URL_API."categories";
                    //$data = json_encode(["Product" => $_GET['Id']]);
                    $data='';
                    $data = json_decode(API($jwt,$api_url,$data,'POST'), true);
                    if ($data['status'] === 'success') {
                        foreach ($data['data'] as $category) {
                            $category['Imagen'] = URL_IMAGES.'/categories/thumbnails/'.$category['Imagen'];
                            
                            $URL = str_replace(" ","-",$category['Nombre']);
                            echo "<div class='col-6 col-md-4 col-lg-2'><a href='".URL_BASE."/products/{$URL}' class='cat-card'><img height='150px' src='{$category['Imagen']}' alt='{$category['Nombre']}'> </a></div>";
                        }        
                    } 
                ?>
        </div>
    </section>