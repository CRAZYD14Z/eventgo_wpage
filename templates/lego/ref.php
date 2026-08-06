<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "ref"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 
<div class="lego-divider">
        <span>
            <div class="mini-stud" style="background:var(--lego-orange);"></div>
            <div class="mini-stud" style="background:var(--lego-blue);"></div>
            <div class="mini-stud" style="background:var(--lego-red);"></div>
            <div class="mini-stud" style="background:var(--lego-yellow);"></div>
            <div class="mini-stud" style="background:var(--lego-green);"></div>
        </span>
    </div>
    
    <section class="mb-5 section-brick brick-green">
        <div class="section-studs"></div>
        <div class="container">
            <h2 class="section-title" style="color:var(--lego-yellow);">🟩 <?= Trd(1) ?></h2>
            <p class="section-sub" style="color:rgba(255,255,255,.75);">— opiniones de nuestros constructores —</p>
            <div class="swiper swiperReviews">
                <div class="swiper-wrapper">

                
                <?php
                // Configuración
                $api_key = GOOGLE_API_KEY;
                $place_id = PLACE_ID;

                // URL de la API de Google Places (solicitando específicamente las reseñas)
                $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=$place_id&fields=name,rating,reviews&key=$api_key&language=es";

                // Realizar la petición
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                curl_close($ch);

                // Decodificar la respuesta
                $data = json_decode($response, true);

                function mostrarEstrellas($rating) {
                    $estrellas = "";
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            // Estrella rellena (Dorada)
                            $estrellas .= "<span style='color: #f1c40f;'>★</span>";
                        } else {
                            // Estrella vacía (Gris)
                            $estrellas .= "<span style='color: #ccc;'>☆</span>";
                        }
                    }
                    return $estrellas;
                }

                if ($data['status'] == 'OK') {
                    $place_name = $data['result']['name'];
                    $reviews = $data['result']['reviews'];

                    //echo "<h2>Reseñas de $place_name</h2>";

                    foreach ($reviews as $review) {
                //        echo "<div style='border: 1px solid #ccc; margin-bottom: 10px; padding: 10px;'>";
                //        echo "<strong>" . htmlspecialchars($review['author_name']) . "</strong>";
                //        echo " - Calificación: " . $review['rating'] . " ⭐<br>";
                //        echo "<p>" . htmlspecialchars($review['text']) . "</p>";
                //        echo "</div>";

                
                echo '
                
                    <div class="swiper-slide" style="padding-bottom:10px;">
                        <div class="review-card">
                            <div class="card-studs"><div class="stud"></div><div class="stud"></div><div class="stud"></div><div class="stud"></div></div>
                            <div class="stars" style="color:var(--lego-yellow);">'.mostrarEstrellas($review['rating']).'</div>
                            <p>"'. htmlspecialchars($review['text']) .'"</p>
                            <h6 style="color:var(--lego-red);">— '. htmlspecialchars($review['author_name']) .' 🧱</h6>
                        </div>
                    </div>                

                ';
                    }
                } else {
                    echo "Error al obtener las reseñas: " . $data['status'];
                }
                ?>                   

                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>