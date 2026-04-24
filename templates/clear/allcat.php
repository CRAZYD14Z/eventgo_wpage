    <section class="mb-5">
        <h2 class="section-title">Explora Todo el Catálogo</h2>
        <div class="row g-4">
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