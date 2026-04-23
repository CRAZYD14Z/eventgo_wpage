    <div class="comic-divider" data-label="⚡ LA GENTE HABLA ⚡"></div>

    <!-- REVIEWS -->
    <section class="mb-5">
        <div class="caption-box">📖 CAPÍTULO 2 — TESTIMONIOS HEROICOS</div>
        <h2 class="section-title" style="color:var(--blue);">¡La Tribuna del Héroe!</h2>
        <p class="section-sub">— Ciudadanos que ya fueron salvados —</p>
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
                echo "
                                <div class='swiper-slide'>
                                    <div class='review-card'>
                                        <div class='stars'>".mostrarEstrellas($review['rating'])."</div>
                                        <p class='fst-italic'>" . htmlspecialchars($review['text']) . "</p>
                                        <h6 class='mb-0'><strong>" . htmlspecialchars($review['author_name']) . "</strong></h6>
                                    </div>
                                </div>
                ";

                    }
                } else {
                    echo "Error al obtener las reseñas: " . $data['status'];
                }
                ?>  
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>